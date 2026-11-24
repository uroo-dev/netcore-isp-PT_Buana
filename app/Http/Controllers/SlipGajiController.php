<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\LogAktivitas;
use App\Models\SlipGaji;
use App\Models\SlipGajiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SlipGajiController extends Controller
{
    public function index()
    {
        $slipGaji = SlipGaji::withCount('details')->latest()->paginate(25)->onEachSide(2);

        return view('pages.slip-gaji.index', compact('slipGaji'));
    }

    public function create()
    {
        $bulan = request('bulan', now()->format('Y-m'));
        $carbon = Carbon::parse($bulan.'-01');
        $tahun = $carbon->year;
        $bulanNum = $carbon->month;

        $tanggalAwal = $carbon->copy()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $carbon->copy()->endOfMonth()->format('Y-m-d');

        $karyawan = Karyawan::orderBy('nama_lengkap')->limit(500)->get();

        $allAbsensi = Absensi::selectRaw('karyawan_id, status, COUNT(*) as total')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->groupBy('karyawan_id', 'status')
            ->get()
            ->groupBy('karyawan_id');

        $data = [];
        foreach ($karyawan as $k) {
            $byStatus = $allAbsensi->get($k->id, collect())->keyBy('status');
            $data[] = [
                'karyawan' => $k,
                'total_hadir' => (int) ($byStatus['datang']->total ?? 0),
                'total_terlambat' => (int) ($byStatus['terlambat']->total ?? 0),
                'total_absen' => (int) ($byStatus['absen']->total ?? 0),
                'total_libur' => (int) ($byStatus['libur']->total ?? 0),
            ];
        }

        return view('pages.slip-gaji.create', compact('data', 'bulan', 'tahun', 'bulanNum'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'karyawan_id' => 'required|array',
            'karyawan_id.*' => 'exists:karyawans,id',
            'gaji_pokok' => 'required|array',
            'gaji_pokok.*' => 'numeric|min:0',
            'total_hadir' => 'required|array',
            'total_hadir.*' => 'integer|min:0',
            'total_terlambat' => 'nullable|array',
            'total_terlambat.*' => 'integer|min:0',
            'total_absen' => 'nullable|array',
            'total_absen.*' => 'integer|min:0',
        ]);

        $namaBulan = Carbon::create($validated['tahun'], $validated['bulan'], 1)->translatedFormat('F Y');
        $slipGaji = SlipGaji::create([
            'nama' => 'Slip Gaji '.$namaBulan,
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'total_gaji' => 0,
        ]);

        $now = now();
        $inserts = [];
        $totalSemua = 0;
        foreach ($validated['karyawan_id'] as $idx => $karyawanId) {
            $gp = (float) $validated['gaji_pokok'][$idx];
            $hadir = (int) ($validated['total_hadir'][$idx] ?? 0);
            $terlambat = (int) ($validated['total_terlambat'][$idx] ?? 0);
            $absen = (int) ($validated['total_absen'][$idx] ?? 0);

            $inserts[] = [
                'slip_gaji_id' => $slipGaji->id,
                'karyawan_id' => $karyawanId,
                'gaji_pokok' => $gp,
                'total_hadir' => $hadir,
                'total_terlambat' => $terlambat,
                'total_absen' => $absen,
                'total_gaji' => $gp,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $totalSemua += $gp;
        }

        if (! empty($inserts)) {
            SlipGajiDetail::insert($inserts);
        }

        $slipGaji->update(['total_gaji' => $totalSemua]);

        LogAktivitas::catat('create', 'SlipGaji', $slipGaji->id, 'Membuat slip gaji: '.$slipGaji->nama);

        return redirect()->route('slip-gaji.show', $slipGaji->id)->with('success', 'Slip gaji berhasil dibuat');
    }

    public function show($id)
    {
        $slipGaji = SlipGaji::with('details.karyawan')->findOrFail($id);

        return view('pages.slip-gaji.show', compact('slipGaji'));
    }

    public function updateDetail(Request $request, $id)
    {
        $detail = SlipGajiDetail::with('slipGaji', 'karyawan')->findOrFail($id);

        $validated = $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
            'total_hadir' => 'required|integer|min:0',
            'total_terlambat' => 'required|integer|min:0',
            'total_absen' => 'required|integer|min:0',
        ]);

        $detail->update([
            'gaji_pokok' => $validated['gaji_pokok'],
            'total_hadir' => $validated['total_hadir'],
            'total_terlambat' => $validated['total_terlambat'],
            'total_absen' => $validated['total_absen'],
            'total_gaji' => $validated['gaji_pokok'],
        ]);

        // Update total slip gaji
        $total = $detail->slipGaji->details()->sum('total_gaji');
        $detail->slipGaji->update(['total_gaji' => $total]);

        LogAktivitas::catat('update', 'SlipGaji', $detail->slipGaji->id, 'Mengubah gaji '.($detail->karyawan->nama_lengkap ?? '').' di '.$detail->slipGaji->nama);

        return redirect()->back()->with('success', 'Data gaji berhasil diperbarui');
    }

    public function destroy($id)
    {
        $slipGaji = SlipGaji::findOrFail($id);
        LogAktivitas::catat('delete', 'SlipGaji', $id, 'Menghapus slip gaji: '.$slipGaji->nama);
        $slipGaji->delete();

        return redirect()->route('slip-gaji.index')->with('success', 'Slip gaji berhasil dihapus');
    }

    public function destroyDetail($id)
    {
        $detail = SlipGajiDetail::with(['slipGaji', 'karyawan'])->findOrFail($id);
        $slipGaji = $detail->slipGaji;

        LogAktivitas::catat('delete', 'SlipGaji', $slipGaji->id, 'Menghapus '.$detail->karyawan->nama_lengkap.' dari '.$slipGaji->nama);

        $detail->delete();

        $total = $slipGaji->details()->sum('total_gaji');
        $slipGaji->update(['total_gaji' => $total]);

        return redirect()->back()->with('success', 'Data gaji berhasil dihapus');
    }

    public function cetakPdf($id)
    {
        $slipGaji = SlipGaji::with('details.karyawan')->findOrFail($id);

        $pdf = Pdf::loadView('pages.slip-gaji.pdf', compact('slipGaji'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Slip-Gaji-'.$slipGaji->nama.'.pdf');
    }
}
