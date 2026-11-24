<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kas;
use App\Models\LogAktivitas;
use App\Models\LogStok;
use App\Models\Produk;
use App\Models\ProdukCabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->stok_status) {
            if ($request->stok_status === 'menipis') {
                $query->where('stok', '>', 0)->where('stok', '<=', 10);
            } elseif ($request->stok_status === 'habis') {
                $query->where('stok', 0);
            } elseif ($request->stok_status === 'tersedia') {
                $query->where('stok', '>', 10);
            }
        }

        if ($request->q) {
            $query->where('nama', 'like', '%'.$request->q.'%');
        }

        $produk = $query->orderBy('nama')->paginate(25)->onEachSide(2);
        $kategoris = Produk::whereNotNull('kategori')->distinct()->pluck('kategori');

        $stokStats = Produk::selectRaw('COUNT(*) as total, SUM(CASE WHEN stok=0 THEN 1 ELSE 0 END) as habis, SUM(CASE WHEN stok>0 AND stok<=10 THEN 1 ELSE 0 END) as menipis, SUM(CASE WHEN stok>10 THEN 1 ELSE 0 END) as tersedia')->first();
        $totalProduk = $stokStats->total;
        $totalMenipis = $stokStats->menipis;
        $totalHabis = $stokStats->habis;
        $totalTersedia = $stokStats->tersedia;

        $produkTerlaris = Produk::withCount(['details as terjual' => function ($query) {
            $query->select(DB::raw('sum(qty)'));
        }])->orderByDesc('terjual')->take(5)->get();

        $cabangs = Cabang::orderBy('nama')->get();

        return view('pages.produk.index', compact(
            'produk',
            'kategoris',
            'totalProduk',
            'totalMenipis',
            'totalHabis',
            'totalTersedia',
            'produkTerlaris',
            'cabangs'
        ));
    }

    public function fetchData(Request $request)
    {
        $query = Produk::query();

        if ($request->kategori) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->stok_status) {
            if ($request->stok_status === 'menipis') {
                $query->where('stok', '>', 0)->where('stok', '<=', 10);
            } elseif ($request->stok_status === 'habis') {
                $query->where('stok', 0);
            } elseif ($request->stok_status === 'tersedia') {
                $query->where('stok', '>', 10);
            }
        }

        if ($request->q) {
            $query->where('nama', 'like', '%'.$request->q.'%');
        }

        $produk = $query->orderBy('nama')->paginate(25)->onEachSide(2)->withQueryString();

        return response()->json([
            'rows' => view('pages.produk.partials.rows', compact('produk'))->render(),
            'pagination' => view('pages.produk.partials.pagination', compact('produk'))->render(),
            'pindah' => view('pages.produk.partials.pindah', compact('produk'))->render(),
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required',
            'kategori' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
        ]);

        $produk = Produk::create($validate);

        if ($produk->stok > 0) {
            LogStok::create([
                'produk_id' => $produk->id,
                'aksi' => 'masuk',
                'jumlah' => $produk->stok,
                'user_id' => auth()->id(),
                'keterangan' => 'Pembelian produk: '.$produk->nama,
                'tanggal' => now(),
            ]);
        }

        if ($produk->stok > 0 && ($produk->harga_beli ?? 0) > 0) {
            $totalBiaya = $produk->stok * $produk->harga_beli;
            $kasTerakhir = Kas::orderByDesc('id')->first();
            $saldoSebelumnya = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;

            Kas::create([
                'tanggal' => now(),
                'tipe' => 'keluar',
                'kategori' => 'beli_produk',
                'keterangan' => 'Pembelian produk: '.$produk->nama.' ('.$produk->stok.' pcs)',
                'jumlah' => $totalBiaya,
                'saldo_akhir' => $saldoSebelumnya - $totalBiaya,
                'user_id' => auth()->id(),
            ]);
        }

        LogAktivitas::catat('create', 'Produk', $produk->id, 'Menambah produk: '.$produk->nama);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validate = $request->validate([
            'nama' => 'required',
            'kategori' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
        ]);

        $produk->update($validate);

        LogAktivitas::catat(
            'update',
            'Produk',
            $id,
            'Mengubah produk: '.$validate['nama'],
            [
                'nama' => $produk->nama,
                'harga' => $produk->harga,
                'harga_beli' => $produk->harga_beli,
                'stok' => $produk->stok,
                'kategori' => $produk->kategori,
            ],
            [
                'nama' => $validate['nama'],
                'harga' => $validate['harga'],
                'harga_beli' => $validate['harga_beli'] ?? 0,
                'stok' => $validate['stok'],
                'kategori' => $validate['kategori'] ?? null,
            ]
        );

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function restok(Request $request, $id)
    {
        $validate = $request->validate([
            'stok' => 'required|integer|min:1',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->stok += $validate['stok'];
        $produk->save();

        LogStok::create([
            'produk_id' => $produk->id,
            'aksi' => 'masuk',
            'jumlah' => $validate['stok'],
            'user_id' => auth()->id(),
            'keterangan' => 'Restok produk: '.$produk->nama,
            'tanggal' => now(),
        ]);

        if ($validate['total_biaya'] > 0) {
            $kasTerakhir = Kas::orderByDesc('id')->first();
            $saldoSebelumnya = $kasTerakhir ? $kasTerakhir->saldo_akhir : 0;
            $saldoAkhir = $saldoSebelumnya - $validate['total_biaya'];

            if ($saldoAkhir >= 0) {
                Kas::create([
                    'tanggal' => now(),
                    'tipe' => 'keluar',
                    'kategori' => 'restok',
                    'keterangan' => 'Pembelian stok: '.$produk->nama.' ('.$validate['stok'].' pcs)',
                    'jumlah' => $validate['total_biaya'],
                    'saldo_akhir' => $saldoAkhir,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        LogAktivitas::catat('create', 'Produk', $id, 'Restok produk: '.$produk->nama.' (+'.$validate['stok'].' pcs, biaya: Rp'.number_format($validate['total_biaya'], 0, ',', '.').')');

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->details()->exists()) {
            return back()->with('error', 'Produk tidak bisa dihapus karena sudah pernah digunakan dalam tagihan.');
        }

        LogAktivitas::catat('delete', 'Produk', $id, 'Menghapus produk: '.$produk->nama);

        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $produk = Produk::where('nama', 'like', '%'.$q.'%')
            ->where('stok', '>', 0)
            ->limit(10)
            ->get();

        return response()->json($produk);
    }

    public function pindahKeCabang(Request $request)
    {
        $validated = $request->validate([
            'produk_ids' => 'required|array',
            'produk_ids.*' => 'exists:produk,id',
            'cabang_id' => 'required|exists:cabang,id',
            'jumlah' => 'array',
        ]);

        $cabang = Cabang::findOrFail($validated['cabang_id']);

        $hasTransfer = false;

        $produkList = Produk::whereIn('id', $validated['produk_ids'])->get()->keyBy('id');

        foreach ($validated['produk_ids'] as $produkId) {
            $jumlah = $validated['jumlah'][$produkId] ?? 0;
            if ($jumlah <= 0) {
                continue;
            }

            $produk = $produkList->get($produkId);
            if (! $produk) {
                continue;
            }

            if ($produk->stok < $jumlah) {
                return back()->with('error', 'Stok "'.$produk->nama.'" tidak mencukupi. Tersedia: '.$produk->stok);
            }

            Produk::where('id', $produk->id)->decrement('stok', $jumlah);

            ProdukCabang::updateOrInsert(
                ['produk_id' => $produkId, 'cabang_id' => $validated['cabang_id']],
                ['stok' => DB::raw('COALESCE(stok,0) + '.$jumlah)]
            );

            LogStok::create([
                'produk_id' => $produk->id,
                'aksi' => 'pindah',
                'jumlah' => $jumlah,
                'cabang_id' => $validated['cabang_id'],
                'user_id' => auth()->id(),
                'keterangan' => 'Pindah ke cabang: '.$cabang->nama,
                'tanggal' => now(),
            ]);

            $hasTransfer = true;
        }

        if (! $hasTransfer) {
            return back()->with('error', 'Tidak ada produk yang dipindahkan. Centang produk dan isi jumlah minimal 1.');
        }

        LogAktivitas::catat('create', 'Produk', null, 'Memindahkan stok ke cabang: '.$cabang->nama);

        return redirect()->back()->with('success', 'Stok berhasil dipindahkan ke cabang '.$cabang->nama);
    }
}
