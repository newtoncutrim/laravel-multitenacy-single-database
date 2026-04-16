<?php

namespace Tests\Unit\Architecture;

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\CommunicationMessageController;
use App\Http\Controllers\Api\V1\CrudController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\FinancialAccountController;
use App\Http\Controllers\Api\V1\FinancialTransactionController;
use App\Http\Controllers\Api\V1\HospitalizationController;
use App\Http\Controllers\Api\V1\IntegrationController;
use App\Http\Controllers\Api\V1\InventoryLocationController;
use App\Http\Controllers\Api\V1\InventoryMovementController;
use App\Http\Controllers\Api\V1\MedicalRecordController;
use App\Http\Controllers\Api\V1\MedicalRecordEntryController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\PetController;
use App\Http\Controllers\Api\V1\PetVaccineController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\PriceTableController;
use App\Http\Controllers\Api\V1\PriceTableItemController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RoleController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SubscriptionPlanController;
use App\Http\Controllers\Api\V1\SupplierController;
use App\Http\Controllers\Api\V1\TenantController;
use App\Http\Controllers\Api\V1\TenantSubscriptionController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\VaccineController;
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
            'Appointment' => AppointmentController::class,
            'AuditLog' => AuditLogController::class,
            'Branch' => BranchController::class,
            'Brand' => BrandController::class,
            'Client' => ClientController::class,
            'CommunicationMessage' => CommunicationMessageController::class,
            'Document' => DocumentController::class,
            'FinancialAccount' => FinancialAccountController::class,
            'FinancialTransaction' => FinancialTransactionController::class,
            'Hospitalization' => HospitalizationController::class,
            'Integration' => IntegrationController::class,
            'InventoryLocation' => InventoryLocationController::class,
            'InventoryMovement' => InventoryMovementController::class,
            'MedicalRecord' => MedicalRecordController::class,
            'MedicalRecordEntry' => MedicalRecordEntryController::class,
            'Permission' => PermissionController::class,
            'Pet' => PetController::class,
            'PetVaccine' => PetVaccineController::class,
            'Post' => PostController::class,
            'PriceTable' => PriceTableController::class,
            'PriceTableItem' => PriceTableItemController::class,
            'Product' => ProductController::class,
            'Role' => RoleController::class,
            'Service' => ServiceController::class,
            'SubscriptionPlan' => SubscriptionPlanController::class,
            'Supplier' => SupplierController::class,
            'Tenant' => TenantController::class,
            'TenantSubscription' => TenantSubscriptionController::class,
            'User' => UserController::class,
            'Vaccine' => VaccineController::class,
        ];
    }
}
