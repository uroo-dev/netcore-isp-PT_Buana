<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Produk</title>
    <style>
        @page { margin: 10mm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #4b5563; }
        .header h1 { font-size: 16px; margin: 0; color: #4b5563; }
        .header p { font-size: 8px; color: #666; margin: 2px 0; }
        .info-print { font-size: 7px; color: #999; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4b5563; color: #fff; padding: 6px 4px; font-size: 8px; text-align: left; }
        td { padding: 4px; border-bottom: 1px solid #e5e7eb; font-size: 8px; }
        tr:nth-child(even) td { background: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }
        .text-yellow { color: #d97706; }
        .text-blue { color: #4b5563; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 3px; font-size: 7px; font-weight: bold; }
        .badge-masuk { background: #d1fae5; color: #065f46; }
        .badge-keluar { background: #fee2e2; color: #991b1b; }
        .badge-pindah { background: #f3f4f6; color: #374151; }
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
        <p>Laporan Mutasi Stok Produk</p>
    </div>

    <div class="info-print">
        Dicetak: {{ now()->format('d/m/y H:i') }} |
        Periode: {{ $tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') : 'Awal' }}
        - {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') : 'Sekarang' }}
        @if ($tipe) | {{ ucfirst($tipe) }} @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:25px">No</th>
                <th style="width:65px">Tanggal</th>
                <th>Produk</th>
                <th style="width:50px" class="text-right">Jumlah</th>
                <th style="width:55px">Aksi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logStok as $i => $log)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $log->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $log->produk?->nama ?? 'Produk dihapus' }}</td>
                    <td class="text-right">{{ number_format($log->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if ($log->aksi === 'masuk')
                            <span class="badge badge-masuk">Masuk</span>
                        @elseif ($log->aksi === 'keluar')
                            <span class="badge badge-keluar">Keluar</span>
                        @else
                            <span class="badge badge-pindah">Pindah</span>
                        @endif
                    </td>
                    <td>{{ $log->keterangan }}{{ $log->cabang ? ' (' . $log->cabang->nama . ')' : '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding:20px;color:#999;">
                        Tidak ada data mutasi stok
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
