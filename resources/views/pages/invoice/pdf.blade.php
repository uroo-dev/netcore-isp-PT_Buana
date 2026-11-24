    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">

        <style>
            @page {
                margin: 5mm;
            }

            * {
                margin: 5;
                padding: 0;
            }

            .wrapper {
                page-break-inside: avoid;
            }

            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                line-height: 1.6;
                color: #000;
                padding: 0;
            }

            .wrapper {
                width: 98%;
            }

            .main-table {
                width: 98%;
                border-collapse: collapse;
            }

            .left-col {
                width: 68%;
                vertical-align: top;
            }

            .divider-col {
                width: 2%;
                border-left: 2px dashed #000;
                position: relative;
            }

            .right-col {
                width: 30%;
                vertical-align: top;
                text-align: right;
            }

            /* Logo Tengah */
            .logo-wrapper {
                position: absolute;
                top: 40px;
                left: -75px;
                width: 170px;
                text-align: center;
            }

            .logo-table {
                width: 100%;
                border-collapse: collapse;
            }

            .logo-table td {
                vertical-align: middle;
            }

            .logo {
                width: 40px;
            }

            .company-name {
                font-size: 8px;
                font-weight: bold;
                text-align: left;
            }

            .info-table {
                width: 100%;
                border-collapse: collapse;
            }

            .info-table td {
                padding: 4px 0;
            }

            .label {
                width: 70px;
            }

            .green-text {
                color: #00a651;
                font-weight: bold;
                margin: 6px 0;
            }

            .total-table {
                width: 260px;
                border-collapse: collapse;
                border: 2px solid #000;
            }

            .total-table td {
                border: 1px solid #000;
                padding: 6px 4px;
            }

            .footer {
                margin-top: 12px;
                font-size: 10px;
                font-style: italic;
                line-height: 1.5;
            }

            .payment-title {
                font-size: 15px;
                font-weight: bold;
                margin-bottom: 6px;
            }

            .payment-date {
                margin-bottom: 6px;
            }

            .customer-name {
                font-weight: bold;
                margin-bottom: 8px;
            }

            .customer-address {
                font-size: 10px;
            }

            .space {
                height: 40px;
            }

            .total-right {
                width: 180px;
                float: right;
            }

            .total-right table {
                width: 100%;
                border-collapse: collapse;
                border: 2px solid #000;
            }

            .total-right td {
                border: 1px solid #000;
                padding: 6px 4px;
            }

            .total-right td:last-child {
                text-align: right;
            }

            .clear {
                clear: both;
            }

            .date-box {
                margin-top: 10px;
                text-align: right;
            }

            .date-box span {
                display: inline-block;
                border: 1px solid #999;
                background: #f3f3f3;
                padding: 4px 10px;
                font-size: 22px;
                font-weight: bold;
                color: red;
            }
        </style>
    </head>

    <body>

        @php
            $tanggal = \Carbon\Carbon::parse($invoice->tanggal);
        @endphp

        <div class="wrapper">

            <table class="main-table">

                <tr>

                    <!-- KIRI -->
                    <td class="left-col">

                        <table class="info-table">

                            <tr>
                                <td class="label">NAMA</td>
                                <td>: {{ strtoupper($invoice->pelanggan->nama ?? '-') }}</td>
                            </tr>

                            <tr>
                                <td class="label">ALAMAT</td>
                                <td>: {{ strtoupper($invoice->pelanggan->alamat ?? '-') }}</td>
                            </tr>

                            <tr>
                                <td class="label">BULAN</td>
                                <td>: {{ $tanggal->locale('id')->translatedFormat('F Y') }}</td>
                            </tr>

                        </table>

                        <div class="green-text">

                            @php
                                $totalTunggakan = $tunggakan->sum('sisa');
                                $tagihanBulanIni = max(0, $invoice->total - $totalTunggakan);
                                $keterangan = [];

                                foreach ($tunggakan as $item) {
                                    $bulan = \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('F');

                                    $keterangan[] =
                                        'KEKURANGAN ' .
                                        strtoupper($bulan) .
                                        ' Rp ' .
                                        number_format($item->sisa, 0, ',', '.');
                                }
                            @endphp

                            {{ implode(' + ', $keterangan) }}

                        </div>

                        <table class="total-table">

                            <tr>
                                <td width="50%">TOTAL</td>
                                <td width="10%">Rp</td>
                                <td align="right">
                                    {{ number_format($tagihanBulanIni, 0, ',', '.') }}
                                </td>
                            </tr>

                        </table>

                        <div class="footer">
                            Konfirmasi Pembayaran atau Komplain WIFI <br>
                            WA : {{ $pengaturan->telepon ?? '-' }}
                        </div>

                    </td>

                    <!-- TENGAH -->
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

                    <!-- KANAN -->
                    <td class="right-col">

                        <div class="payment-title">
                            PAYMENT
                        </div>

                        <div class="payment-date">
                            {{ $tanggal->format('d/m/y') }}
                        </div>

                        <div class="customer-name">
                            {{ strtoupper($invoice->pelanggan->nama ?? '-') }}
                        </div>

                        <div class="customer-address">
                            {{ strtoupper($invoice->pelanggan->alamat ?? '-') }}
                        </div>
                        <div class="green-text">

                            @php
                                $keterangan = [];

                                foreach ($tunggakan as $item) {
                                    $bulan = \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('F');

                                    $keterangan[] =
                                        'KEKURANGAN ' .
                                        strtoupper($bulan) .
                                        ' Rp ' .
                                        number_format($item->sisa, 0, ',', '.');
                                }
                            @endphp

                            {{ implode(' + ', $keterangan) }}

                        </div>

                        <div class="total-right">

                            <table>

                                <tr>
                                    <td width="30">Rp</td>

                                    <td>
                                        {{ number_format($tagihanBulanIni, 0, ',', '.') }}
                                    </td>
                                </tr>

                            </table>

                        </div>

                        <div class="clear"></div>

                    </td>

                </tr>

            </table>

        </div>

    </body>

    </html>
