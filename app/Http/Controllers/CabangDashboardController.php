<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\LogAktivitas;
use App\Models\LogStok;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;

class CabangDashboardController extends Controller
{
    public function index(Request $request)
    {
        $cabang = auth()->user()->cabang;
        if (! $cabang) {
            abort(403, 'Akun ini tidak terhubung ke cabang manapun.');
        }

        $query = ProdukCabang::with('produk')->where('cabang_id', $cabang->id);

        if ($request->q) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->q.'%');
            });
        }

        $produkCabang = $query->orderBy('stok', 'asc')->paginate(25)->onEachSide(2);

        $agg = ProdukCabang::where('cabang_id', $cabang->id)
            ->selectRaw('COUNT(*) as total_produk, COALESCE(SUM(stok),0) as total_stok, SUM(CASE WHEN stok=0 THEN 1 ELSE 0 END) as habis')
            ->first();
        $totalProduk = $agg->total_produk ?? 0;
        $totalStok = $agg->total_stok ?? 0;
        $totalHabis = $agg->habis ?? 0;

        return view('pages.cabang.dashboard', compact('cabang', 'produkCabang', 'totalProduk', 'totalStok', 'totalHabis'));
    }

    public function fetchData(Request $request)
    {
        $cabang = auth()->user()->cabang;
        if (! $cabang) {
            abort(403, 'Akun ini tidak terhubung ke cabang manapun.');
        }

        $query = ProdukCabang::with('produk')->where('cabang_id', $cabang->id);

        if ($request->q) {
            $query->whereHas('produk', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->q.'%');
            });
        }

        $produkCabang = $query->orderBy('stok', 'asc')->paginate(25)->onEachSide(2)->withQueryString();

        return response()->json([
            'rows' => view('pages.cabang.partials.dashboard-rows', compact('produkCabang'))->render(),
            'pagination' => view('pages.cabang.partials.dashboard-pagination', compact('produkCabang'))->render(),
        ]);
    }

    public function pakai(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $cabang = auth()->user()->cabang;
        if (! $cabang) {
            return back()->with('error', 'Akses ditolak.');
        }

        $pc = ProdukCabang::with('produk')->where('id', $id)->where('cabang_id', $cabang->id)->firstOrFail();

        if ($pc->stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi. Stok tersedia: '.$pc->stok);
        }

        $pc->stok -= $request->jumlah;
        $pc->save();

        $keterangan = $request->keterangan ?: 'Pemakaian stok: '.$pc->produk->nama.' ('.$request->jumlah.' pcs)';

        LogStok::create([
            'produk_id' => $pc->produk_id,
            'cabang_id' => $cabang->id,
            'aksi' => 'keluar',
            'jumlah' => $request->jumlah,
            'user_id' => auth()->id(),
            'keterangan' => $keterangan,
            'tanggal' => now(),
        ]);

        $biaya = $request->jumlah * $pc->produk->harga_beli;

        if ($biaya > 0) {
            $kasTerakhir = Kas::orderByDesc('id')->first();
            $saldoSebelumnya = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

            Kas::create([
                'tanggal' => now(),
                'tipe' => 'keluar',
                'kategori' => 'pemakaian',
                'keterangan' => $keterangan,
                'jumlah' => $biaya,
                'saldo_akhir' => $saldoSebelumnya - $biaya,
                'user_id' => auth()->id(),
            ]);
        }

        LogAktivitas::catat('create', 'ProdukCabang', $pc->id, 'Pemakaian stok cabang '.$cabang->nama.': '.$pc->produk->nama.' (-'.$request->jumlah.')');

        return redirect()->back()->with('success', 'Stok berhasil dipakai.');
    }
}
