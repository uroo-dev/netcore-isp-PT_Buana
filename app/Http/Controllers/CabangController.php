<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Kas;
use App\Models\LogAktivitas;
use App\Models\LogStok;
use App\Models\ProdukCabang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CabangController extends Controller
{
    public function index()
    {
        $cabang = Cabang::withCount('users')->paginate(25)->onEachSide(2);

        return view('pages.cabang.index', compact('cabang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'nama_lengkap' => 'required|string|max:100',
        ]);

        $cabang = Cabang::create([
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'telepon' => $validated['telepon'],
        ]);

        User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => 'cabang',
            'cabang_id' => $cabang->id,
        ]);

        LogAktivitas::catat('create', 'Cabang', $cabang->id, 'Menambah cabang: '.$cabang->nama);

        return redirect()->route('cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $cabang = Cabang::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        $cabang->update($validated);

        LogAktivitas::catat('update', 'Cabang', $cabang->id, 'Mengubah cabang: '.$cabang->nama);

        return redirect()->back()->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $cabang = Cabang::findOrFail($id);

        User::where('cabang_id', $cabang->id)->update(['cabang_id' => null, 'role' => 'pegawai']);

        LogAktivitas::catat('delete', 'Cabang', $cabang->id, 'Menghapus cabang: '.$cabang->nama);

        $cabang->delete();

        return redirect()->back()->with('success', 'Cabang berhasil dihapus.');
    }

    public function users($id)
    {
        $cabang = Cabang::with('users')->findOrFail($id);

        return response()->json($cabang->users);
    }

    public function produk($id)
    {
        $cabang = Cabang::findOrFail($id);
        $produkCabang = ProdukCabang::with('produk')->where('cabang_id', $id)->get();
        $totalProduk = $produkCabang->count();
        $totalStok = $produkCabang->sum('stok');

        return view('pages.cabang.produk', compact('cabang', 'produkCabang', 'totalProduk', 'totalStok'));
    }

    public function cetakLaporan(Request $request, $id)
    {
        $cabang = Cabang::findOrFail($id);

        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;

        // User yang terhubung ke cabang ini
        $userCabang = User::where('cabang_id', $id)->pluck('id');

        // PEMAKAIAN STOK (kas keluar kategori pemakaian oleh user cabang)
        $pemakaian = Kas::with('user')
            ->where('tipe', 'keluar')
            ->where('kategori', 'pemakaian')
            ->whereIn('user_id', $userCabang);

        // PENGELUARAN LAIN (kas keluar kategori non-pemakaian oleh user cabang)
        $pengeluaran = Kas::with('user')
            ->where('tipe', 'keluar')
            ->where('kategori', '!=', 'pemakaian')
            ->whereIn('user_id', $userCabang);

        // PEMINDAHAN STOK MASUK KE CABANG
        $pemindahan = LogStok::with('produk', 'user')
            ->where('cabang_id', $id)
            ->where('aksi', 'pindah');

        foreach ([['query' => $pemakaian, 'field' => 'tanggal'], ['query' => $pengeluaran, 'field' => 'tanggal'], ['query' => $pemindahan, 'field' => 'tanggal']] as $item) {
            $q = $item['query'];
            if ($tanggalMulai && $tanggalAkhir) {
                $q->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
            } elseif ($tanggalMulai) {
                $q->where('tanggal', '>=', $tanggalMulai);
            } elseif ($tanggalAkhir) {
                $q->where('tanggal', '<=', $tanggalAkhir);
            }
        }

        $pemakaian = $pemakaian->orderBy('tanggal')->orderBy('id')->get();
        $pengeluaran = $pengeluaran->orderBy('tanggal')->orderBy('id')->get();
        $pemindahan = $pemindahan->orderBy('tanggal')->orderBy('id')->get();

        $totalPemakaian = $pemakaian->sum('jumlah');
        $totalPengeluaran = $pengeluaran->sum('jumlah');
        $totalPemindahan = $pemindahan->sum('jumlah');

        return view('pages.cabang.cetak-laporan', compact(
            'cabang', 'pemakaian', 'pengeluaran', 'pemindahan',
            'tanggalMulai', 'tanggalAkhir',
            'totalPemakaian', 'totalPengeluaran', 'totalPemindahan'
        ));
    }
}
