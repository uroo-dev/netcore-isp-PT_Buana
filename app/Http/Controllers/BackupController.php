<?php

namespace App\Http\Controllers;

use App\Mail\BackupSuccess;
use App\Models\Backup;
use App\Models\LogAktivitas;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        $pengaturan = Pengaturan::first();

        $backups = Backup::query()
            ->when($request->filled('date'), fn ($q) => $q->whereDate('backup_date', $request->date))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $diskUsed = Backup::sum('size');
        $diskUsedFormatted = $this->formatBytes($diskUsed);
        $backupCount = Backup::count();
        $lastBackup = Backup::latest()->first();

        return view('pages.pengaturan.backup', compact('pengaturan', 'backups', 'diskUsedFormatted', 'backupCount', 'lastBackup'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'backup_date' => 'nullable|date',
            'retention_days' => 'required|integer|min:0|max:365',
        ]);

        try {
            $exitCode = Artisan::call('backup:run', ['--only-db' => true]);
        } catch (\Throwable $e) {
            try {
                LogAktivitas::catat('create', 'Backup', null, 'Backup database gagal: '.$e->getMessage());
            } catch (\Exception $ignored) {
            }

            return redirect()->route('pengaturan.backup')->with('error', 'Backup gagal dijalankan: '.$e->getMessage());
        }

        $backupFile = collect(Storage::disk('backups')->allFiles())
            ->sortByDesc(fn ($f) => Storage::disk('backups')->lastModified($f))
            ->first();

        if ($exitCode === 0 && $backupFile) {
            $record = Backup::create([
                'filename' => basename($backupFile),
                'disk' => 'backups',
                'size' => Storage::disk('backups')->size($backupFile),
                'path' => $backupFile,
                'status' => 'success',
                'backup_date' => $data['backup_date'] ?? now()->toDateString(),
                'retention_days' => $data['retention_days'],
            ]);

            try {
                LogAktivitas::catat('create', 'Backup', null, 'Backup database: '.basename($backupFile));
            } catch (\Exception $e) {
            }

            $pengaturan = Pengaturan::first();
            if ($pengaturan?->backup_email) {
                try {
                    Mail::to($pengaturan->backup_email)->send(new BackupSuccess($record));
                } catch (\Exception $e) {
                }
            }

            return redirect()->route('pengaturan.backup')->with('success', 'Backup berhasil dijalankan.');
        }

        try {
            LogAktivitas::catat('create', 'Backup', null, 'Backup database gagal (exit code: '.$exitCode.')');
        } catch (\Exception $e) {
        }

        return redirect()->route('pengaturan.backup')->with('error', 'Backup gagal dijalankan.');
    }

    public function download(Backup $backup)
    {
        if (! Storage::disk('backups')->exists($backup->path)) {
            return redirect()->route('pengaturan.backup')->with('error', 'File backup tidak ditemukan.');
        }

        try {
            LogAktivitas::catat('read', 'Backup', $backup->id, 'Mengunduh backup: '.$backup->filename);
        } catch (\Exception $e) {
        }

        return Storage::disk('backups')->download($backup->path, $backup->filename);
    }

    public function destroy(Backup $backup)
    {
        if (Storage::disk('backups')->exists($backup->path)) {
            Storage::disk('backups')->delete($backup->path);
        }

        try {
            LogAktivitas::catat('delete', 'Backup', $backup->id, 'Menghapus backup: '.$backup->filename);
        } catch (\Exception $e) {
        }

        $backup->delete();

        return redirect()->route('pengaturan.backup')->with('success', 'Backup berhasil dihapus.');
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'backup_email' => 'nullable|email|max:255',
            'backup_retention_days' => 'required|integer|min:1|max:365',
            'backup_schedule_time' => 'required|date_format:H:i',
        ]);

        $pengaturan = Pengaturan::first();
        if ($pengaturan) {
            $pengaturan->update($validated);
        } else {
            Pengaturan::create($validated);
        }

        Config::set('backup.notifications.mail.to', $validated['backup_email']);
        Config::set('backup.cleanup.default_strategy.keep_all_backups_for_days', $validated['backup_retention_days']);

        try {
            LogAktivitas::catat('update', 'Pengaturan', null, 'Memperbarui pengaturan backup');
        } catch (\Exception $e) {
        }

        return redirect()->route('pengaturan.backup')->with('success', 'Pengaturan backup berhasil disimpan.');
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision).' '.$units[$pow];
    }
}
