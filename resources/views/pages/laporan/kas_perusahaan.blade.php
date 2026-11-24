<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kas Perusahaan</title>
    <style>
        @page { margin: 10mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #4b5563; }
        .header h1 { font-size: 16px; margin: 0; color: #4b5563; }
        .header p { font-size: 8px; color: #666; margin: 2px 0; }
        .info-print { font-size: 7px; color: #999; margin-bottom: 8px; }
        .summary { display: flex; justify-content: space-between; margin-bottom: 12px; gap: 8px; }
        .summary-item { flex: 1; padding: 8px 10px; border-radius: 4px; border: 1px solid #e5e7eb; text-align: center; }
        .summary-item .label { font-size: 7px; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary-item .value { font-size: 13px; font-weight: bold; margin-top: 3px; }
        .summary-item.masuk { border-color: #059669; }
        .summary-item.masuk .value { color: #059669; }
        .summary-item.keluar { border-color: #dc2626; }
        .summary-item.keluar .value { color: #dc2626; }
        .summary-item.saldo { border-color: #4b5563; }
        .summary-item.saldo .value { color: #4b5563; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4b5563; color: #fff; padding: 6px 4px; font-size: 8px; text-align: left; }
        td { padding: 4px; border-bottom: 1px solid #e5e7eb; font-size: 8px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }
        .fw-bold { font-weight: bold; }
        .footer { margin-top: 15px; padding-top: 8px; border-top: 1px solid #e5e7eb; font-size: 7px; color: #999; text-align: center; }
    </style>
</head>
<body>
    @php $pengaturan = $pengaturan ?? \App\Models\Pengaturan::first(); @endphp

    <div class="header">
        @if ($pengaturan && $pengaturan->logo)
            <img src="{{ public_path('storage/' . $pengaturan->logo) }}" style="height:35px;margin-bottom:5px;">
        @endif
        <h1>{{ $pengaturan->nama_perusahaan ?? $pengaturan->nama ?? 'Perusahaan' }}</h1>
        <p>Laporan Kas Perusahaan & Keuangan</p>
    </div>

    <div class="info-print">
        Dicetak: {{ now()->format('d/m/y H:i') }} |
        Periode: {{ $tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') : 'Awal' }}
        - {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') : 'Sekarang' }}
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th style="width:65px">Tanggal</th>
                <th style="width:55px">Tipe</th>
                <th>Keterangan</th>
                <th style="width:65px" class="text-right">Jumlah</th>
                <th style="width:70px" class="text-right">Saldo Akhir</th>
                <th style="width:50px">User</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kasEntries as $i => $entry)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($entry->tanggal)->format('d/m/Y') }}</td>
                    <td>
                        @if ($entry->tipe === 'masuk')
                            <span class="text-green">Pemasukan</span>
                        @else
                            <span class="text-red">Pengeluaran</span>
                        @endif
                    </td>
                    <td>{{ $entry->keterangan ?? '-' }}</td>
                    <td class="text-right {{ $entry->tipe === 'masuk' ? 'text-green' : 'text-red' }}">
                        Rp {{ number_format($entry->jumlah, 0, ',', '.') }}
                    </td>
                    <td class="text-right fw-bold">Rp {{ number_format($entry->saldo_akhir, 0, ',', '.') }}</td>
                    <td>{{ $entry->user?->nama_lengkap ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:20px;color:#999;">
                        Tidak ada data kas
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i:s') }} | Sistem {{ $pengaturan->nama_perusahaan ?? $pengaturan->nama ?? '' }}
    </div>
</body>
</html>
