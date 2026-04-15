<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientPortalAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        abort(Response::HTTP_NOT_IMPLEMENTED, 'Portal do cliente final ainda nao possui autenticacao propria.');
    }
}
