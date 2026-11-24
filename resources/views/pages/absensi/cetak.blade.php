<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi</title>
    <style>
        @page { margin: 20mm 15mm; size: A4 landscape; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 11px; color: #374151; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 3px double #4b5563; }
        .header h1 { font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
        .header p { font-size: 12px; color: #4b5563; }
        .info { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 11px; }
        .info span { color: #4b5563; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table thead th { background-color: #4b5563; color: #ffffff; font-weight: 600; padding: 8px 10px; font-size: 10px; text-align: left; text-transform: uppercase; letter-spacing: 0.5px; }
        table tbody td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; color: #374151; }
        table tbody tr:nth-child(even) { background-color: #f9fafb; }
        .status-datang { color: #059669; font-weight: 600; }
        .status-terlambat { color: #d97706; font-weight: 600; }
        .status-absen { color: #dc2626; font-weight: 600; }
        .status-libur { color: #6b7280; font-weight: 600; }
        .footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; font-size: 10px; color: #6b7280; }
        @media print { body { -webkit-print-color-adjust: exact; print-color-adjust: exact; } table thead th { background-color: #4b5563 !important; color: #ffffff !important; } table tbody tr:nth-child(even) { background-color: #f9fafb !important; } }
    </style>
</head>
<body>
    @php $pengaturan = \App\Models\Pengaturan::first(); @endphp
    <div class="header">
        <h1>{{ $pengaturan->nama_perusahaan ?? 'LAPORAN ABSENSI KARYAWAN' }}</h1>
        <p>{{ \Carbon\Carbon::parse($dari)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d F Y') }}</p>
    </div>
    <div class="info">
        <span>Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</span>
        <span>Total Data: {{ $data->count() }} record</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Tanggal</th>
                <th>Nama Karyawan</th>
                <th>Status</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d/m/Y') }}</td>
                    <td>{{ $item->karyawan?->nama_lengkap ?? '-' }}</td>
                    <td>
                        @php
                            $statusMap = ['datang' => 'Datang', 'terlambat' => 'Terlambat', 'absen' => 'Absen', 'libur' => 'Libur'];
                            $statusClass = $item->status === 'datang' ? 'status-datang' : ($item->status === 'terlambat' ? 'status-terlambat' : ($item->status === 'absen' ? 'status-absen' : 'status-libur'));
                        @endphp
                        <span class="{{ $statusClass }}">{{ $statusMap[$item->status] ?? ucfirst($item->status) }}</span>
                    </td>
                    <td>{{ $item->jam_masuk ?? '-' }}</td>
                    <td>{{ $item->jam_pulang ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #9ca3af;">Tidak ada data absensi pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        <span>Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}</span>
        <span>&copy; {{ date('Y') }} BuanaNet System</span>
    </div>
    <script>window.print();</script>
</body>
</html>
