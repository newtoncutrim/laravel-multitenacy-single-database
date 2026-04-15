<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\CrudRepository;
use App\Services\CrudService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class CrudController extends Controller
{
    protected string $modelClass;

    private ?CrudService $service = null;

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 15), 1), 100);

        return response()->json($this->service()->paginate($perPage));
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->service()->create($request->all()),
        ], Response::HTTP_CREATED);
    }

    public function show(int|string $id): JsonResponse
    {
        return response()->json([
            'data' => $this->service()->findOrFail($id),
        ]);
    }

    public function update(Request $request, int|string $id): JsonResponse
    {
        $model = $this->service()->findOrFail($id);

        return response()->json([
            'data' => $this->service()->update($model, $request->all()),
        ]);
    }

    public function destroy(int|string $id): Response
    {
        $model = $this->service()->findOrFail($id);

        $this->service()->delete($model);

        return response()->noContent();
    }

    protected function service(): CrudService
    {
        return $this->service ??= new CrudService(
            new CrudRepository($this->modelClass)
        );
    }
}
