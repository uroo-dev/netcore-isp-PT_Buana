<?php

namespace App\Console\Commands;

use App\Mail\BackupSuccess;
use App\Models\Backup;
use App\Models\Pengaturan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Run database backup and record result';

    public function handle()
    {
        $this->info('Starting database backup...');

        $pengaturan = Pengaturan::first();
        if ($pengaturan) {
            if ($pengaturan->backup_email) {
                Config::set('backup.notifications.mail.to', $pengaturan->backup_email);
            }
            if ($pengaturan->backup_retention_days) {
                Config::set('backup.cleanup.default_strategy.keep_all_backups_for_days', $pengaturan->backup_retention_days);
            }
        }

        $exitCode = Artisan::call('backup:run', ['--only-db' => true]);
        $output = Artisan::output();
        $this->line($output);

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
                'backup_date' => now()->toDateString(),
                'retention_days' => $pengaturan->backup_retention_days ?? 30,
            ]);

            $this->info('Backup recorded: '.basename($backupFile));

            if ($pengaturan?->backup_email) {
                try {
                    Mail::to($pengaturan->backup_email)->send(new BackupSuccess($record));
                    $this->info('Email notification sent to: '.$pengaturan->backup_email);
                } catch (\Exception $e) {
                    $this->warn('Failed to send email: '.$e->getMessage());
                }
            }

            return self::SUCCESS;
        }

        $this->error('Backup failed (exit code: '.$exitCode.')');

        return self::FAILURE;
    }
}
