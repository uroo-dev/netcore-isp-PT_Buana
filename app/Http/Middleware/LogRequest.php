<?php

namespace App\Http\Middleware;

use App\Models\LogAktivitas;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $path = $request->path();

        if ($request->isMethod('OPTIONS')) {
            return $response;
        }

        if (str_starts_with($path, 'pengaturan/log-aktivitas') || $path === 'up') {
            return $response;
        }

        try {
            $user = auth()->user();

            LogAktivitas::create([
                'user_id' => $user?->id,
                'aksi' => 'access',
                'tipe' => 'request',
                'model' => 'Request',
                'model_id' => null,
                'deskripsi' => $request->method().' '.$path,
                'data_lama' => null,
                'data_baru' => [
                    'ip' => $request->ip(),
                    'status' => $response->getStatusCode(),
                    'referer' => $request->header('referer'),
                ],
                'ip' => $request->ip(),
                'status_code' => $response->getStatusCode(),
            ]);
        } catch (\Throwable $e) {
        }

        return $response;
    }
}
