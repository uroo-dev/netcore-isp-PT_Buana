<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Absensi {{ $namaBulan }}</title>
    <style>
        @page {
            margin: 10mm 8mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 7px;
            color: #333;
            line-height: 1.3;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 2px solid #4b5563;
        }

        .header h1 {
            font-size: 13px;
            margin: 0;
            color: #4b5563;
        }

        .header p {
            font-size: 8px;
            color: #666;
            margin: 2px 0;
        }

        .info-print {
            font-size: 6px;
            color: #999;
            margin-bottom: 6px;
        }

        .employee-section {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }

        .employee-name {
            font-size: 9px;
            font-weight: bold;
            color: #4b5563;
            margin-bottom: 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #d1d5db;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        th {
            background: #4b5563;
            color: #fff;
            padding: 3px 2px;
            font-size: 6px;
            text-align: center;
            font-weight: 600;
        }

        td {
            padding: 2px;
            border: 1px solid #d1d5db;
            font-size: 6px;
        }

        tr:nth-child(even) td {
            background: #f9fafb;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .status-datang {
            color: #059669;
            font-weight: bold;
        }

        .status-terlambat {
            color: #d97706;
            font-weight: bold;
        }

        .status-absen {
            color: #dc2626;
            font-weight: bold;
        }

        .status-libur {
            color: #6b7280;
        }

        .summary-box {
            display: flex;
            width: 100%;
            gap: 6px;
        }

        .summary-item {
            flex: 1;
            text-align: center;
        }

        .summary-item .label {
            font-size: 6px;
            color: #666;
        }

        .summary-item .value {
            font-size: 10px;
            font-weight: bold;
            margin-top: 1px;
        }

        .summary-item.green {
            border-color: #059669;
        }

        .summary-item.green .value {
            color: #059669;
        }

        .summary-item.yellow {
            border-color: #d97706;
        }

        .summary-item.yellow .value {
            color: #d97706;
        }

        .summary-item.red {
            border-color: #dc2626;
        }

        .summary-item.red .value {
            color: #dc2626;
        }

        .summary-item.gray {
            border-color: #6b7280;
        }

        .summary-item.gray .value {
            color: #6b7280;
        }

        .footer {
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #e5e7eb;
            font-size: 6px;
            color: #999;
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    @php $pengaturan = $pengaturan ?? \App\Models\Pengaturan::first(); @endphp

    <div class="header">
        @if ($pengaturan && $pengaturan->logo)
            <img src="{{ public_path('storage/' . $pengaturan->logo) }}" style="height:35px;margin-bottom:5px;">
        @endif
        <h1>{{ $pengaturan->nama_perusahaan ?? ($pengaturan->nama ?? 'Perusahaan') }}</h1>
        <p>Rekap Absensi Karyawan - {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    <div class="info-print">
        Dicetak: {{ now()->format('d/m/Y H:i') }} |
        Periode: {{ $carbon->copy()->startOfMonth()->format('d/m/Y') }} -
        {{ $carbon->copy()->endOfMonth()->format('d/m/Y') }}
        @if ($karyawanDipilih)
            | Karyawan: {{ $karyawanDipilih->nama_lengkap }}
        @endif
    </div>

    @foreach ($rekap as $index => $r)
        <div class="employee-section{{ $index > 0 ? ' page-break' : '' }}">
            <div class="employee-name">
                {{ $index + 1 }}. {{ $r['karyawan']->nama_lengkap }}
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width:25px">No</th>
                        <th style="width:70px">Tanggal</th>
                        <th style="width:65px">Hari</th>
                        <th style="width:55px">Status</th>
                        <th style="width:55px">Jam Masuk</th>
                        <th style="width:55px">Jam Pulang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($r['detailHari'] as $hari)
                        <tr>
                            <td class="text-center">{{ \Carbon\Carbon::parse($hari['tanggal'])->format('d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($hari['tanggal'])->format('d/m/Y') }}</td>
                            <td>{{ $hari['hari'] }}</td>
                            <td class="text-center">
                                @if ($hari['status'])
                                    @php
                                        $statusLabels = [
                                            'datang' => 'Datang',
                                            'terlambat' => 'Terlambat',
                                            'absen' => 'Absen',
                                            'libur' => 'Libur',
                                        ];
                                        $statusClasses = [
                                            'datang' => 'status-datang',
                                            'terlambat' => 'status-terlambat',
                                            'absen' => 'status-absen',
                                            'libur' => 'status-libur',
                                        ];
                                    @endphp
                                    <span class="{{ $statusClasses[$hari['status']] ?? '' }}">
                                        {{ $statusLabels[$hari['status']] ?? ucfirst($hari['status']) }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ $hari['jam_masuk'] ?? '-' }}</td>
                            <td class="text-center">{{ $hari['jam_pulang'] ?? '-' }}</td>
                            <td>{{ $hari['keterangan'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="summary-table">
                <tr>
                    <td class="summary-item green">
                        <div class="label">Datang</div>
                        <div class="value">{{ $r['datang'] }}</div>
                    </td>

                    <td class="summary-item yellow">
                        <div class="label">Terlambat</div>
                        <div class="value">{{ $r['terlambat'] }}</div>
                    </td>

                    <td class="summary-item red">
                        <div class="label">Absen</div>
                        <div class="value">{{ $r['absen'] }}</div>
                    </td>

                    <td class="summary-item gray">
                        <div class="label">Libur</div>
                        <div class="value">{{ $r['libur'] }}</div>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i:s') }} |
        Sistem {{ $pengaturan->nama_perusahaan ?? ($pengaturan->nama ?? '') }}
    </div>
</body>

</html>
