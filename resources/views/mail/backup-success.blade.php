@component('mail::message')
# Backup Database Berhasil

Backup database **{{ config('app.name') }}** telah berhasil dibuat.

@component('mail::table')
| Detail | |
|--------|---|
| **File** | {{ $backup->filename }} |
| **Ukuran** | {{ $sizeFormatted }} |
| **Tanggal** | {{ $backup->backup_date ? \Carbon\Carbon::parse($backup->backup_date)->format('d M Y') : $backup->created_at->format('d M Y') }} |
| **Retensi** | {{ $backup->retention_days > 0 ? $backup->retention_days . ' hari' : 'Selamanya' }} |
@endcomponent

File backup terlampir dalam email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
