# Laporan Bug Bounty — BuanaNet System

| | |
|---|---|
| **Target** | `http://157.20.244.207:9542/` |
| **Nama Aplikasi** | BuanaNet System (Manajemen WiFi/Billing) |
| **Tanggal Tes** | 16 Agustus 2026 |
| **Stack** | Laravel 13.6.0, PHP 8.4.21, MySQL, nginx |
| **Akun Tes** | `uroo` (Role: Owner) |
| **Metodologi** | Black-box + authenticated testing, semua tes non-destruktif |
| **Otorisasi** | Diizinkan oleh pemilik |

---

## Ringkasan

Aplikasi manajemen WiFi/billing dengan modul invoice, tagihan, kasbon, pelanggan, produk, kas, laporan, absensi, cabang, pajak, slip gaji, repeater, biaya operasional, pegawai, akun, dan backup database. Sistem multi-user dengan scoping per cabang.

---

## Temuan

### 🔴 HIGH — 1. Debug Mode Aktif (`APP_DEBUG=true`)

**Deskripsi:**
Halaman error menampilkan stack trace penuh. Dipicu oleh request `POST /dashboard` yang memunculkan `MethodNotAllowedHttpException`.

**Detail yang bocor:**
- Versi framework: **Laravel 13.6.0**, **PHP 8.4.21**
- Database: **MySQL** dengan query `select exists (select 1 from information_schema.tables where table_schema = schema() ...)` dan query tabel `pengaturan`
- Path internal: `vendor/laravel/framework/src/...`, `public/index.php`
- IP internal dan informasi request lengkap

**Dampak:**
Membocorkan versi framework, struktur path, dan konfigurasi database yang memudahkan attacker menyusun eksploitasi terarah.

**Rekomendasi:**
Set `APP_DEBUG=false` pada environment produksi.

**Bukti:**
```
## Stack Trace
0 - vendor/laravel/framework/src/Illuminate/Routing/AbstractRouteCollection.php:130
...
31 - public/index.php:20

PHP 8.4.21
Laravel 13.6.0
157.20.244.207:9542
```

---

### 🔴 HIGH — 2. Tidak Ada TLS/HTTPS — Kredensial Dikirim Plaintext

**Deskripsi:**
Seluruh layanan hanya tersedia via HTTP (koneksi ke port 443 gagal). Username, password, dan session cookie dikirim tanpa enkripsi melalui internet publik.

**Dampak:**
- Password login (`uroo1234`) dapat disadap via serangan man-in-the-middle (MITM) di jaringan publik
- Session cookie dapat dicuri dan digunakan untuk session hijacking
- Cookie `laravel-session` tidak memiliki flag `Secure`

**Rekomendasi:**
1. Pasang sertifikat SSL/TLS dan aktifkan HTTPS
2. Set `'secure' => true` pada `config/session.php`
3. Tambahkan redirect HTTP → HTTPS

---

### 🟠 MEDIUM — 3. Tidak Ada Rate-Limiting pada Login

**Deskripsi:**
8 percobaan login dengan password salah secara berurutan dan cepat semuanya mendapat respons `302 Found` tanpa adanya throttle/429.

**Dampak:**
Brute-force login terbuka lebar, terutama berbahaya jika digabungkan dengan password lemah (temuan #4).

**Rekomendasi:**
Terapkan throttling pada route login, misalnya dengan `ThrottleRequests` / `RateLimiter` di Laravel, serta lockout akun setelah beberapa percobaan gagal.

---

### 🟠 MEDIUM — 4. Password Akun Owner Lemah & Tanpa 2FA

**Deskripsi:**
Akun `uroo` dengan role **Owner** (akses penuh ke semua data keuangan) menggunakan password sederhana `uroo1234` dan tidak dilindungi 2FA.

**Dampak:**
Jika password terungkap (via brute-force atau kebocoran lain), attacker mendapat akses penuh ke seluruh data sensitif.

**Rekomendasi:**
1. Gunakan password kuat (min. 12 karakter, kombinasi)
2. Aktifkan 2FA (misalnya TOTP)
3. Terapkan kebijakan pergantian password berkala

---

### 🟡 LOW — 5. Security Headers Hilang

**Deskripsi:**
Respons HTTP tidak menyertakan header keamanan standar:
- `Content-Security-Policy`
- `Strict-Transport-Security`
- `X-Frame-Options`
- `X-Content-Type-Options`
- `Referrer-Policy`

**Dampak:**
Risiko clickjacking, MIME-sniffing, dan berkurangnya pertahanan berlapis.

**Rekomendasi:**
Tambahkan middleware untuk menyisipkan header keamanan tersebut.

---

## Hal yang Sudah Aman ✅

| Item | Status |
|---|---|
| **CSRF Protection** | ✅ Semua form state-changing (logout, delete, toggle-status) memiliki token `_token` |
| **User Enumeration** | ✅ Pesan error login generik ("Login gagal. Pastikan username dan password benar.") |
| **File Sensitif** | ✅ `.env`, `.git/config`, `/server-status` → 404 (tidak ter-expose) |
| **CORS** | ✅ Tidak ada refleksi Origin (`evil.com` ditolak) |
| **Registrasi Publik** | ✅ `/register` dan `/forgot-password` → 404 |
| **SQL Injection** | ✅ Probe pada `pelanggan_id` tidak menghasilkan SQL error (parameterized/validated) |
| **API Publik** | ✅ Tidak ada route API ter-expose |

---

## Catatan & Pekerjaan Lanjutan

1. **Access Control / IDOR belum tuntas.** Akun tes `uroo` adalah Owner sehingga melihat semua cabang. Untuk menguji privilege escalation horizontal (mis. user cabang ANAM membaca data cabang KEMUNING), diperlukan **akun test dengan role rendah** (pegawai/kasir).
2. **Backup database** (`/pengaturan/backup/{id}/download`) diakses via GET dengan autentikasi. Tidak diunduh karena berisi data produksi penuh. Perlu dicek apakah endpoint ini aman dari IDOR untuk user role rendah.
3. Semua tes dilakukan **non-destruktif**; tidak ada data produksi yang diubah atau dihapus.

---

## Prioritasi Perbaikan

1. Matikan debug mode (`APP_DEBUG=false`) — **segera**
2. Pasang HTTPS + force redirect — **segera**
3. Ganti password Owner + aktifkan 2FA — **segera**
4. Tambah rate-limiting pada login — **1 minggu**
5. Tambah security headers — **1 minggu**
