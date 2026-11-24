<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $karyawan = Karyawan::orderBy('nama_lengkap')->paginate(25)->onEachSide(2);
        $totalKaryawan = Karyawan::count();

        return view('pages.pegawai.index', compact('karyawan', 'totalKaryawan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $karyawan = Karyawan::create($validated);

        LogAktivitas::catat('create', 'Karyawan', $karyawan->id, 'Menambah karyawan: '.$validated['nama_lengkap']);

        return redirect()->route('pegawai.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
        ]);

        $karyawan->update($validated);

        LogAktivitas::catat('update', 'Karyawan', $id, 'Mengubah data karyawan: '.$validated['nama_lengkap']);

        return redirect()->back()->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        LogAktivitas::catat('delete', 'Karyawan', $id, 'Menghapus karyawan: '.$karyawan->nama_lengkap);

        $karyawan->delete();

        return redirect()->back()->with('success', 'Data karyawan berhasil dihapus');
    }
}
