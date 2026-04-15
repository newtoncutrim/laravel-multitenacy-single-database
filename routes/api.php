<?php

use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\BranchController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\ClientController;
use App\Http\Controllers\Api\V1\CommunicationMessageController;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')
    ->prefix('v1')
    ->name('api.v1.')
    ->group(function () {
        Route::apiResources([
            'appointments' => AppointmentController::class,
            'audit-logs' => AuditLogController::class,
            'branches' => BranchController::class,
            'brands' => BrandController::class,
            'clients' => ClientController::class,
            'communication-messages' => CommunicationMessageController::class,
            'documents' => DocumentController::class,
            'financial-accounts' => FinancialAccountController::class,
            'financial-transactions' => FinancialTransactionController::class,
            'hospitalizations' => HospitalizationController::class,
            'integrations' => IntegrationController::class,
            'inventory-locations' => InventoryLocationController::class,
            'inventory-movements' => InventoryMovementController::class,
            'medical-record-entries' => MedicalRecordEntryController::class,
            'medical-records' => MedicalRecordController::class,
            'permissions' => PermissionController::class,
            'pet-vaccines' => PetVaccineController::class,
            'pets' => PetController::class,
            'posts' => PostController::class,
            'price-table-items' => PriceTableItemController::class,
            'price-tables' => PriceTableController::class,
            'products' => ProductController::class,
            'roles' => RoleController::class,
            'services' => ServiceController::class,
            'subscription-plans' => SubscriptionPlanController::class,
            'suppliers' => SupplierController::class,
            'tenant-subscriptions' => TenantSubscriptionController::class,
            'tenants' => TenantController::class,
            'users' => UserController::class,
            'vaccines' => VaccineController::class,
        ]);
    });
