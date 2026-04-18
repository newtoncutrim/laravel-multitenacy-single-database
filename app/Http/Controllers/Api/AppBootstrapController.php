<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppBootstrapController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user()->load(['roles.permissions', 'tenant.segment', 'tenant.branding']);
        $tenant = $user->tenant;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'area' => $this->area($user),
                    'home_path' => route($user->homeRoute(), absolute: false),
                    'roles' => $user->roles->pluck('slug')->values(),
                    'permissions' => $user->roles
                        ->flatMap(fn ($role) => $role->permissions->pluck('slug'))
                        ->unique()
                        ->values(),
                ],
                'tenant' => $tenant ? $this->tenantPayload($tenant) : null,
                'modules' => $this->modulesPayload($tenant),
                'navigation' => $this->navigationPayload($tenant),
            ],
        ]);
    }

    private function tenantPayload(Tenant $tenant): array
    {
        return [
            'id' => $tenant->id,
            'name' => $tenant->name,
            'slug' => $tenant->slug,
            'status' => $tenant->status,
            'segment' => $tenant->segment ? [
                'id' => $tenant->segment->id,
                'slug' => $tenant->segment->slug,
                'name' => $tenant->segment->name,
            ] : null,
            'branding' => $tenant->branding ? [
                'logo_path' => $tenant->branding->logo_path,
                'primary_color' => $tenant->branding->primary_color,
                'secondary_color' => $tenant->branding->secondary_color,
                'accent_color' => $tenant->branding->accent_color,
                'custom_domain' => $tenant->branding->custom_domain,
            ] : null,
        ];
    }

    private function modulesPayload(?Tenant $tenant): array
    {
        $modules = $tenant
            ? $tenant->enabledModules()->orderBy('category')->orderBy('name')->get()
            : Module::query()->where('scope', 'platform')->where('active', true)->orderBy('category')->orderBy('name')->get();

        return $modules
            ->map(fn (Module $module) => [
                'key' => $module->key,
                'name' => $module->name,
                'description' => $module->description,
                'scope' => $module->scope,
                'category' => $module->category,
                'is_core' => $module->is_core,
                'api_prefix' => $module->api_prefix,
                'navigation_label' => $module->navigation_label,
                'navigation_path' => $module->navigation_path,
                'icon' => $module->icon,
                'config' => $module->pivot?->config,
            ])
            ->values()
            ->all();
    }

    private function navigationPayload(?Tenant $tenant): array
    {
        return collect($this->modulesPayload($tenant))
            ->filter(fn (array $module) => $module['navigation_label'] && $module['navigation_path'])
            ->map(fn (array $module) => [
                'label' => $module['navigation_label'],
                'path' => $module['navigation_path'],
                'module' => $module['key'],
                'icon' => $module['icon'],
                'api_prefix' => $module['api_prefix'],
                'category' => $module['category'],
            ])
            ->values()
            ->all();
    }

    private function area($user): string
    {
        if ($user->isPlatformAdmin()) {
            return 'platform';
        }

        if ($user->isSupportUser()) {
            return 'support';
        }

        return 'clinic';
    }
}
