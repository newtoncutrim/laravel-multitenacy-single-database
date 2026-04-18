<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Segment;
use App\Models\Tenant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TenantProvisioningService
{
    public function provision(Tenant $tenant, Segment|string|null $segment = null): Tenant
    {
        $segment = $this->resolveSegment($segment);

        $attributes = [];

        if ($segment && $tenant->segment_id !== $segment->id) {
            $attributes['segment_id'] = $segment->id;
        }

        if (! $tenant->slug) {
            $attributes['slug'] = Str::slug($tenant->name).'-'.Str::lower(Str::random(6));
        }

        if ($attributes !== []) {
            $tenant->forceFill($attributes)->save();
        }

        $tenant->branding()->firstOrCreate([], [
            'primary_color' => '#2563eb',
            'secondary_color' => '#22a389',
            'accent_color' => '#16a34a',
        ]);

        $tenant->settings()->updateOrCreate(
            ['key' => 'locale'],
            ['value' => ['language' => 'pt-BR', 'timezone' => config('app.timezone')]]
        );

        $this->syncDefaultModules($tenant);

        return $tenant->refresh();
    }

    private function resolveSegment(Segment|string|null $segment): ?Segment
    {
        if ($segment instanceof Segment) {
            return $segment;
        }

        if (is_string($segment) && $segment !== '') {
            return Segment::query()->where('slug', $segment)->where('active', true)->first();
        }

        return Segment::query()->where('slug', 'veterinary')->where('active', true)->first()
            ?? Segment::query()->where('active', true)->first();
    }

    private function syncDefaultModules(Tenant $tenant): void
    {
        $segmentModules = $tenant->segment?->modules()
            ->wherePivot('enabled_by_default', true)
            ->where('modules.active', true)
            ->get() ?? collect();

        $coreModules = Module::query()
            ->where('scope', 'tenant')
            ->where('is_core', true)
            ->where('active', true)
            ->get();

        $modules = $coreModules->merge($segmentModules)->unique('id');

        foreach ($modules as $module) {
            $tenant->tenantModules()->updateOrCreate(
                ['module_id' => $module->id],
                [
                    'enabled' => true,
                    'config' => $module->pivot?->default_config ? json_decode($module->pivot->default_config, true) : null,
                    'enabled_at' => Carbon::now(),
                ]
            );
        }
    }
}
