<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aktivitas - {{ $cabang->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #374151;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4b5563;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .header h1 { font-size: 20px; color: #4b5563; margin-bottom: 4px; }
        .header h2 { font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 2px; }
        .header p { font-size: 11px; color: #6b7280; }
        .info {
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        .info table { width: 100%; }
        .info td { padding: 2px 8px; font-size: 12px; }
        .info td:first-child { font-weight: 600; color: #4b5563; width: 130px; }
        .filter-bar {
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 6px;
        }
        .filter-bar form { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .filter-bar label { font-size: 12px; font-weight: 600; color: #4338ca; }
        .filter-bar input {
            padding: 6px 10px;
            border: 1px solid #c7d2fe;
            border-radius: 4px;
            font-size: 12px;
        }
        .filter-bar button {
            padding: 6px 16px;
            background: #4b5563;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }
        .section-title {
            background: #4b5563;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 12px;
            border-radius: 4px 4px 0 0;
            display: flex;
            justify-content: space-between;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th {
            background: #e5e7eb;
            color: #374151;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px;
            text-align: left;
        }
        table.data th:nth-child(3),
        table.data th:nth-child(4) { text-align: center; }
        table.data td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        table.data td:nth-child(3),
        table.data td:nth-child(4) { text-align: center; }
        table.data tr:nth-child(even) { background: #f9fafb; }
        .total {
            font-weight: 600;
            background: #f3f4f6 !important;
        }
        .empty {
            text-align: center;
            padding: 20px;
            color: #9ca3af;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
        }
        .print-btn {
            display: inline-block;
            margin-bottom: 16px;
            padding: 8px 20px;
            background: #4b5563;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
        }
        .print-btn:hover { background: #374151; }
        @media print {
            .print-btn, .filter-bar { display: none; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Cetak Laporan</button>

    <div class="filter-bar">
        <form method="GET" action="{{ route('cabang.cetak-laporan', $cabang->id) }}">
            <label>Dari</label>
            <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}">
            <label>Sampai</label>
            <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}">
            <button type="submit">Tampilkan</button>
        </form>
    </div>

    <div class="header">
        <h1>BUANANET PRO</h1>
        <h2>Laporan Aktivitas Cabang</h2>
        <p>Dicetak pada {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }} WIB</p>
    </div>

    <div class="info">
        <table>
            <tr><td>Cabang</td><td>: {{ $cabang->nama }}</td></tr>
            <tr><td>Alamat</td><td>: {{ $cabang->alamat ?: '-' }}</td></tr>
            <tr><td>Telepon</td><td>: {{ $cabang->telepon ?: '-' }}</td></tr>
            <tr>
                <td>Periode</td>
                <td>: {{ $tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') : 'Awal' }} s/d {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') : 'Sekarang' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">
        <span>1. Pemakaian Stok (Kas Keluar)</span>
        <span>Total: Rp {{ number_format($totalPemakaian, 0, ',', '.') }}</span>
    </div>
    <table class="data">
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th>Tanggal</th>
                <th style="width:120px">Jumlah</th>
                <th style="width:110px">User</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemakaian as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ $item->user->nama_lengkap ?? '-' }}</td>
                <td>{{ $item->keterangan ?: '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty">Tidak ada data pemakaian stok pada periode ini</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">
        <span>2. Pengeluaran Lainnya</span>
        <span>Total: Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</span>
    </div>
    <table class="data">
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th>Tanggal</th>
                <th style="width:120px">Jumlah</th>
                <th style="width:110px">User</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengeluaran as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ $item->user->nama_lengkap ?? '-' }}</td>
                <td>{{ $item->keterangan ?: '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty">Tidak ada pengeluaran lain pada periode ini</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">
        <span>3. Pemindahan Stok Masuk</span>
        <span>Total: {{ number_format($totalPemindahan, 0, ',', '.') }} pcs</span>
    </div>
    <table class="data">
        <thead>
            <tr>
                <th style="width:40px">No</th>
                <th>Tanggal</th>
                <th style="width:120px">Jumlah</th>
                <th style="width:110px">User</th>
                <th>Produk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemindahan as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                <td>{{ $item->jumlah }} pcs</td>
                <td>{{ $item->user->nama_lengkap ?? '-' }}</td>
                <td>{{ $item->produk->nama ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="empty">Tidak ada pemindahan stok pada periode ini</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Laporan ini digenerate otomatis oleh sistem BuanaNet Pro
    </div>

    @if(request()->has('print'))
    <script>window.onload = function() { window.print(); }</script>
    @endif
</body>
</html>