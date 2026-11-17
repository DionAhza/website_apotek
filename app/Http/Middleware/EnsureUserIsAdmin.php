<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses hanya untuk user dengan role "admin".
 * Pemeriksaan dilakukan di server, bukan hanya dengan menyembunyikan tombol.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isAdmin(), 403, 'Anda tidak memiliki akses ke halaman ini.');

        return $next($request);
    }
}
