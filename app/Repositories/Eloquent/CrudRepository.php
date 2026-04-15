<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\CrudRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class CrudRepository implements CrudRepositoryInterface
{
    /**
     * @param  class-string<Model>  $modelClass
     */
    public function __construct(private readonly string $modelClass)
    {
        if (! is_subclass_of($modelClass, Model::class)) {
            throw new InvalidArgumentException("{$modelClass} must extend ".Model::class);
        }
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->query()
            ->orderByDesc($this->newModel()->getKeyName())
            ->paginate($perPage);
    }

    public function all(): Collection
    {
        return $this->query()
            ->orderByDesc($this->newModel()->getKeyName())
            ->get();
    }

    public function findOrFail(int|string $id): Model
    {
        return $this->query()->findOrFail($id);
    }

    public function create(array $data): Model
    {
        $model = $this->newModel();
        $model->fill($data);
        $model->save();

        return $model->refresh();
    }

    public function update(Model $model, array $data): Model
    {
        $model->fill($data);
        $model->save();

        return $model->refresh();
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    private function query(): Builder
    {
        return $this->newModel()->newQuery();
    }

    private function newModel(): Model
    {
        return new $this->modelClass();
    }
}
