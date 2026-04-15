<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isTenantUser()) {
            abort(Response::HTTP_FORBIDDEN, 'Acesso restrito aos usuarios da clinica.');
        }

        return $next($request);
    }
}
