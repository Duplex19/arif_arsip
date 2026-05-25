<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PimpinanMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isPimpinan()) {
            abort(403, 'Akses hanya untuk Pimpinan.');
        }

        return $next($request);
    }
}
