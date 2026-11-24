<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\PaketWifi;
use App\Models\PenanggungJawabWifi;
use App\Models\Pengaturan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function index()
    {
        return redirect()->route('pengaturan.profil');
    }

    public function profil()
    {
        $user = auth()->user();

        return view('pages.pengaturan.profil', compact('user'));
    }

    public function perusahaan()
    {
        $pengaturan = Pengaturan::first();

        return view('pages.pengaturan.perusahaan', compact('pengaturan'));
    }

    public function paket()
    {
        $paketWifi = PaketWifi::orderBy('nama')->get();

        return view('pages.pengaturan.paket', compact('paketWifi'));
    }

    public function penanggungJawab()
    {
        $penanggungJawab = PenanggungJawabWifi::orderBy('nama')->get();

        return view('pages.pengaturan.penanggung-jawab', compact('penanggungJawab'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        $pengaturan = Pengaturan::first();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logo', 'public');
            $validated['logo'] = $path;
        }

        if ($pengaturan) {
            $pengaturan->update($validated);
        } else {
            Pengaturan::create($validated);
        }

        return redirect()->back()->with('success', 'Pengaturan perusahaan berhasil diperbarui');
    }

    public function updateProfil(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            'telepon' => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->username = $validated['username'];
        $user->telepon = $validated['telepon'] ?? $user->telepon;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }

    // PAKET WIFI
    public function storePaket(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'kecepatan' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        PaketWifi::create($validated);

        LogAktivitas::catat('create', 'PaketWifi', null, 'Menambah paket wifi: '.$validated['nama']);

        return redirect()->back()->with('success', 'Paket WiFi berhasil ditambahkan');
    }

    public function updatePaket(Request $request, $id)
    {
        $paket = PaketWifi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'kecepatan' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $paket->update($validated);

        LogAktivitas::catat('update', 'PaketWifi', $id, 'Mengubah paket wifi: '.$validated['nama']);

        return redirect()->back()->with('success', 'Paket WiFi berhasil diperbarui');
    }

    public function destroyPaket($id)
    {
        $paket = PaketWifi::findOrFail($id);

        $paket->pelanggan()->update(['paket_id' => null]);

        $paket->delete();

        LogAktivitas::catat('delete', 'PaketWifi', $id, 'Menghapus paket wifi: '.$paket->nama);

        return redirect()->back()->with('success', 'Paket WiFi berhasil dihapus');
    }

    // PENANGGUNG JAWAB WIFI
    public function storePenanggungJawab(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'wilayah' => 'nullable|string|max:100',
        ]);

        PenanggungJawabWifi::create($validated);

        LogAktivitas::catat('create', 'PenanggungJawabWifi', null, 'Menambah penanggung jawab: '.$validated['nama']);

        return redirect()->back()->with('success', 'Penanggung jawab WiFi berhasil ditambahkan');
    }

    public function updatePenanggungJawab(Request $request, $id)
    {
        $pj = PenanggungJawabWifi::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'wilayah' => 'nullable|string|max:100',
        ]);

        $pj->update($validated);

        LogAktivitas::catat('update', 'PenanggungJawabWifi', $id, 'Mengubah penanggung jawab: '.$validated['nama']);

        return redirect()->back()->with('success', 'Penanggung jawab WiFi berhasil diperbarui');
    }

    public function destroyPenanggungJawab($id)
    {
        $pj = PenanggungJawabWifi::findOrFail($id);
        $pj->delete();

        LogAktivitas::catat('delete', 'PenanggungJawabWifi', $id, 'Menghapus penanggung jawab: '.$pj->nama);

        return redirect()->back()->with('success', 'Penanggung jawab WiFi berhasil dihapus');
    }

    // LOG AKTIVITAS
    public function logAktivitas(Request $request)
    {
        return view('pages.pengaturan.log-aktivitas', $this->buildLogData($request));
    }

    public function fetchLogData(Request $request)
    {
        if (! $request->expectsJson()) {
            return redirect()->route('pengaturan.log');
        }

        $data = $this->buildLogData($request);

        return response()->json([
            'rows' => view('pages.pengaturan.partials.log-rows', $data)->render(),
            'pagination' => view('pages.pengaturan.partials.log-pagination', $data)->render(),
            'total' => $data['logs']->total(),
        ]);
    }

    private function buildLogData(Request $request)
    {
        $query = LogAktivitas::with('user')->latest('id');

        if ($request->tipe && $request->tipe !== 'semua') {
            $query->where('tipe', $request->tipe);
        }

        if ($request->aksi && $request->aksi !== 'semua') {
            $query->where('aksi', $request->aksi);
        }

        if ($request->user_id && $request->user_id !== 'semua') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->model && $request->model !== 'semua') {
            $query->where('model', $request->model);
        }

        if ($request->tanggal_mulai) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->tanggal_akhir) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        if ($request->q) {
            $q = $request->q;
            $query->where(function ($b) use ($q) {
                $b->where('deskripsi', 'like', '%'.$q.'%')
                    ->orWhere('model', 'like', '%'.$q.'%')
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('nama_lengkap', 'like', '%'.$q.'%')
                            ->orWhere('username', 'like', '%'.$q.'%');
                    });
            });
        }

        $logs = $query->paginate(25)->withQueryString();

        $users = User::orderBy('nama_lengkap')->get();
        $models = LogAktivitas::distinct()->orderBy('model')->pluck('model');

        return compact('logs', 'users', 'models');
    }
}
