<div align="center">

# 🌐 netcore-isp-PT_Buana
### Enterprise-Grade ISP Management, Automated Billing & Financial Ecosystem

*Solusi Perangkat Lunak All-in-One untuk Manajemen Penyedia Jasa Internet (ISP), RT/RW Net, Penagihan Otomatis, Multi-Cabang, dan Penggajian Karyawan.*

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Testing](https://img.shields.io/badge/Pest_PHP-v4.6-F3B000?style=for-the-badge&logo=pest&logoColor=black)](https://pestphp.com)
[![Docker Ready](https://img.shields.io/badge/Docker-Ready-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![Code Style](https://img.shields.io/badge/Code_Style-Laravel_Pint-00D084?style=for-the-badge)](https://laravel.com/docs/pint)
[![License](https://img.shields.io/badge/License-MIT-3B82F6?style=for-the-badge)](LICENSE)

[Fitur Utama](#-fitur-utama) • [Rancangan Arsitektur](#-rancangan-arsitektur-sistem) • [Model Relasi Data](#-model-relasi-data-erd) • [Pengujian Pest](#-jaminan-kualitas--pengujian-pest-php) • [Panduan Instalasi](#-panduan-instalasi--menjalankan-aplikasi) • [Akun Demo](#-kredensial-akun-uji-coba)

---

</div>

## 📌 Mengapa PT Buana NetCore System?

Pengelolaan operasional ISP dan jaringan RT/RW Net sering menghadapi tantangan krusial:
* **Kebocoran Piutang**: Sulit memantau pelanggan yang membayar separuh (kasbon/cicilan) vs lunas.
* **Kompleksitas Multi-Cabang**: Data antar cabang tercampur, stok perangkat teknisi (ONT/Router) rawan hilang saat mutasi.
* **Kepatuhan Pajak**: Perhitungan regulasi kominfo (Pajak PPN 11% & BHP-USO) kerap dilakukan manual dan rentan salah hitung.
* **Beban Administrasi**: Presensi kehadiran teknisi dan kalkulasi slip gaji memakan waktu berhari-hari.

> [!NOTE]
> **NetCore PT Buana** memadukan seluruh alur kerja ini ke dalam satu platform tersentralisasi: dari *lifecycle* pelanggan, siklus penagihan otomatis, pencatatan kas mutasi real-time, kontrol stok antar gudang cabang, hingga absensi & penggajian karyawan.

---

## 🏛️ Rancangan Arsitektur Sistem

Sistem dirancang mengadopsi standar **Clean Layered Architecture (MVC)** di atas ekosistem Laravel 13, memastikan performa tinggi, modularitas, dan kepatuhan terhadap prinsip *Separation of Concerns*.

### 1. Arsitektur Berlapis (Layered High-Level Architecture)

```
┌────────────────────────────────────────────────────────────────────────┐
│                        1. PRESENTATION LAYER                           │
│  • Responsive UI (Blade + Tailwind CSS v4 + Flowbite)                  │
│  • Dynamic Component Modals & Reactive Filter Tables                   │
│  • PDF Export Generator (DomPDF Engine)                                │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ HTTP Requests
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                     2. SECURITY & MIDDLEWARE LAYER                     │
│  • SecurityHeaders (Strict-Transport-Security, X-Frame-Options, CSP)   │
│  • Role-Based Access Control (RBAC via CheckRole: Owner, Pegawai, Cabang)│
│  • Login Anti-Brute Force Rate Limiting (ThrottleRequests 10 req/min)  │
│  • Request Audit Logger (LogRequest → LogAktivitas)                    │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Sanitized Request
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                       3. CONTROLLER & BUSINESS LOGIC                   │
│  • Customer & Billing Engine (Pelanggan, Invoice, Kasbon, Tagihan)     │
│  • Finance & Cashflow Handler (Kas, Saldo Berjalan, Biaya Operasional) │
│  • Inventory & Branch Controller (Stok Gudang, Transfer Cabang)        │
│  • HR & Payroll Dispatcher (Absensi, Slip Gaji Kalkulator)             │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ ORM Interactions
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                         4. DOMAIN & DATA LAYER                         │
│  • Eloquent ORM Models & Relationships                                 │
│  • Database Migration Engine (44 Migrations, Fully Dual-Compatible)   │
│  • SQLite & MySQL Abstraction (Driver-level function mapping)          │
└───────────────────────────────────┬────────────────────────────────────┘
                                    │ Query Execution
                                    ▼
┌────────────────────────────────────────────────────────────────────────┐
│                      5. INFRASTRUCTURE & PERSISTENCE                   │
│  • Development & CI/CD: In-Memory SQLite (Lightning Fast)              │
│  • Production: Dockerized MySQL 8.0 Engine + Nginx Alpine Proxy       │
└────────────────────────────────────────────────────────────────────────┘
```

---

### 2. Alur Transaksi & Billing (Transaction Lifecycle)

```
   ┌────────────────────┐
   │ REGISTRASI PELANGGAN │ ── Tipe: Individu / Instansi / Mitra
   └─────────┬──────────┘
             │
             ▼
   ┌────────────────────┐
   │ PENETAPAN PAKET & PJ │ ── Paket Kecepatan (Mbps) + Penanggung Jawab Wilayah
   └─────────┬──────────┘
             │
             ▼
   ┌────────────────────┐
   │ GENERATE INVOICE   │ ── Tagihan Bulanan / Pembelian Perangkat
   └─────────┬──────────┘
             │
     ┌───────┴────────┬──────────────────────────┐
     │ Status         │ Status                   │
     ▼                ▼                          ▼
┌─────────┐      ┌─────────┐                ┌─────────┐
│  LUNAS  │      │ CICILAN │                │ KASBON  │
└────┬────┘      └────┬────┘                └────┬────┘
     │                │ Sisa Tercatat            │ Piutang Terbuka
     └────────┬───────┴──────────────────────────┘
              ▼
   ┌────────────────────┐
   │ BUKU KAS OTOMATIS  │ ── Uang Masuk Bertambah, Saldo Berjalan Direkalkulasi
   └─────────┬──────────┘
             │
             ▼
   ┌────────────────────┐
   │ LAPORAN & PAJAK    │ ── Cetak Nota PDF & Laporan PPN 11% / BHP-USO
   └────────────────────┘
```

---

## ✨ Fitur Utama

| Modul | Kemampuan & Manfaat Bisnis |
|---|---|
| **👥 Manajemen Pelanggan** | Profiling lengkap pelanggan (Individu, Instansi, Mitra), alokasi IP Address, koordinat Google Maps/GPS, integrasi Paket WiFi & PJ, tombol cepat switch status On/Off. |
| **🧾 Billing & Invoice** | Invoice otomatis & ad-hoc, multi-item perangkat (modem, kabel, konektor), deteksi status pembayaran (Lunas, Cicilan, Kasbon), download & cetak PDF invoice resmi. |
| **💰 Kas & Keuangan Terintegrasi** | Pencatatan arus kas otomatis saat invoice terbayar atau pembelian stok, fitur **Rekalkulasi Saldo Berjalan**, alokasi biaya operasional kantor & bandwidth upstream. |
| **🏢 Multi-Cabang & Inventaris** | Isolasi hak akses data per cabang. Modul inventaris gudang pusat dengan mutasi barang ke cabang dan kartu stok (*LogStok*) otomatis. |
| **👥 HR, Absensi & Slip Gaji** | Absensi harian karyawan (status Datang, Terlambat, Lembur, Pulang). Pembuatan slip gaji otomatis dengan perhitungan gaji pokok, tunjangan, dan cetak PDF. |
| **📡 Repeater & Pajak Regulasi** | Manajemen titik sewa repeater pemancar WiFi, kalkulasi pajak pendapatan jasa internet: PPN (11%) dan tarif kontribusi BHP-USO Kominfo. |
| **🛡️ Keamanan & Audit Trail** | Pencatatan otomatis setiap request dan manipulasi data (*LogAktivitas*), proteksi rate limiter pada login, CSRF tokens, dan sanitasi header respons. |
| **💾 Backup & Recovery** | Modul pencadangan database mandiri melalui UI web atau CLI artisan, notifikasi status via email, serta manajemen retensi cadangan. |

---

## 🗄️ Model Relasi Data (ERD Summary)

```
[ Cabang ] ────────────1:N────────────► [ User ] (Owner, Pegawai, Cabang)
   │
   └───────────────────1:N────────────► [ ProdukCabang ] ◄──N:1── [ Produk ]
                                                                      │
                                                                     1:N
                                                                      ▼
[ PaketWifi ] ─────────1:N─────────┐                             [ LogStok ]
                                   ▼
[ PenanggungJawabWifi ] 1:N───► [ Pelanggan ]
                                   │
                                  1:N
                                   ▼
                               [ Invoice ] ──1:N──► [ InvoiceDetail ]
                                   │
                                  1:N
                                   ▼
[ Kas (Saldo Berjalan) ] ◄── [ Pembayaran ]
```

---

## 🧪 Jaminan Kualitas & Pengujian (Pest PHP)

Proyek ini telah diverifikasi penuh menggunakan framework pengujian modern **Pest PHP v4.6** dengan **33 automated tests** dan **68 assertions**. Seluruh pengujian berjalan pada database *in-memory* yang terisolasi, memastikan zero side-effects.

> [!TIP]
> **Status Pengujian:** `33 PASSED (100%)` | Durasi: `~1.2 detik`

```bash
# Menjalankan seluruh test suite Pest
./vendor/bin/pest

# Atau menggunakan shortcut Composer
composer test
```

### Cakupan Test Suite:
- **`AuthTest`**: Validasi login, pengujian brute-force, redirect guest, dan invalidasi sesi saat logout.
- **`DashboardTest`**: Otorisasi dashboard berbasis peran (Owner vs Pegawai) dan endpoint agregasi pajak.
- **`PelangganTest`**: Validasi CRUD pelanggan, pemisahan tab segmen, dan toggle status aktif/nonaktif.
- **`ProdukTest`**: Inventaris produk, endpoint pagination AJAX JSON, dan validasi mutasi restok.
- **`InvoiceKasbonTest`**: Siklus pembuatan invoice, validasi kalkulasi subtotal, dan tracking kasbon.
- **`KasBiayaTest`**: Integritas mutasi kas, rekalkulasi saldo akhir matematis, dan biaya operasional.
- **`LaporanAbsensiGajiTest`**: Penarikan laporan analitik, form absensi teknisi, dan generator slip gaji.
- **`PengaturanCabangTest`**: Akses konfigurasi perusahaan, master data paket, PJ, dan akun pengguna.

---

## 🎨 Standarisasi Clean Code (Laravel Pint)

Kode ditulis mematuhi konvensi resmi **PSR-12** dan standar *idiomatic Laravel* menggunakan tool **Laravel Pint**.

```bash
# Memeriksa kepatuhan format kode
./vendor/bin/pint --test
# Status: {"tool":"pint","result":"passed"}
```

---

## 🔐 Kredensial Akun Uji Coba

Database demo telah terisi data uji coba (*1.000+ pelanggan dummy, 2.000+ riwayat transaksi, produk, dan log absensi*). Gunakan akun di bawah ini untuk menguji setiap level hak akses:

| Role | Username | Password | Deskripsi Hak Akses & Pengujian |
|---|---|---|---|
| 👑 **Owner (Utama)** | `uroo` | `uroo1234` | **Super Admin**: Akses mutlak ke semua cabang, dashboard pajak, audit log, backup, dan manajemen akun. |
| 🛡️ **Owner (Admin)** | `owner` | `password` | **Owner Sekunder**: Hak akses penuh setara untuk pengujian redundansi. |
| 💼 **Pegawai (Pusat)** | `pegawai` | `password` | **Staff Operasional**: Entri pelanggan baru, pembuatan tagihan invoice, kasir kas, dan absensi harian. |
| 🏢 **Pegawai (Cabang)** | `cabang` | `password` | **Staff Cabang Bandung**: Tampilan terisolasi dashboard cabang, input pemakaian stok unit cabang. |

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### Opsi A: Menjalankan di Lingkungan Lokal (Recommended)

1. **Clone Repository:**
   ```bash
   git clone git@github.com:uroo-dev/netcore-isp-PT_Buana.git
   cd netcore-isp-PT_Buana
   ```

2. **Salin Environment File:**
   ```bash
   cp .env.example .env
   ```

3. **Install Dependensi:**
   ```bash
   composer install --ignore-platform-req=php
   npm install
   ```

4. **Inisialisasi Aplikasi & Database:**
   ```bash
   php artisan key:generate
   php artisan storage:link
   php artisan migrate:fresh --seed
   ```

5. **Kompilasi Frontend & Jalankan Server:**
   ```bash
   npm run build
   php artisan serve
   ```
   Akses aplikasi di: **http://127.0.0.1:8000**

---

### Opsi B: Menjalankan via Docker Compose

> [!IMPORTANT]
> Konfigurasi Docker telah mencakup kontainer **PHP 8.3 FPM**, server web **Nginx Alpine**, dan basis data **MySQL 8.0**.

```bash
# 1. Siapkan file environment docker
cp .env.docker .env

# 2. Build dan jalankan seluruh container di background
docker compose up -d --build

# 3. Jalankan migrasi dan data seeder di dalam container
docker compose exec app php artisan migrate:fresh --seed
```
Buka browser dan akses melalui: **http://localhost:8080**

---

## 🛠️ Perintah Artisan Tambahan (Custom Commands)

Sistem dilengkapi beberapa artisan command bawaan untuk otomasi latar belakang:

```bash
# Notifikasi jatuh tempo invoice via WhatsApp (H-7 dan H-3)
php artisan invoice:notify-due

# Menjalankan backup database dan mencatat histori backup
php artisan backup:database

# Rekalkulasi dan perbaikan data kas jika ada anomali saldo
php artisan kas:perbaiki

# Generate data uji tambahan untuk pengujian pagination & filter kas
php artisan kas:seed-test
```

---

## 📁 Struktur Direktori Utama

```
netcore-isp-PT_Buana/
├── app/
│   ├── Console/Commands/       # Custom artisan tools (Backup, Notifikasi WA, Kas)
│   ├── Http/
│   │   ├── Controllers/        # Controllers logika bisnis per modul
│   │   └── Middleware/         # CheckRole, SecurityHeaders, LogRequest
│   ├── Models/                 # Eloquent entities & relationships
│   └── Providers/              # AppServiceProvider (SQLite driver abstractions)
├── database/
│   ├── migrations/             # 44 file migrasi skema tabel
│   └── seeders/                # DatabaseSeeder & TestDataSeeder
├── docker/
│   └── nginx/                  # Konfigurasi reverse proxy Nginx
├── resources/
│   ├── views/                  # Blade templates (Dashboard, Kas, Pelanggan, dll)
│   └── css/                    # Tailwind CSS v4 styling
├── routes/
│   ├── web.php                 # Rute web & proteksi RBAC
│   └── console.php             # Scheduled task cron definitions
├── tests/
│   ├── Feature/                # Skenario pengujian Pest PHP per fitur
│   └── Pest.php                # Testing helpers & test case binding
├── docker-compose.yml          # Konfigurasi multi-container Docker
├── Dockerfile                  # Base image PHP 8.3 FPM Alpine
└── README.md                   # Dokumentasi resmi proyek
```

---

<div align="center">

Dibuat dengan dedikasi tinggi terhadap kualitas kode oleh **[Uroo.dev](https://github.com/uroo-dev)**.  
Lisensi open-source di bawah **[MIT License](LICENSE)**.

</div>
