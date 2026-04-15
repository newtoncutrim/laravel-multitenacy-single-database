<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CrudRoutesTest extends TestCase
{
    public function test_api_v1_crud_routes_are_registered_for_each_resource(): void
    {
        foreach ($this->resources() as $resource) {
            foreach (['index', 'store', 'show', 'update', 'destroy'] as $action) {
                $routeName = "api.v1.{$resource}.{$action}";

                $this->assertNotNull(
                    Route::getRoutes()->getByName($routeName),
                    "{$routeName} route was not registered."
                );
            }
        }
    }

    /**
     * @return list<string>
     */
    private function resources(): array
    {
        return [
            'appointments',
            'audit-logs',
            'branches',
            'brands',
            'clients',
            'communication-messages',
            'documents',
            'financial-accounts',
            'financial-transactions',
            'hospitalizations',
            'integrations',
            'inventory-locations',
            'inventory-movements',
            'medical-record-entries',
            'medical-records',
            'permissions',
            'pet-vaccines',
            'pets',
            'posts',
            'price-table-items',
            'price-tables',
            'products',
            'roles',
            'services',
            'subscription-plans',
            'suppliers',
            'tenant-subscriptions',
            'tenants',
            'users',
            'vaccines',
        ];
    }
}
