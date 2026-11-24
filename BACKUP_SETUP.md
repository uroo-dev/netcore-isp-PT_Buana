# Panduan Backup Database

## Persyaratan
- PHP 8.2+
- MySQL/MariaDB (mysqldump harus terinstall)
- Ekstensi PHP: `zip`, `pdo_mysql`

## Instalasi

### 1. Install Package
```bash
composer install --no-interaction
```
Package `spatie/laravel-backup` v10.3.0 sudah ada di `composer.lock`.

### 2. Jalankan Migration
```bash
php artisan migrate
```
Membuat tabel `backups` untuk menyimpan riwayat backup.

### 3. Konfigurasi .env
Tambahkan di `.env`:
```
MAIL_TO=email-anda@example.com
```
Email ini akan menerima notifikasi hasil backup (sukses/gagal).

### 4. Buat Storage Directory
```bash
mkdir -p storage/app/backups
```

## Penggunaan

### Backup Manual
- Buka menu **Pengaturan > Backup Database**
- Klik tombol **Backup Sekarang**
- File backup akan tersimpan di `storage/app/backups/`

Atau via terminal:
```bash
php artisan backup:run --only-db
```

### Melihat Daftar Backup
- Buka menu **Pengaturan > Backup Database**
- Lihat daftar backup, ukuran, dan status

### Download Backup
- Klik tombol **Download** pada baris backup yang diinginkan

### Menghapus Backup
- Klik tombol **Hapus** pada baris backup yang diinginkan

## Backup Otomatis

Backup database dijadwalkan otomatis setiap hari pukul **02:00 WIB**.

### Konfigurasi Cron (Server Production)
Tambahkan cron job berikut di server:
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```
Cron ini akan mengecek jadwal setiap menit dan menjalankan backup pada pukul 02:00.

## Hasil Backup

File backup:
- Format: `.zip` berisi dump SQL
- Lokasi: `storage/app/backups/`
- Retensi default: 30 hari (bisa diatur per-backup)
- Backup hanya menyimpan database (file tidak diikutkan)

## Notifikasi Email

Notifikasi dikirim ke `MAIL_TO` untuk event:
- Backup berhasil
- Backup gagal
- Pembersihan backup berhasil/gagal
- Backup dalam kondisi sehat/tidak sehat

## Troubleshooting

### Backup gagal dengan error "mysqldump not found"
Pastikan MySQL/MariaDB client tools terinstall dan path `mysqldump` tersedia di sistem.

### Storage disk penuh
Backup akan berhenti otomatis jika melebihi 5 GB. Hapus backup lama melalui menu atau gunakan:
```bash
php artisan backup:clean
```

### Cron tidak berjalan
Pastikan cron job sudah terdaftar:
```bash
crontab -e
# tambahkan:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Command Reference
```bash
php artisan backup:run --only-db    # Backup database saja
php artisan backup:list             # Lihat daftar backup
php artisan backup:clean            # Hapus backup lama
php artisan backup:monitor          # Cek kesehatan backup
```
