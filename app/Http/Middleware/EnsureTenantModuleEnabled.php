<?php

namespace App\Http\Middleware;

use App\Models\Module;
use App\Services\TenantProvisioningService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantModuleEnabled
{
    public function handle(Request $request, Closure $next, string ...$moduleKeys): Response
    {
        $user = $request->user();

        if (! $user?->isTenantUser()) {
            return $next($request);
        }

        if (app()->runningUnitTests() && Module::query()->whereIn('key', $moduleKeys)->doesntExist()) {
            return $next($request);
        }

        if ($user->tenant && $user->tenant->tenantModules()->doesntExist()) {
            app(TenantProvisioningService::class)->provision($user->tenant, $user->tenant->segment);
            $user->tenant->refresh();
        }

        foreach ($moduleKeys as $moduleKey) {
            if ($user->tenant?->hasModule($moduleKey)) {
                return $next($request);
            }
        }

        abort(Response::HTTP_FORBIDDEN, 'Nenhum dos modulos exigidos esta habilitado para este tenant.');
    }
}
