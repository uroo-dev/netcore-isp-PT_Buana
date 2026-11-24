<?php

namespace App\Providers;

use App\Models\LogAktivitas;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Database\Events\ConnectionEstablished;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');

        Event::listen(ConnectionEstablished::class, function (ConnectionEstablished $event) {
            if ($event->connection->getDriverName() === 'sqlite') {
                $pdo = $event->connection->getPdo();
                $pdo->sqliteCreateFunction('YEAR', function ($date) {
                    if (! $date) {
                        return null;
                    }
                    $time = strtotime($date);

                    return $time !== false ? (int) date('Y', $time) : null;
                });
                $pdo->sqliteCreateFunction('MONTH', function ($date) {
                    if (! $date) {
                        return null;
                    }
                    $time = strtotime($date);

                    return $time !== false ? (int) date('n', $time) : null;
                });
            }
        });

        try {
            if (DB::getDriverName() === 'sqlite') {
                $pdo = DB::connection()->getPdo();
                $pdo->sqliteCreateFunction('YEAR', function ($date) {
                    if (! $date) {
                        return null;
                    }
                    $time = strtotime($date);

                    return $time !== false ? (int) date('Y', $time) : null;
                });
                $pdo->sqliteCreateFunction('MONTH', function ($date) {
                    if (! $date) {
                        return null;
                    }
                    $time = strtotime($date);

                    return $time !== false ? (int) date('n', $time) : null;
                });
            }
        } catch (\Throwable $e) {
        }

        try {
            if (Schema::hasTable('pengaturan')) {
                $p = Pengaturan::first();
                if ($p && $p->backup_email) {
                    Config::set('backup.notifications.mail.to', $p->backup_email);
                }
                if ($p && $p->backup_retention_days) {
                    Config::set('backup.cleanup.default_strategy.keep_all_backups_for_days', $p->backup_retention_days);
                }
            }
        } catch (\Exception $e) {
        }

        try {
            if (Schema::hasTable('log_aktivitas')) {
                $this->registerAuthLogs();
            }
        } catch (\Throwable $e) {
        }
    }

    protected function registerAuthLogs(): void
    {
        $catat = function ($aksi, $model, $modelId, $deskripsi) {
            try {
                LogAktivitas::create([
                    'user_id' => $modelId,
                    'aksi' => $aksi,
                    'tipe' => 'login',
                    'model' => 'Auth',
                    'model_id' => $modelId ? (string) $modelId : null,
                    'deskripsi' => $deskripsi,
                    'ip' => request()->ip(),
                ]);
            } catch (\Throwable $e) {
            }
        };

        try {
            Event::listen(Login::class, function (Login $event) use ($catat) {
                $catat('login', 'Auth', $event->user->id, 'Login: '.($event->user->username ?? $event->user->email));
            });

            Event::listen(Logout::class, function (Logout $event) use ($catat) {
                $catat('logout', 'Auth', $event->user?->id, 'Logout: '.($event->user->username ?? '-'));
            });

            Event::listen(Failed::class, function (Failed $event) use ($catat) {
                $catat('failed', 'Auth', null, 'Percobaan login gagal: '.($event->credentials['username'] ?? $event->credentials['email'] ?? '-'));
            });
        } catch (\Throwable $e) {
        }
    }
}
