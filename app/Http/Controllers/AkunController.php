<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $users = User::with('cabang')->orderBy('nama_lengkap')->paginate(25)->onEachSide(2);
        $totalAkun = User::count();
        $cabangList = Cabang::orderBy('nama')->get();

        return view('pages.akun.index', compact('users', 'totalAkun', 'cabangList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:owner,pegawai,cabang'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        LogAktivitas::catat('create', 'User', $user->id, 'Menambah akun: '.$validated['nama_lengkap'].' ('.$validated['role'].')');

        return redirect()->route('akun.index')->with('success', 'Akun berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username,'.$id],
            'telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string'],
            'role' => ['required', 'in:owner,pegawai,cabang'],
            'cabang_id' => ['nullable', 'exists:cabang,id'],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make(
                $request->password
            );
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        LogAktivitas::catat('update', 'User', $id, 'Mengubah akun: '.$validated['nama_lengkap']);

        return redirect()->back()->with('success', 'Akun berhasil diperbarui');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        LogAktivitas::catat('delete', 'User', $id, 'Menghapus akun: '.$user->nama_lengkap);
        $user->delete();

        return redirect()->back()->with('success', 'Akun berhasil dihapus');
    }
}
