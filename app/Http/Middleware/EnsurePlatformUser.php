<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isPlatformUser()) {
            abort(Response::HTTP_FORBIDDEN, 'Acesso restrito aos administradores da plataforma.');
        }

        return $next($request);
    }
}
