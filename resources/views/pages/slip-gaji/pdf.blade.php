<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $slipGaji->nama }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; padding: 20px; }
        h1 { font-size: 18px; margin-bottom: 5px; color: #1a56db; }
        h2 { font-size: 14px; margin-top: 0; color: #666; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #f3f4f6; text-align: left; padding: 8px 10px; font-size: 11px; text-transform: uppercase; color: #666; }
        td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row td { border-top: 2px solid #333; font-weight: bold; font-size: 13px; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: center; }
        .ringkasan { margin-top: 10px; }
        .ringkasan table { width: auto; margin-top: 5px; }
        .ringkasan td { border: none; padding: 3px 10px 3px 0; }
    </style>
</head>
<body>
    <h1>{{ $slipGaji->nama }}</h1>
    <h2>Daftar Penggajian Karyawan</h2>

    <div class="ringkasan">
        <table>
            <tr><td>Total Karyawan</td><td>: {{ $slipGaji->details->count() }} orang</td></tr>
            <tr><td>Total Gaji</td><td>: Rp {{ number_format($slipGaji->total_gaji, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Telat</th>
                <th class="text-center">Absen</th>
                <th class="text-right">Gaji Pokok</th>
                <th class="text-right">Total Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($slipGaji->details as $detail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->karyawan->nama_lengkap }}</td>
                <td class="text-center">{{ $detail->total_hadir }}</td>
                <td class="text-center">{{ $detail->total_terlambat }}</td>
                <td class="text-center">{{ $detail->total_absen }}</td>
                <td class="text-right">Rp {{ number_format($detail->gaji_pokok, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->total_gaji, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-right">Total</td>
                <td class="text-right">Rp {{ number_format($slipGaji->total_gaji, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} &middot; {{ config('app.name') }}
    </div>
</body>
</html>
