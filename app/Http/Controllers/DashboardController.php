<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Kas;
use App\Models\Pelanggan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pelanggan::count();
        $totalPelangganOn = Pelanggan::aktif()->count();
        $totalPelangganOff = Pelanggan::nonaktif()->count();
        $totalProduk = Produk::count();
        $totalInvoice = Invoice::count();
        $totalPiutang = Invoice::where('status', 'cicilan')->sum('sisa');
        $totalLunas = Invoice::where('status', 'lunas')->sum('dibayar');

        $invoiceBulanIni = Invoice::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total');

        $kasTerakhir = Kas::orderByDesc('id')->first();
        $saldoKas = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

        $pendapatanBulanIni = Kas::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where('tipe', 'masuk')
            ->sum('jumlah');

        $pengeluaranBulanIni = Kas::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->where('tipe', 'keluar')
            ->sum('jumlah');

        $produkMenipis = Produk::where('stok', '>', 0)->where('stok', '<=', 10)->count();
        $produkHabis = Produk::where('stok', 0)->count();

        $invoiceBelumLunas = Invoice::with('pelanggan')->where('status', 'cicilan')->latest()->take(5)->get();
        $pelangganBaru = Pelanggan::with('paket')->latest()->take(5)->get();

        $periodeMulai = now()->subMonths(5)->startOfMonth();
        $periodeAkhir = now()->endOfMonth();

        $grafikPenjualan = Invoice::whereBetween('tanggal', [$periodeMulai, $periodeAkhir])
            ->selectRaw('YEAR(tanggal) as thn, MONTH(tanggal) as bln, SUM(total) as total')
            ->groupBy('thn', 'bln')
            ->orderBy('thn')
            ->orderBy('bln')
            ->get()
            ->keyBy(fn ($item) => $item->thn.'-'.str_pad($item->bln, 2, '0', STR_PAD_LEFT));

        $pelangganOffBulanan = Pelanggan::where('status', 'off')
            ->whereBetween('tanggal_off', [$periodeMulai, $periodeAkhir])
            ->selectRaw('YEAR(tanggal_off) as thn, MONTH(tanggal_off) as bln, COUNT(*) as total')
            ->groupBy('thn', 'bln')
            ->orderBy('thn')
            ->orderBy('bln')
            ->get()
            ->keyBy(fn ($item) => $item->thn.'-'.str_pad($item->bln, 2, '0', STR_PAD_LEFT));

        $grafikLabel = [];
        $finalPenjualan = [];
        $finalOff = [];
        for ($i = 5; $i >= 0; $i--) {
            $tgl = now()->subMonths($i);
            $key = $tgl->format('Y-m');
            $grafikLabel[] = $tgl->format('M Y');
            $finalPenjualan[] = (int) ($grafikPenjualan[$key]->total ?? 0);
            $finalOff[] = (int) ($pelangganOffBulanan[$key]->total ?? 0);
        }
        $grafikPenjualan = $finalPenjualan;
        $pelangganOffBulanan = $finalOff;

        return view('pages.dashboard.index', compact(
            'totalPelanggan',
            'totalPelangganOn',
            'totalPelangganOff',
            'totalProduk',
            'totalInvoice',
            'totalPiutang',
            'totalLunas',
            'invoiceBulanIni',
            'saldoKas',
            'pendapatanBulanIni',
            'pengeluaranBulanIni',
            'produkMenipis',
            'produkHabis',
            'invoiceBelumLunas',
            'pelangganBaru',
            'grafikPenjualan',
            'grafikLabel',
            'pelangganOffBulanan'
        ));
    }

    public function pajak(Request $request)
    {
        $data = $this->buildPajakData($request->bulan);

        return view('pages.dashboard.pajak', $data);
    }

    public function fetchDataPajak(Request $request)
    {
        $data = $this->buildPajakData($request->bulan);

        return response()->json([
            'content' => view('pages.dashboard.partials.pajak-content', $data)->render(),
        ]);
    }

    private function buildPajakData($bulan)
    {
        $bulan = $bulan ?? now()->format('Y-m');
        $tahun = substr($bulan, 0, 4);
        $bulanNum = substr($bulan, 5, 2);

        $invoices = Invoice::with('pelanggan')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->orderBy('tanggal')
            ->paginate(25)->onEachSide(2)->withQueryString();

        // Hitung agregat dari semua invoice bulan ini (1 query)
        $agg = Invoice::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->selectRaw('COUNT(*) as total_tagihan, COUNT(DISTINCT pelanggan_id) as total_pelanggan, SUM(total) as total_harga')
            ->first();

        $totalPelanggan = $agg->total_pelanggan ?? 0;
        $totalTagihan = $agg->total_tagihan ?? 0;
        $totalHargaPaket = (float) ($agg->total_harga ?? 0);

        $totalDpp = $totalHargaPaket / 1.11;
        $totalPpn = $totalHargaPaket - $totalDpp;
        $totalBhp = ($totalHargaPaket / 1.11) * 0.0175;
        $totalProyeksi = $totalBhp + $totalPpn;

        // Data per bulan untuk grafik 12 bulan
        $bulanan = Invoice::whereYear('tanggal', $tahun)
            ->selectRaw('MONTH(tanggal) as bln, COUNT(*) as total_tagihan, SUM(total) as total_harga')
            ->groupBy('bln')
            ->orderBy('bln')
            ->get()
            ->keyBy('bln');

        $grafikBulan = [];
        $grafikDpp = [];
        $grafikPpn = [];
        $grafikBhp = [];
        $grafikProyeksi = [];
        for ($m = 1; $m <= 12; $m++) {
            $data = $bulanan[$m] ?? null;
            $harga = $data ? $data->total_harga : 0;
            $dpp = $harga / 1.11;
            $ppn = $harga - $dpp;
            $bhp = $dpp * 0.0175;
            $grafikBulan[] = Carbon::create($tahun, $m, 1)->format('M');
            $grafikDpp[] = round($dpp);
            $grafikPpn[] = round($ppn);
            $grafikBhp[] = round($bhp);
            $grafikProyeksi[] = round($bhp + $ppn);
        }

        return compact(
            'invoices', 'bulan',
            'totalPelanggan', 'totalTagihan', 'totalHargaPaket',
            'totalDpp', 'totalPpn', 'totalBhp', 'totalProyeksi',
            'grafikBulan', 'grafikDpp', 'grafikPpn', 'grafikBhp', 'grafikProyeksi'
        );
    }
}
