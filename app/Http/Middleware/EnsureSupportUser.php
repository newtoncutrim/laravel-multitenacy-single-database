<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSupportUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isPlatformAdmin() && ! $request->user()?->isSupportUser()) {
            abort(Response::HTTP_FORBIDDEN, 'Acesso restrito aos usuarios de suporte.');
        }

        return $next($request);
    }
}
