<?php

namespace Tests\Unit\Architecture;

use App\Http\Controllers\Api\V1\CrudController;
use App\Repositories\Contracts\CrudRepositoryInterface;
use App\Repositories\Eloquent\CrudRepository;
use App\Services\CrudService;
use PHPUnit\Framework\TestCase;

class CrudLayerTest extends TestCase
{
    public function test_each_model_has_a_basic_api_controller(): void
    {
        foreach ($this->controllerMap() as $controllerClass) {
            $this->assertTrue(class_exists($controllerClass), "{$controllerClass} was not created.");
            $this->assertTrue(is_subclass_of($controllerClass, CrudController::class));
        }
    }

    public function test_service_and_repository_layers_exist(): void
    {
        $this->assertTrue(interface_exists(CrudRepositoryInterface::class));
        $this->assertTrue(class_exists(CrudRepository::class));
        $this->assertTrue(class_exists(CrudService::class));
    }

    /**
     * @return array<string, class-string>
     */
    private function controllerMap(): array
    {
        return [
            'Appointment' => \App\Http\Controllers\Api\V1\AppointmentController::class,
            'AuditLog' => \App\Http\Controllers\Api\V1\AuditLogController::class,
            'Branch' => \App\Http\Controllers\Api\V1\BranchController::class,
            'Brand' => \App\Http\Controllers\Api\V1\BrandController::class,
            'Client' => \App\Http\Controllers\Api\V1\ClientController::class,
            'CommunicationMessage' => \App\Http\Controllers\Api\V1\CommunicationMessageController::class,
            'Document' => \App\Http\Controllers\Api\V1\DocumentController::class,
            'FinancialAccount' => \App\Http\Controllers\Api\V1\FinancialAccountController::class,
            'FinancialTransaction' => \App\Http\Controllers\Api\V1\FinancialTransactionController::class,
            'Hospitalization' => \App\Http\Controllers\Api\V1\HospitalizationController::class,
            'Integration' => \App\Http\Controllers\Api\V1\IntegrationController::class,
            'InventoryLocation' => \App\Http\Controllers\Api\V1\InventoryLocationController::class,
            'InventoryMovement' => \App\Http\Controllers\Api\V1\InventoryMovementController::class,
            'MedicalRecord' => \App\Http\Controllers\Api\V1\MedicalRecordController::class,
            'MedicalRecordEntry' => \App\Http\Controllers\Api\V1\MedicalRecordEntryController::class,
            'Permission' => \App\Http\Controllers\Api\V1\PermissionController::class,
            'Pet' => \App\Http\Controllers\Api\V1\PetController::class,
            'PetVaccine' => \App\Http\Controllers\Api\V1\PetVaccineController::class,
            'Post' => \App\Http\Controllers\Api\V1\PostController::class,
            'PriceTable' => \App\Http\Controllers\Api\V1\PriceTableController::class,
            'PriceTableItem' => \App\Http\Controllers\Api\V1\PriceTableItemController::class,
            'Product' => \App\Http\Controllers\Api\V1\ProductController::class,
            'Role' => \App\Http\Controllers\Api\V1\RoleController::class,
            'Service' => \App\Http\Controllers\Api\V1\ServiceController::class,
            'SubscriptionPlan' => \App\Http\Controllers\Api\V1\SubscriptionPlanController::class,
            'Supplier' => \App\Http\Controllers\Api\V1\SupplierController::class,
            'Tenant' => \App\Http\Controllers\Api\V1\TenantController::class,
            'TenantSubscription' => \App\Http\Controllers\Api\V1\TenantSubscriptionController::class,
            'User' => \App\Http\Controllers\Api\V1\UserController::class,
            'Vaccine' => \App\Http\Controllers\Api\V1\VaccineController::class,
        ];
    }
}
