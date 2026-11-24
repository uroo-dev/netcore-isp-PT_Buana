# netcore-isp-PT_Buana

<h1 align="center">🌐 PT Buana Network System (BuanaNet)</h1>

<p align="center">
  <strong>Platform Terintegrasi Manajemen ISP, Penagihan Otomatis (Billing), Keuangan Real-Time, Multi-Cabang, dan Penggajian Karyawan</strong>
</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
  <a href="#"><img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"></a>
  <a href="#"><img src="https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind"></a>
  <a href="#"><img src="https://img.shields.io/badge/Testing-Pest_PHP_v4-F3B000?style=for-the-badge&logo=pest&logoColor=black" alt="Pest"></a>
  <a href="#"><img src="https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker"></a>
  <a href="#"><img src="https://img.shields.io/badge/Code_Style-Laravel_Pint-success?style=for-the-badge" alt="Pint"></a>
  <a href="#"><img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License"></a>
</p>

---

## 📌 Ringkasan Eksekutif (Executive Summary)

**BuanaNet System** dikembangkan untuk menjawab tantangan operasional harian perusahaan penyedia jasa internet (ISP) dan pengelola jaringan RT/RW Net berskala multi-cabang. Sistem ini mengintegrasikan seluruh proses bisnis mulai dari pendaftaran pelanggan, pemetaan paket & penanggung jawab wilayah, penerbitan tagihan (invoice) otomatis, pembayaran termin/kasbon, manajemen arus kas, distribusi logistik/stok barang antar cabang, hingga presensi dan penggajian (payroll) karyawan.

### 🎯 Nilai Bisnis Utama (Business Value):
1. **Otomatisasi Penagihan & Monitoring Piutang**: Meminimalkan tagihan terlewat dengan modul invoice otomatis, riwayat cicilan kasbon, dan status pembayaran real-time.
2. **Visibilitas Finansial Multi-Cabang**: Pemisahan data operasional per cabang dengan konsolidasi arus kas terpusat untuk Owner.
3. **Audit Trail & Keamanan Ketat**: Setiap aksi pengguna, mutasi stok, dan log akses dicatat secara sistematis untuk transparansi data dan akuntabilitas tim.

---

## 🏗️ Rancangan Arsitektur Sistem (System Architecture & Design)

Sistem dibangun dengan pola arsitektur berlapis (*Layered MVC*) yang menjamin skalabilitas, keterbacaan kode (*clean code*), dan kemudahan pengujian.

```
+-------------------------------------------------------------------------------+
|                             PRESENTATION LAYER                                |
|   Blade Templates  •  Tailwind CSS v4  •  Flowbite UI  •  Vite Asset Bundler  |
+-------------------------------------------------------------------------------+
                                      │
                                      ▼
+-------------------------------------------------------------------------------+
|                         SECURITY & MIDDLEWARE LAYER                           |
|  SecurityHeaders (CSP, X-Frame) • CheckRole (RBAC) • ThrottleRequests • Logs  |
+-------------------------------------------------------------------------------+
                                      │
                                      ▼
+-------------------------------------------------------------------------------+
|                             APPLICATION LAYER                                 |
|  Controllers (Pelanggan, Invoice, Kas, Produk, Absensi, SlipGaji, Backup, dll)|
+-------------------------------------------------------------------------------+
                                      │
                                      ▼
+-------------------------------------------------------------------------------+
|                              DOMAIN & DATA LAYER                              |
|   Eloquent ORM  •  Model Relationships  •  Event Observers  •  Query Scopes   |
+-------------------------------------------------------------------------------+
                                      │
                                      ▼
+-------------------------------------------------------------------------------+
|                            INFRASTRUCTURE LAYER                               |
|   SQLite (Local / Testing)  •  MySQL 8.0 (Production)  •  Nginx  •  Docker    |
+-------------------------------------------------------------------------------+
```

### 🔄 Alur Proses Bisnis Utama (Business Workflow)

```
[ Registrasi Pelanggan ] ──► [ Pilih Paket & Wilayah PJ ] ──► [ Generate Invoice ]
                                                                       │
           ┌───────────────────────────────────────────────────────────┴────────────────────────┐
           ▼                                                                                    ▼
    [ Bayar Lunas ]                                                                    [ Cicilan / Kasbon ]
           │                                                                                    │
           ▼                                                                                    ▼
[ Kas Masuk Otomatis ] ◄─────────────────────────────────────────────────────────────── [ Sisa Piutang ]
           │
           ▼
[ Laporan Keuangan, Arus Kas & Dashboard Pajak PPN/BHP-USO ]
```

---

## 💎 Fitur Unggulan Sistem (Key Features)

### 1. 👥 Manajemen Pelanggan Berbasis Segmen
* Pengelompokan tipe pelanggan: **Individu**, **Instansi**, dan **Mitra**.
* Pemetaan paket internet (kecepatan & harga) serta penanggung jawab (PJ) teknis wilayah.
* Kontrol status koneksi (*Aktif / Nonaktif*) dengan penandaan tanggal pemutusan.

### 2. 🧾 Billing, Invoice & Manajemen Piutang (Kasbon)
* Pembuatan tagihan internet bulanan maupun penjualan perangkat (ONT, Router, Kabel).
* Dukungan sistem pembayaran penuh (*Lunas*) atau parsial (*Kasbon/Cicilan*).
* Ekspor dan cetak nota / kwitansi resmi dalam format **PDF**.

### 3. 💰 Manajemen Kas & Arus Kas Real-Time
* Buku kas terintegrasi: pencatatan kas masuk otomatis dari pembayaran invoice dan kas keluar dari pembelian stok barang.
* Fitur **Rekalkulasi Saldo Berjalan** otomatis untuk memastikan integritas buku kas.
* Manajemen biaya operasional (upstream ISP, sewa tiang, utilitas kantor).

### 4. 📦 Logistik, Inventaris Produk & Mutasi Cabang
* Inventaris perangkat jaringan dengan peringatan otomatis stok menipis (*Alert*).
* Fitur transfer stok barang dari kantor pusat ke cabang tertentu.
* Riwayat kartu stok (*Stock Card Log*) yang mencatat setiap barang masuk, pindah, atau dipakai.

### 5. 🏢 Multi-Cabang & Hak Akses Berjenjang (Role-Based Access Control)
* **Owner (Pemilik)**: Akses tanpa batas ke seluruh modul, dashboard performa, pengaturan pajak, manajemen user akun, dan backup database.
* **Pegawai (Kantor Pusat)**: Operasional kasir, penerbitan invoice, entri pelanggan baru, dan pengajuan kas.
* **Cabang**: Dashboard independen cabang, laporan stok cabang, dan pemakaian unit perangkat.

### 6. 🕒 SDM, Presensi Harian & Payroll (Slip Gaji)
* Absensi digital harian: pencatatan kehadiran (*Datang*, *Terlambat*, *Lembur*, *Pulang*).
* Generator Slip Gaji otomatis berdasarkan kehadiran dan komponen potongan/tunjangan.
* Cetak dokumen slip gaji resmi berformat **PDF**.

### 7. 🛡️ Keamanan & Kepatuhan Data (Enterprise Security)
* Proteksi brute-force login via **Rate Limiting**.
* Header keamanan respons HTTP (*Content-Type-Options*, *Frame-Options*, *XSS Protection*, *Referrer-Policy*).
* Audit Trail otomatis: pencatatan riwayat setiap aksi *create*, *update*, dan *delete* data pengguna.
* Pencadangan basis data (*Backup Database*) terjadwal dengan retensi otomatis.

---

## 🧪 Jaminan Kualitas & Pengujian (Pest PHP Test Suite)

Proyek ini menerapkan metodologi *Test-Driven Quality Assurance* menggunakan **Pest PHP v4** dengan **33 skenario pengujian komprehensif** dan **68 assertions** yang menguji seluruh endpoint dan *business logic*.

```text
   PASS  Tests\Unit\ExampleTest
   PASS  Tests\Feature\AuthTest
   PASS  Tests\Feature\DashboardTest
   PASS  Tests\Feature\ExampleTest
   PASS  Tests\Feature\InvoiceKasbonTest
   PASS  Tests\Feature\KasBiayaTest
   PASS  Tests\Feature\LaporanAbsensiGajiTest
   PASS  Tests\Feature\PelangganTest
   PASS  Tests\Feature\PengaturanCabangTest
   PASS  Tests\Feature\ProdukTest

  Tests:    33 passed (68 assertions)
  Duration: 1.22s
```

Untuk menjalankan unit & feature test:
```bash
./vendor/bin/pest
# atau via composer
composer test
```

Untuk memastikan standarisasi gaya penulisan kode (*Clean Code* / PSR-12):
```bash
./vendor/bin/pint --test
```

---

## 🔑 Kredensial Akun untuk Uji Coba (Demo Credentials)

Database lokal telah terisi data dummy lengkap (>1.000 pelanggan, >2.000 riwayat transaksi). Gunakan akun-akun berikut untuk menguji hak akses setiap peran:

| Peran (Role) | Username | Password | Deskripsi Akses |
|---|---|---|---|
| **Owner (Utama)** | `uroo` | `uroo1234` | Akses penuh seluruh sistem, pajak, laporan laba rugi, akun & backup |
| **Owner (Admin)** | `owner` | `password` | Akun owner sekunder dengan hak akses setara |
| **Pegawai (Pusat)** | `pegawai` | `password` | Operasional input pelanggan, invoice, kas, kasir, dan absensi |
| **Pegawai (Cabang)** | `cabang` | `password` | Dashboard cabang Bandung, cek stok cabang & pemakaian unit |

---

## 💻 Panduan Instalasi Lokal (Local Setup)

### Prasyarat:
- PHP >= 8.3 (`pdo_sqlite`, `pdo_mysql`, `mbstring`, `bcmath`, `gd`, `zip`, `xml`)
- Composer >= 2.0
- Node.js >= 18.x & npm

### Langkah Instalasi:

1. **Clone Repository:**
   ```bash
   git clone https://github.com/uroo-dev/netcore-isp-PT_Buana.git
   cd netcore-isp-PT_Buana
   ```

2. **Setup Konfigurasi Environment:**
   ```bash
   cp .env.example .env
   ```

3. **Install Dependensi Backend & Frontend:**
   ```bash
   composer install --ignore-platform-req=php
   npm install
   ```

4. **Generate Key & Storage Link:**
   ```bash
   php artisan key:generate
   php artisan storage:link
   ```

5. **Migrasi & Seeding Data:**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Kompilasi Aset Frontend:**
   ```bash
   npm run build
   ```

7. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```
   Buka peramban di: **http://127.0.0.1:8000**

---

## 🐳 Deployment via Docker Compose

Untuk menjalankan aplikasi dalam lingkungan kontainer terisolasi dengan **MySQL 8.0** dan **Nginx**:

1. Salin konfigurasi environment docker:
   ```bash
   cp .env.docker .env
   ```
2. Jalankan docker compose:
   ```bash
   docker compose up -d --build
   ```
3. Akses aplikasi di: **http://localhost:8080**

---

## 📂 Struktur Direktori Proyek

```
BuanaNetSystem/
├── app/
│   ├── Http/
│   │   ├── Controllers/     # Controller per modul (Pelanggan, Invoice, Kas, dll)
│   │   └── Middleware/      # CheckRole, SecurityHeaders, LogRequest
│   ├── Models/              # Eloquent Models & Relasi Database
│   └── Providers/           # AppServiceProvider & SQLite Function Resolvers
├── database/
│   ├── migrations/          # 44 skrip migrasi terstruktur
│   └── seeders/             # DatabaseSeeder & TestDataSeeder
├── docker/
│   └── nginx/               # Konfigurasi reverse proxy Nginx
├── resources/
│   ├── views/               # Blade Templates UI terpadu
│   └── css/                 # Styling Tailwind CSS
├── routes/
│   ├── web.php              # Definisi rute & pengelompokan hak akses (RBAC)
│   └── console.php          # Scheduled background tasks
├── tests/
│   ├── Feature/             # 33 Pest Feature Tests
│   └── Pest.php             # Pest configuration & testing helpers
├── docker-compose.yml       # Orkestrasi Docker (App, Nginx, MySQL)
├── Dockerfile               # Kontainer PHP 8.3 FPM
└── README.md                # Dokumentasi Resmi Proyek
```

---

<p align="center">
  Didesain dan dikembangkan dengan standar rekayasa perangkat lunak modern oleh <strong><a href="https://github.com/uroo-dev">Uroo.dev</a></strong>.
</p>
