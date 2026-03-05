<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HanyaLokal — hanya izinkan akses jika APP_ENV=local.
 * Digunakan untuk route dev tools (system scan, dsb).
 */
class HanyaLokal
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('production', 'staging')) {
            abort(403, 'Akses ditolak. Hanya tersedia di environment lokal.');
        }

        return $next($request);
    }
}
