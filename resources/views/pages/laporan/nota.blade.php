<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print Tagihan</title>
    <style>
        @page { margin: 5mm; }

        * { margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #000;
            padding: 10px;
        }

        .info-print {
            text-align: right;
            font-size: 8px;
            color: #999;
            margin-bottom: 4px;
        }

        .wrapper { width: 100%; }

        .main-table { width: 100%; border-collapse: collapse; }

        .left-col { width: 68%; vertical-align: top; }

        .divider-col {
            width: 2%;
            border-left: 2px dashed #000;
            position: relative;
        }

        .right-col { width: 30%; vertical-align: top; text-align: right; }

        .logo-wrapper {
            position: absolute;
            top: 40px;
            left: -75px;
            width: 170px;
            text-align: center;
        }

        .logo-table { width: 100%; border-collapse: collapse; }

        .logo-table td { vertical-align: middle; }

        .logo { width: 40px; }

        .company-name { font-size: 8px; font-weight: bold; text-align: left; }

        .info-table { width: 100%; border-collapse: collapse; }

        .info-table td { padding: 3px 0; }

        .label { width: 70px; }

        .green-text { color: #00a651; font-weight: bold; margin: 6px 0; }

        .total-table {
            width: 260px;
            border-collapse: collapse;
            border: 2px solid #000;
        }

        .total-table td { border: 1px solid #000; padding: 6px 4px; }

        .footer { margin: 0; padding: 0; font-size: 10px; font-style: italic; line-height: 1.5; }

        .payment-title { font-size: 15px; font-weight: bold; margin-bottom: 6px; }

        .payment-date { margin-bottom: 6px; }

        .customer-name { font-weight: bold; margin-bottom: 8px; }

        .customer-address { font-size: 10px; }

        .space { height: 40px; }

        .total-right { width: 180px; float: right; }

        .total-right table { width: 100%; border-collapse: collapse; border: 2px solid #000; }

        .total-right td { border: 1px solid #000; padding: 6px 4px; }

        .total-right td:last-child { text-align: right; }

        .clear { clear: both; }

        .date-box { margin-top: 10px; text-align: right; }

        .date-box span {
            display: inline-block;
            border: 1px solid #999;
            background: #f3f3f3;
            padding: 4px 10px;
            font-size: 22px;
            font-weight: bold;
            color: red;
        }

        .grand-total {
            margin-top: 6px;
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    @php
        $pengaturan = \App\Models\Pengaturan::first();
    @endphp

    @php $grandTotalTagihan = 0; @endphp

    @if ($invoices->count() > 0)
    <div class="info-print">
        Dicetak: {{ now()->format('d/m/y H:i') }} |
        {{ $tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->format('d/m/y') : 'Awal' }}
        - {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/y') : 'Sekarang' }}
        | {{ ucfirst($status) }} | {{ ucfirst($tipe) }}{{ isset($penanggungJawab) && $penanggungJawab ? ' | PJ: ' . $penanggungJawab : '' }}
    </div>
    @endif

    @foreach ($invoices as $i => $inv)
        @php $tanggal = \Carbon\Carbon::parse($inv->tanggal); @endphp

        <div class="wrapper">
            <table class="main-table">
                <tr>
                    <td class="left-col">
                        <table class="info-table">
                            <tr>
                                <td class="label">NAMA</td>
                                <td>: {{ strtoupper($inv->pelanggan->nama ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="label">ALAMAT</td>
                                <td>: {{ strtoupper($inv->pelanggan->alamat ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="label">BULAN</td>
                                <td>: {{ $tanggal->locale('id')->translatedFormat('F Y') }}</td>
                            </tr>
                        </table>

                        <div class="green-text">
                            @php
                                $tunggakanInv = isset($allTunggakan[$inv->pelanggan_id])
                                    ? $allTunggakan[$inv->pelanggan_id]->filter(fn($t) => $t->id < $inv->id)
                                    : collect([]);
                                $totalTunggakanInv = $tunggakanInv->sum('sisa');
                                $tagihanBulanIniInv = max(0, $inv->total - $totalTunggakanInv);
                                $keterangan = [];
                                foreach ($tunggakanInv as $item) {
                                    $bulan = \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('F');
                                    $keterangan[] = 'KEKURANGAN ' . strtoupper($bulan) . ' Rp ' . number_format($item->sisa, 0, ',', '.');
                                }
                            @endphp
                            {{ implode(' + ', $keterangan) }}
                        </div>

                        <table class="total-table">
                            <tr>
                                <td width="50%">TOTAL TAGIHAN</td>
                                <td width="10%">Rp</td>
                                <td align="right">{{ number_format($tagihanBulanIniInv, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                        @php $grandTotalTagihan += $tagihanBulanIniInv; @endphp

                        <div class="footer">
                            Konfirmasi Pembayaran atau Komplain WIFI <br>
                            WA : {{ $pengaturan->telepon ?? '-' }}
                        </div>
                    </td>

                    <td class="divider-col">
                        <div class="logo-wrapper">
                            <table class="logo-table">
                                <tr>
                                    <td width="45">
                                        @if ($pengaturan && $pengaturan->logo)
                                            <img src="{{ public_path('storage/' . $pengaturan->logo) }}" class="logo">
                                        @endif
                                    </td>
                                    <td class="company-name">
                                        {{ strtoupper($pengaturan->nama_perusahaan ?? 'PT BUANA DATA MEDIA') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>

                    <td class="right-col">
                        <div class="payment-title">PAYMENT</div>
                        <div class="payment-date">{{ $tanggal->format('d/m/y') }}</div>
                        <div class="customer-name">{{ strtoupper($inv->pelanggan->nama ?? '-') }}</div>
                        <div class="customer-address">{{ strtoupper($inv->pelanggan->alamat ?? '-') }}</div>

                        <div class="green-text">
                            @php
                                $keterangan2 = [];
                                foreach ($tunggakanInv as $item) {
                                    $bulan = \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('F');
                                    $keterangan2[] = 'KEKURANGAN ' . strtoupper($bulan) . ' Rp ' . number_format($item->sisa, 0, ',', '.');
                                }
                            @endphp
                            {{ implode(' + ', $keterangan2) }}
                        </div>

                        <div class="total-right">
                            <table>
                                <tr>
                                    <td width="30">Rp</td>
                                    <td>{{ number_format($tagihanBulanIniInv, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="clear"></div>
                    </td>
                </tr>
            </table>
        </div>

        @if (!$loop->last)
            <div style="text-align:center;font-size:7px;color:#999;margin:6px 0 8px 0;letter-spacing:2px;">
                - - - - - - - - - - - - - - - - - Potong disini - - - - - - - - - - - - - - - - -
            </div>
        @endif
    @endforeach

    @if ($invoices->count() > 1)
        <div class="grand-total">
            GRAND TOTAL ({{ $invoices->count() }} tagihan): Rp{{ number_format($grandTotalTagihan, 0, ',', '.') }}
        </div>
    @endif
</body>
</html>
