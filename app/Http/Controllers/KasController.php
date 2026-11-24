<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.kas.index', $this->buildData($request));
    }

    public function fetchData(Request $request)
    {
        if (! $request->expectsJson()) {
            return redirect()->route('kas.index');
        }

        $data = $this->buildData($request);

        return response()->json([
            'rows' => view('pages.kas.partials.table-rows', $data)->render(),
            'stats' => view('pages.kas.partials.stats', $data)->render(),
            'pagination' => $data['kas']->hasPages() ? $data['kas']->links()->toHtml() : '',
        ]);
    }

    private function buildData(Request $request)
    {
        $query = Kas::with('user')->latest();

        if ($request->tipe) {
            $query->where('tipe', $request->tipe);
        }

        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        $hasDateFilter = $tanggalAwal && $tanggalAkhir;

        if ($hasDateFilter) {
            $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
        }

        $kas = $query->paginate(25)->onEachSide(2)->withQueryString();

        $queryStats = Kas::query();
        if ($request->tipe) {
            $queryStats->where('tipe', $request->tipe);
        }

        $totalMasuk = (clone $queryStats)->where('tipe', 'masuk');
        $totalKeluar = (clone $queryStats)->where('tipe', 'keluar');

        if ($hasDateFilter) {
            $totalMasuk->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
            $totalKeluar->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
        }

        $totalMasuk = $totalMasuk->sum('jumlah');
        $totalKeluar = $totalKeluar->sum('jumlah');
        $saldoPeriode = $totalMasuk - $totalKeluar;

        if ($hasDateFilter) {
            $saldoSebelum = Kas::where('tanggal', '<', $tanggalAwal)
                ->selectRaw("COALESCE(SUM(CASE WHEN tipe='masuk' THEN jumlah WHEN tipe='keluar' THEN -jumlah END), 0) as total")
                ->value('total');

            $dailyKas = Kas::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->select('tanggal')
                ->selectRaw("COALESCE(SUM(CASE WHEN tipe='masuk' THEN jumlah ELSE 0 END), 0) as total_masuk")
                ->selectRaw("COALESCE(SUM(CASE WHEN tipe='keluar' THEN jumlah ELSE 0 END), 0) as total_keluar")
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();

            $runningSaldo = $saldoSebelum;
            $saldoPerHari = $dailyKas->map(function ($item) use (&$runningSaldo) {
                $runningSaldo = $runningSaldo + $item->total_masuk - $item->total_keluar;
                $item->saldo_akhir = $runningSaldo;

                return $item;
            });
        } else {
            $saldoSebelum = 0;
            $saldoPerHari = collect();
        }

        $kasTerakhir = Kas::orderByDesc('id')->first();
        $saldoSekarang = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

        $totalMasukBulanIni = Kas::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->where('tipe', 'masuk')->sum('jumlah');
        $totalKeluarBulanIni = Kas::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->where('tipe', 'keluar')->sum('jumlah');
        $kasBulanIni = $totalMasukBulanIni - $totalKeluarBulanIni;

        return compact('kas', 'totalMasuk', 'totalKeluar', 'saldoPeriode', 'saldoSekarang', 'totalMasukBulanIni', 'totalKeluarBulanIni', 'kasBulanIni', 'saldoPerHari', 'saldoSebelum', 'hasDateFilter');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:masuk,keluar',
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $kasTerakhir = Kas::orderByDesc('id')->first();
        $saldoSebelumnya = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

        $saldoAkhir = $validated['tipe'] === 'masuk'
            ? $saldoSebelumnya + $validated['jumlah']
            : $saldoSebelumnya - $validated['jumlah'];

        $kas = Kas::create([
            'tanggal' => $validated['tanggal'],
            'tipe' => $validated['tipe'],
            'kategori' => 'manual',
            'keterangan' => $validated['keterangan'],
            'jumlah' => $validated['jumlah'],
            'saldo_akhir' => $saldoAkhir,
            'user_id' => auth()->id(),
        ]);

        LogAktivitas::catat('create', 'Kas', $kas->id, 'Transaksi keuangan '.$validated['tipe'].': '.$validated['keterangan']);

        return redirect()->back()->with('success', 'Transaksi keuangan berhasil ditambahkan');
    }

    public function recalculateSaldo()
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET @saldo := 0');
            DB::statement('UPDATE kas SET saldo_akhir = (@saldo := IF(tipe="masuk", @saldo + jumlah, @saldo - jumlah)) ORDER BY tanggal, id');
        } else {
            $saldo = 0;
            $items = Kas::orderBy('tanggal')->orderBy('id')->get();
            foreach ($items as $item) {
                $saldo = $item->tipe === 'masuk' ? $saldo + $item->jumlah : $saldo - $item->jumlah;
                $item->saldo_akhir = $saldo;
                $item->save();
            }
        }

        LogAktivitas::catat('update', 'Kas', null, 'Rekalkulasi saldo kas dijalankan');

        return redirect()->back()->with('success', 'Saldo kas berhasil direkalkulasi.');
    }
}
