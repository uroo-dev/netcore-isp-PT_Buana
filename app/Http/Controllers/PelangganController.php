<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\LogAktivitas;
use App\Models\PaketWifi;
use App\Models\Pelanggan;
use App\Models\PenanggungJawabWifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $individu = Pelanggan::individu()->with(['paket', 'penanggungJawab'])->orderBy('nama', 'asc')
            ->paginate(25, ['*'], 'individu_page')->onEachSide(2);
        $individu->appends(['tab' => 'individu', 'instansi_page' => $request->instansi_page, 'mitra_page' => $request->mitra_page]);

        $instansi = Pelanggan::instansi()->with(['paket', 'penanggungJawab'])->orderBy('nama', 'asc')
            ->paginate(25, ['*'], 'instansi_page')->onEachSide(2);
        $instansi->appends(['tab' => 'instansi', 'individu_page' => $request->individu_page, 'mitra_page' => $request->mitra_page]);

        $mitra = Pelanggan::mitra()->orderBy('nama', 'asc')
            ->paginate(25, ['*'], 'mitra_page')->onEachSide(2);
        $mitra->appends(['tab' => 'mitra', 'individu_page' => $request->individu_page, 'instansi_page' => $request->instansi_page]);

        $paketWifi = PaketWifi::orderBy('nama')->get();
        $penanggungJawab = PenanggungJawabWifi::orderBy('nama')->get();

        return view('pages.pelanggan.index', compact('individu', 'instansi', 'mitra', 'paketWifi', 'penanggungJawab'));
    }

    public function store(Request $request)
    {
        if ($request->has('harga_wifi_custom')) {
            $cleaned = preg_replace('/[^0-9]/', '', $request->harga_wifi_custom);
            $request->merge(['harga_wifi_custom' => $cleaned === '' ? null : $cleaned]);
        }
        $validated = $request->validate([
            'nama' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'koordinat' => ['nullable', 'string', 'max:100'],
            'ip_address' => ['nullable', 'string', 'max:50'],
            'tipe' => ['required', 'in:individu,instansi,mitra'],
            'paket_id' => ['nullable', 'exists:paket_wifi,id'],
            'paket_wifi_custom' => ['nullable', 'string', 'max:255'],
            'harga_wifi_custom' => ['nullable', 'numeric', 'min:0'],
            'penanggung_jawab_id' => ['nullable', 'exists:penanggung_jawab_wifi,id'],
        ], [
            'tipe.required' => 'Tipe pelanggan wajib dipilih.',
            'tipe.in' => 'Tipe pelanggan harus berupa individu, instansi, atau mitra.',
        ]);

        $pelanggan = Pelanggan::create($validated);

        LogAktivitas::catat('create', 'Pelanggan', $pelanggan->id, 'Menambah pelanggan: '.$validated['nama']);

        return redirect()->route('pelanggan.index', ['tab' => $request->tipe])->with('success', 'Pelanggan berhasil didaftarkan');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['invoices.user', 'invoices.details.produk', 'paket', 'penanggungJawab'])->findOrFail($id);
        $penanggungJawab = PenanggungJawabWifi::orderBy('nama')->get();
        $paketWifi = PaketWifi::orderBy('nama')->get();

        $invAgg = $pelanggan->invoices()
            ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status="lunas" THEN 1 ELSE 0 END) as lunas, SUM(CASE WHEN status="cicilan" THEN 1 ELSE 0 END) as cicilan, SUM(CASE WHEN status="cicilan" THEN sisa ELSE 0 END) as piutang, SUM(dibayar) as dibayar')
            ->first();
        $totalTagihan = $invAgg->total ?? 0;
        $totalLunas = $invAgg->lunas ?? 0;
        $totalCicilan = $invAgg->cicilan ?? 0;
        $totalPiutang = $invAgg->piutang ?? 0;
        $totalDibayar = $invAgg->dibayar ?? 0;

        return view('pages.pelanggan.show', compact('pelanggan', 'penanggungJawab', 'paketWifi', 'totalTagihan', 'totalLunas', 'totalCicilan', 'totalPiutang', 'totalDibayar'));
    }

    public function tagihan($id)
    {
        $pelanggan = Pelanggan::with(['paket', 'penanggungJawab'])->findOrFail($id);
        $invoices = Invoice::with(['details.produk', 'pembayaran.user'])
            ->where('pelanggan_id', $id)
            ->latest()
            ->paginate(20);

        return view('pages.pelanggan.tagihan', compact('pelanggan', 'invoices'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if ($request->has('harga_wifi_custom')) {
            $cleaned = preg_replace('/[^0-9]/', '', $request->harga_wifi_custom);
            $request->merge(['harga_wifi_custom' => $cleaned === '' ? null : $cleaned]);
        }
        $validated = $request->validate([
            'nama' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'koordinat' => ['nullable', 'string', 'max:100'],
            'ip_address' => ['nullable', 'string', 'max:50'],
            'tipe' => ['required', 'in:individu,instansi,mitra'],
            'paket_id' => ['nullable', 'exists:paket_wifi,id'],
            'paket_wifi_custom' => ['nullable', 'string', 'max:255'],
            'harga_wifi_custom' => ['nullable', 'numeric', 'min:0'],
            'penanggung_jawab_id' => ['nullable', 'exists:penanggung_jawab_wifi,id'],
        ]);

        $paketLamaId = $pelanggan->paket_id;
        $paketLamaNama = $pelanggan->paket->nama ?? '-';
        $pelanggan->update($validated);

        if ($request->paket_id && $request->paket_id != $paketLamaId) {
            $paketBaru = $pelanggan->paket->nama ?? '-';
            LogAktivitas::catat('update', 'Pelanggan', $id, 'Paket pelanggan '.$validated['nama'].' berubah: '.$paketLamaNama.' → '.$paketBaru);
        }

        LogAktivitas::catat('update', 'Pelanggan', $id, 'Mengubah data pelanggan: '.$validated['nama']);

        return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $nama = $pelanggan->nama;
            $pelanggan->delete();

            LogAktivitas::catat('delete', 'Pelanggan', $id, 'Menghapus pelanggan: '.$nama);

            return back()->with('success', 'Pelanggan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus pelanggan: '.$e->getMessage());

            return back()->with('error', 'Gagal menghapus data pelanggan.');
        }
    }

    public function toggleStatus($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if ($pelanggan->status == 'on') {
            $pelanggan->status = 'off';
            $pelanggan->tanggal_off = now();
            $msg = 'Pelanggan dinonaktifkan';
        } else {
            $pelanggan->status = 'on';
            $pelanggan->tanggal_off = null;
            $msg = 'Pelanggan diaktifkan';
        }

        $pelanggan->save();

        LogAktivitas::catat('update', 'Pelanggan', $id, $msg.': '.$pelanggan->nama);

        return redirect()->back()->with('success', $msg);
    }

    public function fetchTab(Request $request, $tipe)
    {
        if (! in_array($tipe, ['individu', 'instansi', 'mitra'])) {
            abort(404);
        }

        $q = trim($request->get('q', ''));
        $page = max(1, (int) $request->get('page', 1));

        $query = Pelanggan::where('tipe', $tipe)->orderBy('nama', 'asc');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', '%'.$q.'%')
                    ->orWhere('telepon', 'like', '%'.$q.'%')
                    ->orWhere('alamat', 'like', '%'.$q.'%');
            });
        }

        if ($tipe !== 'mitra') {
            $query->with(['paket', 'penanggungJawab']);
        }

        $items = $query->paginate(25, ['*'], 'page', $page)->onEachSide(2);
        $items->appends(['q' => $q, 'tab' => $tipe]);

        $rowsHtml = view('pages.pelanggan.partials.'.$tipe.'-rows', ['items' => $items])->render();
        $paginationHtml = $items->total() > 25 ? $items->links()->toHtml() : '';

        return response()->json([
            'rows' => $rowsHtml,
            'pagination' => $paginationHtml,
            'total' => $items->total(),
        ]);
    }

    public function search(Request $request)
    {
        $q = $request->q;
        $tipe = $request->tipe;
        $id = $request->id; // untuk pre-fill single pelanggan

        $query = Pelanggan::with('paket');

        if ($id) {
            $query->where('id', $id);
        } else {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama', 'like', '%'.$q.'%')
                    ->orWhere('telepon', 'like', '%'.$q.'%');
            })->limit(20);

            if ($tipe && in_array($tipe, ['individu', 'instansi', 'mitra'])) {
                $query->where('tipe', $tipe);
            }
        }

        $pelanggan = $query->get()->map(function ($p) {
            $paketNama = $p->tipe === 'mitra' ? $p->paket_wifi_custom : ($p->paket?->nama ?? '');
            $paketHarga = $p->tipe === 'mitra' ? ($p->harga_wifi_custom ?? 0) : ($p->paket?->harga ?? 0);

            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'tipe' => $p->tipe,
                'telepon' => $p->telepon,
                'status' => $p->status,
                'paket_nama' => $paketNama,
                'paket_harga' => $paketHarga,
                'text' => $p->nama.' ('.ucfirst($p->tipe).')'.($p->telepon ? ' - '.$p->telepon : ''),
            ];
        });

        return response()->json(['results' => $pelanggan]);
    }
}
