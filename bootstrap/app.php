<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\LogRequest;
use App\Http\Middleware\SecurityHeaders;
use App\Models\LogAktivitas;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        $middleware->prepend(LogRequest::class);
        $middleware->append(SecurityHeaders::class);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (Throwable $e) {
            try {
                LogAktivitas::create([
                    'user_id' => auth()->id(),
                    'aksi' => 'error',
                    'tipe' => 'error',
                    'model' => class_basename($e),
                    'model_id' => null,
                    'deskripsi' => $e->getMessage(),
                    'data_lama' => null,
                    'data_baru' => [
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => substr($e->getTraceAsString(), 0, 2000),
                    ],
                    'ip' => request()->ip(),
                    'status_code' => 500,
                ]);
            } catch (Throwable $e2) {
            }
        });
    })->create();
