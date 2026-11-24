<?php

namespace App\Http\Controllers;

use App\Exports\TagihanExport;
use App\Models\Cabang;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Kas;
use App\Models\LogStok;
use App\Models\PenanggungJawabWifi;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.laporan.index', $this->buildData($request));
    }

    public function fetchData(Request $request)
    {
        if (! $request->expectsJson()) {
            return redirect()->route('laporan.index');
        }

        $data = $this->buildData($request);

        return response()->json([
            'cards' => view('pages.laporan.partials.cards', $data)->render(),
            'invoices' => view('pages.laporan.partials.invoices', $data)->render(),
            'labaRugi' => view('pages.laporan.partials.laba-rugi', $data)->render(),
            'penjualanBulanan' => $data['penjualanBulanan'],
            'pengeluaranBulanan' => $data['pengeluaranBulanan'],
            'piutangBulanan' => $data['piutangBulanan'],
            'tahun' => $data['tahun'],
        ]);
    }

    private function buildData(Request $request)
    {
        $bulan = $request->bulan ?? now()->format('Y-m');
        $tahun = substr($bulan, 0, 4);
        $bulanNum = substr($bulan, 5, 2);

        $totalPenjualan = Invoice::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->sum('total');

        $totalDibayar = Invoice::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->sum('dibayar');

        $totalPiutang = Invoice::where('status', 'cicilan')->sum('sisa');

        $totalKasMasuk = Kas::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->where('tipe', 'masuk')
            ->sum('jumlah');

        $totalKasKeluar = Kas::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->where('tipe', 'keluar')
            ->sum('jumlah');

        $saldoKas = Kas::orderByDesc('id')->first()?->saldo_akhir ?? 0;

        $labaRugi = $totalKasMasuk - $totalKasKeluar;

        // Batch: semua data bulanan dalam 5 grouped queries
        $penjualanBulanan = Invoice::whereYear('tanggal', $tahun)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(total) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $tagihanBulanan = Invoice::whereYear('tanggal', $tahun)
            ->selectRaw('MONTH(tanggal) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $piutangBulanan = Invoice::where('status', 'cicilan')
            ->whereYear('created_at', $tahun)
            ->selectRaw('MONTH(created_at) as bulan, SUM(sisa) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $pengeluaranBulanan = Kas::whereYear('tanggal', $tahun)
            ->where('tipe', 'keluar')
            ->selectRaw('MONTH(tanggal) as bulan, SUM(jumlah) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $pembelianBulanan = InvoiceDetail::whereNotNull('produk_id')
            ->whereYear('created_at', $tahun)
            ->selectRaw('MONTH(created_at) as bulan, SUM(subtotal) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        $pembelianBarang = $pembelianBulanan[$bulanNum] ?? 0;

        // Fill 1..12 dengan nilai 0 jika bulan tidak ada data
        $fillMonthly = function ($data) {
            $result = [];
            for ($i = 1; $i <= 12; $i++) {
                $result[] = (int) ($data[$i] ?? 0);
            }

            return $result;
        };
        $penjualanBulanan = $fillMonthly($penjualanBulanan);
        $tagihanBulanan = $fillMonthly($tagihanBulanan);
        $piutangBulanan = $fillMonthly($piutangBulanan);
        $pengeluaranBulanan = $fillMonthly($pengeluaranBulanan);
        $pembelianBulanan = $fillMonthly($pembelianBulanan);

        $invoices = Invoice::with('pelanggan')
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulanNum)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $cabangList = Cabang::orderBy('nama')->get();

        return compact(
            'bulan',
            'totalPenjualan',
            'totalDibayar',
            'totalPiutang',
            'totalKasMasuk',
            'totalKasKeluar',
            'saldoKas',
            'labaRugi',
            'penjualanBulanan',
            'piutangBulanan',
            'pengeluaranBulanan',
            'pembelianBarang',
            'pembelianBulanan',
            'tagihanBulanan',
            'invoices',
            'tahun',
            'cabangList'
        );
    }

    public function exportPdf()
    {
        return redirect()->route('laporan.index');
    }

    public function notaBulanan(Request $request)
    {
        set_time_limit(0);
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;
        $status = $request->status ?? 'semua';
        $tipe = $request->tipe ?? 'semua';

        $query = Invoice::with(['pelanggan', 'details.produk']);

        if ($tanggalMulai && $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        } elseif ($tanggalMulai) {
            $query->where('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalAkhir) {
            $query->where('tanggal', '<=', $tanggalAkhir);
        }

        if ($status != 'semua') {
            $query->where('status', $status);
        }

        if ($status === 'cicilan' || $status === 'semua') {
            $query->where('sisa', '>', 0);
        } elseif ($status === 'lunas') {
            $query->where('sisa', '<=', 0);
        }

        if ($tipe != 'semua') {
            $query->whereHas('pelanggan', function ($q) use ($tipe) {
                $q->where('tipe', $tipe);
            });
        }

        $invoices = $query->orderBy('tanggal')->orderBy('kode_invoice')->limit(5000)->get();
        $totalKeseluruhan = $invoices->sum('total');

        $pelangganIds = $invoices->pluck('pelanggan_id')->unique()->values()->toArray();

        $allTunggakan = collect();
        if (! empty($pelangganIds)) {
            $allTunggakan = Invoice::whereIn('pelanggan_id', $pelangganIds)
                ->where('status', 'cicilan')
                ->where('sisa', '>', 0)
                ->orderBy('id')
                ->get()
                ->groupBy('pelanggan_id');
        }

        $mode = 'pdf';

        $pdf = Pdf::loadView('pages.laporan.nota', compact('invoices', 'totalKeseluruhan', 'tanggalMulai', 'tanggalAkhir', 'status', 'tipe', 'mode', 'allTunggakan'));
        $pdf->setPaper([0, 0, 609, 978], 'portrait');

        return $pdf->stream('Tagihan-Print-'.now()->format('Ymd-His').'.pdf');
    }

    public function cetakTagihan(Request $request)
    {
        set_time_limit(0);
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;
        $status = $request->status ?? 'semua';
        $tipe = $request->tipe ?? 'semua';
        $type = $request->type ?? 'pdf';

        $query = Invoice::with(['pelanggan', 'details.produk']);

        if ($tanggalMulai && $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        } elseif ($tanggalMulai) {
            $query->where('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalAkhir) {
            $query->where('tanggal', '<=', $tanggalAkhir);
        }

        if ($status != 'semua') {
            $query->where('status', $status);
        }

        if ($status === 'cicilan' || $status === 'semua') {
            $query->where('sisa', '>', 0);
        } elseif ($status === 'lunas') {
            $query->where('sisa', '<=', 0);
        }

        if ($tipe != 'semua') {
            $query->whereHas('pelanggan', function ($q) use ($tipe) {
                $q->where('tipe', $tipe);
            });
        }

        if ($request->penanggung_jawab) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('penanggung_jawab_id', $request->penanggung_jawab);
            });
        }

        $invoices = $query->orderBy('tanggal')->orderBy('kode_invoice')->limit(5000)->get();
        $totalKeseluruhan = $invoices->sum('total');

        $pelangganIds = $invoices->pluck('pelanggan_id')->unique()->values()->toArray();

        $allTunggakan = collect();
        if (! empty($pelangganIds)) {
            $allTunggakan = Invoice::whereIn('pelanggan_id', $pelangganIds)
                ->where('status', 'cicilan')
                ->where('sisa', '>', 0)
                ->orderBy('id')
                ->get()
                ->groupBy('pelanggan_id');
        }

        $penanggungJawab = $request->penanggung_jawab ? PenanggungJawabWifi::find($request->penanggung_jawab)?->nama : null;

        if ($type === 'excel') {
            $filename = 'Tagihan-'.now()->format('Ymd-His').'.xlsx';

            return app(Excel::class)->download(
                new TagihanExport($invoices, $totalKeseluruhan, $tanggalMulai, $tanggalAkhir, $status, $tipe, $request->penanggung_jawab),
                $filename
            );
        }

        $mode = 'pdf';
        $pdf = Pdf::loadView('pages.laporan.nota', compact('invoices', 'totalKeseluruhan', 'tanggalMulai', 'tanggalAkhir', 'status', 'tipe', 'mode', 'penanggungJawab', 'allTunggakan'));
        $pdf->setPaper([0, 0, 609, 978], 'portrait');

        return $pdf->stream('Tagihan-Print-'.now()->format('Ymd-His').'.pdf');
    }

    public function cetakProduk(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;
        $tipe = $request->tipe;

        $query = LogStok::with('produk', 'cabang', 'user')->orderBy('tanggal')->orderBy('id');

        if ($tanggalMulai && $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        } elseif ($tanggalMulai) {
            $query->where('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalAkhir) {
            $query->where('tanggal', '<=', $tanggalAkhir);
        }

        if ($tipe) {
            $query->where('aksi', $tipe);
        }

        $logStok = $query->limit(5000)->get();

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pages.laporan.produk', compact(
            'logStok', 'tanggalMulai', 'tanggalAkhir', 'tipe', 'pengaturan'
        ));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Laporan-Produk-'.now()->format('Ymd-His').'.pdf');
    }

    public function cetakKasPerusahaan(Request $request)
    {
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;

        $query = Kas::with('user')->orderBy('tanggal')->orderBy('id');

        if ($tanggalMulai && $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        } elseif ($tanggalMulai) {
            $query->where('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalAkhir) {
            $query->where('tanggal', '<=', $tanggalAkhir);
        }

        $kasEntries = $query->limit(5000)->get();

        $totalMasuk = $kasEntries->where('tipe', 'masuk')->sum('jumlah');
        $totalKeluar = $kasEntries->where('tipe', 'keluar')->sum('jumlah');
        $saldoAkhir = $kasEntries->last()?->saldo_akhir ?? 0;

        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pages.laporan.kas_perusahaan', compact(
            'kasEntries', 'totalMasuk', 'totalKeluar', 'saldoAkhir',
            'tanggalMulai', 'tanggalAkhir', 'pengaturan'
        ));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Kas-Perusahaan-'.now()->format('Ymd-His').'.pdf');
    }
}
