<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\LogAktivitas;
use App\Models\Pengaturan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $bulan = request('bulan', now()->format('Y-m'));
        $carbon = Carbon::parse($bulan.'-01');
        $hariDalamBulan = $carbon->daysInMonth;
        $mulaiHari = $carbon->dayOfWeek;

        $tanggalAwal = $carbon->copy()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $carbon->copy()->endOfMonth()->format('Y-m-d');

        // Single query: ambil semua tanggal yang punya absensi bulan ini
        $tanggalWithAbsensi = Absensi::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->selectRaw('DISTINCT tanggal')
            ->pluck('tanggal')
            ->map(fn ($d) => $d instanceof Carbon ? $d->format('Y-m-d') : $d)
            ->toArray();

        $daftarTanggal = [];
        for ($i = 1; $i <= $hariDalamBulan; $i++) {
            $tgl = $carbon->copy()->day($i)->format('Y-m-d');
            $daftarTanggal[] = [
                'date' => $tgl,
                'day' => $i,
                'hasAbsensi' => in_array($tgl, $tanggalWithAbsensi),
                'isToday' => $tgl === now()->format('Y-m-d'),
                'isPast' => $tgl < now()->format('Y-m-d'),
            ];
        }

        $namaBulan = $carbon->translatedFormat('F Y');
        $karyawanList = Karyawan::orderBy('nama_lengkap')->get();

        return view('pages.absensi.index', compact(
            'daftarTanggal', 'mulaiHari', 'namaBulan', 'carbon', 'bulan', 'karyawanList'
        ));
    }

    public function form()
    {
        $tanggal = request('tanggal', now()->format('Y-m-d'));
        $karyawan = Karyawan::orderBy('nama_lengkap')->get();
        $absensiHariIni = Absensi::where('tanggal', $tanggal)
            ->get()
            ->keyBy('karyawan_id');

        return view('pages.absensi.form', compact('karyawan', 'absensiHariIni', 'tanggal'));
    }

    public function store(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        if ($request->has('absensi')) {
            $upsertData = [];
            $now = now();
            foreach ($request->absensi as $karyawanId => $data) {
                if (empty($data['status'])) {
                    continue;
                }
                $upsertData[] = [
                    'karyawan_id' => $karyawanId,
                    'tanggal' => $tanggal,
                    'user_id' => auth()->id(),
                    'status' => $data['status'] ?? 'datang',
                    'jam_masuk' => $data['jam_masuk'] ?? null,
                    'jam_pulang' => $data['jam_pulang'] ?? null,
                    'keterangan' => $data['keterangan'] ?? null,
                    'created_by' => auth()->id(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if (! empty($upsertData)) {
                Absensi::upsert($upsertData, ['karyawan_id', 'tanggal'], ['user_id', 'status', 'jam_masuk', 'jam_pulang', 'keterangan', 'created_by', 'updated_at']);
            }
        }

        LogAktivitas::catat('create', 'Absensi', null, 'Mencatat absensi tanggal '.Carbon::parse($tanggal)->format('d/m/Y'));

        return redirect()->route('absensi.form', ['tanggal' => $tanggal])->with('success', 'Absensi berhasil dicatat.');
    }

    public function riwayat(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $carbon = Carbon::parse($bulan.'-01');
        $hariDalamBulan = $carbon->daysInMonth;
        $mulaiHari = $carbon->dayOfWeek;

        $tanggalAwal = $carbon->copy()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $carbon->copy()->endOfMonth()->format('Y-m-d');

        $tanggalWithAbsensi = Absensi::whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->selectRaw('DISTINCT tanggal')
            ->pluck('tanggal')
            ->map(fn ($d) => $d instanceof Carbon ? $d->format('Y-m-d') : $d)
            ->toArray();

        $daftarTanggal = [];
        for ($i = 1; $i <= $hariDalamBulan; $i++) {
            $tgl = $carbon->copy()->day($i)->format('Y-m-d');
            $daftarTanggal[] = [
                'date' => $tgl,
                'day' => $i,
                'hasAbsensi' => in_array($tgl, $tanggalWithAbsensi),
                'isToday' => $tgl === now()->format('Y-m-d'),
                'isPast' => $tgl < now()->format('Y-m-d'),
            ];
        }

        $namaBulan = $carbon->translatedFormat('F Y');

        $detail = Absensi::with(['karyawan', 'creator'])
            ->where('tanggal', $tanggal)
            ->orderBy('karyawan_id', 'asc')
            ->get();

        return view('pages.absensi.riwayat', compact(
            'daftarTanggal', 'mulaiHari', 'namaBulan', 'carbon', 'bulan', 'tanggal', 'detail'
        ));
    }

    public function cetak(Request $request)
    {
        $dari = $request->input('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->format('Y-m-d'));

        $data = Absensi::with(['karyawan', 'creator'])
            ->whereBetween('tanggal', [$dari, $sampai])
            ->orderBy('tanggal', 'asc')
            ->orderBy('karyawan_id', 'asc')
            ->get();

        return view('pages.absensi.cetak', compact('data', 'dari', 'sampai'));
    }

    public function rekapPdf(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));
        $karyawanId = $request->input('karyawan_id');
        $carbon = Carbon::parse($bulan.'-01');
        $hariDalamBulan = $carbon->daysInMonth;
        $namaBulan = $carbon->translatedFormat('F Y');
        $tahun = $carbon->format('Y');

        $tanggalAwal = $carbon->copy()->startOfMonth()->format('Y-m-d');
        $tanggalAkhir = $carbon->copy()->endOfMonth()->format('Y-m-d');

        $queryKaryawan = Karyawan::orderBy('nama_lengkap');
        if ($karyawanId) {
            $queryKaryawan->where('id', $karyawanId);
        }
        $karyawan = $queryKaryawan->get();

        // Batch load semua absensi untuk semua karyawan dalam 1 query
        $karyawanIds = $karyawan->pluck('id')->toArray();
        $allAbsensi = Absensi::whereIn('karyawan_id', $karyawanIds)
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal')
            ->get()
            ->groupBy('karyawan_id');

        $rekap = [];
        foreach ($karyawan as $k) {
            $absensiBulan = $allAbsensi->get($k->id, collect());

            $detailHari = [];
            for ($d = 1; $d <= $hariDalamBulan; $d++) {
                $tgl = $carbon->copy()->day($d)->format('Y-m-d');
                $absen = $absensiBulan->firstWhere('tanggal', $tgl);
                $detailHari[] = [
                    'tanggal' => $tgl,
                    'hari' => Carbon::parse($tgl)->translatedFormat('l'),
                    'status' => $absen?->status ?? null,
                    'jam_masuk' => $absen?->jam_masuk ?? null,
                    'jam_pulang' => $absen?->jam_pulang ?? null,
                    'keterangan' => $absen?->keterangan ?? null,
                ];
            }

            $rekap[] = [
                'karyawan' => $k,
                'datang' => $absensiBulan->where('status', 'datang')->count(),
                'terlambat' => $absensiBulan->where('status', 'terlambat')->count(),
                'absen' => $absensiBulan->where('status', 'absen')->count(),
                'libur' => $absensiBulan->where('status', 'libur')->count(),
                'total' => $absensiBulan->count(),
                'detailHari' => $detailHari,
            ];
        }

        $karyawanDipilih = $karyawanId ? Karyawan::find($karyawanId) : null;
        $pengaturan = Pengaturan::first();

        $pdf = Pdf::loadView('pages.absensi.rekap_pdf', compact(
            'rekap', 'namaBulan', 'tahun', 'bulan', 'hariDalamBulan', 'carbon', 'pengaturan', 'karyawanDipilih'
        ));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Rekap-Absensi-'.$bulan.'.pdf');
    }
}
