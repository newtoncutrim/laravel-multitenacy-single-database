<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CrudRoutesTest extends TestCase
{
    public function test_clinic_api_v1_crud_routes_are_registered_for_each_resource(): void
    {
        foreach ($this->clinicResources() as $resource) {
            foreach (['index', 'store', 'show', 'update', 'destroy'] as $action) {
                $routeName = "api.clinic.v1.{$resource}.{$action}";

                $this->assertNotNull(
                    Route::getRoutes()->getByName($routeName),
                    "{$routeName} route was not registered."
                );
            }
        }
    }

    public function test_platform_api_v1_crud_routes_are_registered_for_each_resource(): void
    {
        foreach ($this->platformResources() as $resource) {
            foreach (['index', 'store', 'show', 'update', 'destroy'] as $action) {
                $routeName = "api.platform.v1.{$resource}.{$action}";

                $this->assertNotNull(
                    Route::getRoutes()->getByName($routeName),
                    "{$routeName} route was not registered."
                );
            }
        }
    }

    public function test_portal_api_status_route_is_registered(): void
    {
        $this->assertNotNull(Route::getRoutes()->getByName('api.portal.v1.status'));
    }

    /**
     * @return list<string>
     */
    private function clinicResources(): array
    {
        return [
            'appointments',
            'branches',
            'brands',
            'clients',
            'communication-messages',
            'documents',
            'financial-accounts',
            'financial-transactions',
            'hospitalizations',
            'inventory-locations',
            'inventory-movements',
            'medical-record-entries',
            'medical-records',
            'pet-vaccines',
            'pets',
            'posts',
            'price-table-items',
            'price-tables',
            'products',
            'services',
            'suppliers',
            'vaccines',
        ];
    }

    /**
     * @return list<string>
     */
    private function platformResources(): array
    {
        return [
            'audit-logs',
            'integrations',
            'permissions',
            'roles',
            'subscription-plans',
            'tenant-subscriptions',
            'tenants',
            'users',
        ];
    }
}
