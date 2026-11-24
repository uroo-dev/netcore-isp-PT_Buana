<table>
    <tr>
        <td colspan="5" style="font-size:9px;color:#999;">
            Dicetak: {{ now()->format('d/m/y H:i') }} |
            {{ $tanggalMulai ? \Carbon\Carbon::parse($tanggalMulai)->format('d/m/y') : 'Awal' }}
            - {{ $tanggalAkhir ? \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/y') : 'Sekarang' }}
            | {{ ucfirst($status) }} | {{ ucfirst($tipe) }}{{ isset($penanggungJawab) && $penanggungJawab ? ' | PJ: ' . $penanggungJawab : '' }}
        </td>
    </tr>
</table>
<br>

@php $grandTotalTagihan = 0; @endphp

@foreach ($invoices as $i => $inv)
    @php $tanggal = \Carbon\Carbon::parse($inv->tanggal); @endphp

    <table border="1" cellpadding="3" cellspacing="0"
        style="border-collapse:collapse;margin-bottom:4px;width:100%;font-size:9px;">
        <tr>
            <td colspan="4" style="width:68%;vertical-align:top;">
                <b>NAMA</b> : {{ strtoupper($inv->pelanggan->nama ?? '-') }}<br>
                <b>ALAMAT</b> : {{ strtoupper($inv->pelanggan->alamat ?? '-') }}<br>
                <b>BULAN</b> : {{ $tanggal->locale('id')->translatedFormat('F Y') }}
            </td>
            <td colspan="2" style="width:30%;vertical-align:top;text-align:right;">
                <div style="font-size:13px;font-weight:bold;">PAYMENT</div>
                <div>{{ $tanggal->format('d/m/y') }}</div>
                <div><b>{{ strtoupper($inv->pelanggan->nama ?? '-') }}</b></div>
                <div style="font-size:8px;">{{ strtoupper($inv->pelanggan->alamat ?? '-') }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:left;color:#00a651;font-weight:bold;">
                @php
                    $tunggakanInv = \App\Models\Invoice::where('pelanggan_id', $inv->pelanggan_id)
                        ->where('id', '<', $inv->id)
                        ->where('status', 'cicilan')
                        ->get();
                    $totalTunggakanInv = $tunggakanInv->sum('sisa');
                    $tagihanBulanIniInv = max(0, $inv->total - $totalTunggakanInv);
                    $keterangan = [];
                    foreach ($tunggakanInv as $item) {
                        $bulan = \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('F');
                        $keterangan[] = 'KEKURANGAN ' . strtoupper($bulan) . ' Rp ' . number_format($item->sisa, 0, ',', '.');
                    }
                @endphp
                {{ implode(' + ', $keterangan) }}
            </td>
        </tr>
        <tr style="background:#f3f3f3;font-weight:bold;font-size:8px;">
            <td style="width:4%;text-align:center;">#</td>
            <td style="width:42%;text-align:center;">Keterangan</td>
            <td style="width:8%;text-align:center;">Qty</td>
            <td style="width:15%;text-align:center;">Harga</td>
            <td style="width:15%;text-align:center;">Subtotal</td>
            <td style="width:16%;text-align:center;">Status</td>
        </tr>
        @forelse ($inv->details as $d => $detail)
            <tr>
                <td style="text-align:center;">{{ $d + 1 }}</td>
                <td style="text-align:left;">{{ $detail->produk->nama ?? ($detail->keterangan ?? 'Item') }}</td>
                <td style="text-align:center;">{{ $detail->qty }}</td>
                <td style="text-align:right;">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                <td style="text-align:right;">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align:center;color:#999;">Tidak ada item</td>
            </tr>
        @endforelse
        <tr style="font-weight:bold;border-top:2px solid #000;">
            <td colspan="5" style="text-align:left;">
                Total Tagihan: Rp{{ number_format($tagihanBulanIniInv, 0, ',', '.') }}
                @if($totalTunggakanInv > 0)
                    | Piutang: Rp{{ number_format($totalTunggakanInv, 0, ',', '.') }}
                @endif
            </td>
            <td style="text-align:right;">Rp{{ number_format($tagihanBulanIniInv, 0, ',', '.') }}</td>
        </tr>
        @php $grandTotalTagihan += $tagihanBulanIniInv; @endphp
        <tr>
            <td colspan="6" style="font-size:8px;text-align:right;font-style:italic;">
                Konfirmasi Pembayaran atau Komplain WIFI WA : {{ $pengaturan->telepon ?? '-' }}
            </td>
        </tr>
    </table>
    <br>
@endforeach

@if ($invoices->count() > 1)
    <table border="1" cellpadding="3" cellspacing="0"
        style="border-collapse:collapse;margin-top:4px;background:#f9f9f9;">
        <tr style="font-weight:bold;font-size:10px;">
            <td style="text-align:right;width:75%;">GRAND TOTAL ({{ $invoices->count() }} tagihan)</td>
            <td style="text-align:right;width:25%;">Rp{{ number_format($grandTotalTagihan, 0, ',', '.') }}</td>
        </tr>
    </table>
@endif
