<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\Repeater;
use App\Models\RepeaterPelanggan;
use Illuminate\Http\Request;

class RepeaterController extends Controller
{
    const PPN_RATE = 11;

    public function index()
    {
        $repeaters = Repeater::withCount('pelanggan')->latest()->limit(500)->get();

        return view('pages.repeater.index', compact('repeaters'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);

        $repeater = Repeater::create($request->only('nama'));

        LogAktivitas::catat('create', 'Repeater', $repeater->id, 'Menambah repeater: '.$repeater->nama);

        return redirect()->route('repeater.show', $repeater->id)->with('success', 'Repeater berhasil ditambah');
    }

    public function show($id)
    {
        $r = Repeater::findOrFail($id);

        $bulanList = RepeaterPelanggan::selectRaw('bulan, tahun, COUNT(*) as total_pelanggan, SUM(harga) as total_pendapatan, SUM(ppn) as total_ppn, SUM(bhp_uso) as total_bhp_uso')
            ->where('repeater_id', $id)
            ->groupBy('bulan', 'tahun')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        return view('pages.repeater.show', compact('r', 'bulanList'));
    }

    public function destroy($id)
    {
        $r = Repeater::findOrFail($id);
        LogAktivitas::catat('delete', 'Repeater', $id, 'Menghapus repeater: '.$r->nama);
        $r->delete();

        return redirect()->route('repeater.index')->with('success', 'Repeater berhasil dihapus');
    }

    public function createBulan($id)
    {
        $r = Repeater::findOrFail($id);

        return view('pages.repeater.create-bulan', compact('r'));
    }

    public function storeBulan(Request $request, $id)
    {
        $r = Repeater::findOrFail($id);

        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'pelanggan' => 'required|array|min:1',
            'pelanggan.*.nama' => 'required|string|max:100',
            'pelanggan.*.paket' => 'nullable|string|max:100',
            'pelanggan.*.harga' => 'required|numeric|min:0',
        ]);

        $now = now();
        $inserts = [];
        foreach ($request->pelanggan as $p) {
            $harga = (float) $p['harga'];
            $dpp = round($harga / (1 + self::PPN_RATE / 100), 2);
            $ppn = round($harga - $dpp, 2);
            $bhp = round($dpp * 1.75 / 100, 2);

            $inserts[] = [
                'repeater_id' => $id,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
                'nama_pelanggan' => $p['nama'],
                'paket' => $p['paket'] ?? null,
                'harga' => $harga,
                'dpp' => $dpp,
                'ppn' => $ppn,
                'bhp_uso' => $bhp,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        RepeaterPelanggan::insert($inserts);

        LogAktivitas::catat('create', 'Repeater', $id, 'Menambah data bulan '.$request->bulan.'/'.$request->tahun.' di '.$r->nama);

        return redirect()->route('repeater.show-bulan', [$id, $request->bulan, $request->tahun])
            ->with('success', 'Data berhasil disimpan');
    }

    public function showBulan($id, $bulan, $tahun)
    {
        $r = Repeater::findOrFail($id);

        $data = RepeaterPelanggan::where('repeater_id', $id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->orderBy('id')
            ->get();

        $stats = [
            'total_pelanggan' => $data->count(),
            'total_pendapatan' => $data->sum('harga'),
            'total_ppn' => $data->sum('ppn'),
            'total_bhp_uso' => $data->sum('bhp_uso'),
        ];

        return view('pages.repeater.show-bulan', compact('r', 'data', 'bulan', 'tahun', 'stats'));
    }

    public function updatePelanggan(Request $request, $pelangganId)
    {
        $p = RepeaterPelanggan::findOrFail($pelangganId);

        $request->validate([
            'field' => 'required|in:nama_pelanggan,paket,harga',
            'value' => 'required',
        ]);

        $field = $request->field;
        $value = $request->value;

        if ($field === 'harga') {
            $value = (float) $value;
            $dpp = round($value / (1 + self::PPN_RATE / 100), 2);
            $ppn = round($value - $dpp, 2);
            $bhp = round($dpp * 1.75 / 100, 2);

            $p->update([
                'harga' => $value,
                'dpp' => $dpp,
                'ppn' => $ppn,
                'bhp_uso' => $bhp,
            ]);
        } else {
            $p->update([$field => $value]);
        }

        LogAktivitas::catat('update', 'Repeater', $p->repeater_id, 'Update '.$field.' pelanggan '.$p->nama_pelanggan);

        $fresh = $p->fresh();

        return response()->json([
            'success' => true,
            'dpp' => (float) $fresh->dpp,
            'ppn' => (float) $fresh->ppn,
            'bhp_uso' => (float) $fresh->bhp_uso,
            'harga' => (float) $fresh->harga,
        ]);
    }

    public function destroyPelanggan($pelangganId)
    {
        $p = RepeaterPelanggan::findOrFail($pelangganId);
        LogAktivitas::catat('delete', 'Repeater', $p->repeater_id, 'Hapus pelanggan '.$p->nama_pelanggan);
        $p->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function destroyBulan($id, $bulan, $tahun)
    {
        RepeaterPelanggan::where('repeater_id', $id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->delete();

        LogAktivitas::catat('delete', 'Repeater', $id, 'Hapus semua data bulan '.$bulan.'/'.$tahun);

        return redirect()->route('repeater.show', $id)->with('success', 'Data bulan berhasil dihapus');
    }
}
