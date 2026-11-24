<?php

namespace App\Http\Controllers;

use App\Models\BiayaOperasional;
use App\Models\Kas;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiayaOperasionalController extends Controller
{
    public function index(Request $request)
    {
        $query = BiayaOperasional::with('user')->latest('tanggal');

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $data = $query->paginate(25)->onEachSide(2);

        $totalBulanIni = BiayaOperasional::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->sum('jumlah');

        $totalSemua = BiayaOperasional::sum('jumlah');

        $kategoris = BiayaOperasional::distinct()->pluck('kategori');

        $keuanganMasukBulanIni = Kas::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->where('tipe', 'masuk')->sum('jumlah');
        $keuanganKeluarBulanIni = Kas::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->where('tipe', 'keluar')->sum('jumlah');
        $saldoKeuangan = Kas::orderByDesc('id')->first()?->saldo_akhir ?? 0;

        $keuanganBulanan = Kas::selectRaw('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun')
            ->selectRaw('SUM(CASE WHEN tipe="masuk" THEN jumlah ELSE 0 END) as total_masuk')
            ->selectRaw('SUM(CASE WHEN tipe="keluar" THEN jumlah ELSE 0 END) as total_keluar')
            ->whereYear('tanggal', now()->year)
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        return view('pages.biaya-operasional.index', compact(
            'data', 'totalBulanIni', 'totalSemua', 'kategoris',
            'keuanganMasukBulanIni', 'keuanganKeluarBulanIni', 'saldoKeuangan', 'keuanganBulanan'
        ));
    }

    public function fetchData(Request $request)
    {
        $query = BiayaOperasional::with('user')->latest('tanggal');

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $data = $query->paginate(25)->onEachSide(2)->withQueryString();

        return response()->json([
            'rows' => view('pages.biaya-operasional.partials.rows', compact('data'))->render(),
            'pagination' => view('pages.biaya-operasional.partials.pagination', compact('data'))->render(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $kasTerakhir = Kas::orderByDesc('id')->first();
        $saldoSebelumnya = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

        $kas = Kas::create([
            'tanggal' => $validated['tanggal'],
            'tipe' => 'keluar',
            'kategori' => 'operasional',
            'keterangan' => 'Biaya operasional: '.$validated['keterangan'].' ('.$validated['kategori'].')',
            'jumlah' => $validated['jumlah'],
            'saldo_akhir' => $saldoSebelumnya - $validated['jumlah'],
            'user_id' => auth()->id(),
        ]);

        $validated['user_id'] = auth()->id();
        $validated['kas_id'] = $kas->id;

        $biaya = BiayaOperasional::create($validated);

        LogAktivitas::catat('create', 'BiayaOperasional', $biaya->id, 'Menambah biaya operasional: '.$validated['keterangan']);

        return redirect()->back()->with('success', 'Biaya operasional berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $biaya = BiayaOperasional::findOrFail($id);

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'keterangan' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
        ]);

        $selisih = $validated['jumlah'] - $biaya->jumlah;

        $biaya->update($validated);

        if ($biaya->kas_id && $selisih != 0) {
            Kas::where('id', $biaya->kas_id)->update([
                'jumlah' => DB::raw('jumlah + '.$selisih),
                'saldo_akhir' => DB::raw('saldo_akhir - '.$selisih),
                'keterangan' => 'Biaya operasional: '.$validated['keterangan'].' ('.$validated['kategori'].')',
            ]);
        }

        LogAktivitas::catat('update', 'BiayaOperasional', $id, 'Mengubah biaya operasional: '.$validated['keterangan']);

        return redirect()->back()->with('success', 'Biaya operasional berhasil diubah.');
    }

    public function destroy($id)
    {
        $biaya = BiayaOperasional::findOrFail($id);

        if ($biaya->kas_id) {
            Kas::where('id', $biaya->kas_id)->delete();
        }

        LogAktivitas::catat('delete', 'BiayaOperasional', $id, 'Menghapus biaya operasional: '.$biaya->keterangan);

        $biaya->delete();

        return redirect()->back()->with('success', 'Biaya operasional berhasil dihapus.');
    }
}
