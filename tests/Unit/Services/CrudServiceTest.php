<?php

namespace Tests\Unit\Services;

use App\Repositories\Contracts\CrudRepositoryInterface;
use App\Services\CrudService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

class CrudServiceTest extends TestCase
{
    public function test_it_delegates_crud_operations_to_the_repository(): void
    {
        $model = new class extends Model
        {
            protected $guarded = [];
        };

        $repository = new class($model) implements CrudRepositoryInterface
        {
            public array $createdData = [];

            public array $updatedData = [];

            public bool $deleted = false;

            public function __construct(private readonly Model $model) {}

            public function paginate(int $perPage = 15): LengthAwarePaginator
            {
                throw new \BadMethodCallException('Not used in this test.');
            }

            public function all(): Collection
            {
                throw new \BadMethodCallException('Not used in this test.');
            }

            public function findOrFail(int|string $id): Model
            {
                return $this->model;
            }

            public function create(array $data): Model
            {
                $this->createdData = $data;

                return $this->model;
            }

            public function update(Model $model, array $data): Model
            {
                $this->updatedData = $data;

                return $model;
            }

            public function delete(Model $model): bool
            {
                $this->deleted = true;

                return true;
            }
        };

        $service = new CrudService($repository);

        $this->assertSame($model, $service->findOrFail(1));
        $this->assertSame($model, $service->create(['name' => 'Clinica Central']));
        $this->assertSame(['name' => 'Clinica Central'], $repository->createdData);
        $this->assertSame($model, $service->update($model, ['name' => 'Clinica Norte']));
        $this->assertSame(['name' => 'Clinica Norte'], $repository->updatedData);
        $this->assertTrue($service->delete($model));
        $this->assertTrue($repository->deleted);
    }
}
