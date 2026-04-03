<?php

namespace App\Repositories\Eloquent;

use App\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function find(int $id, array $columns = ['*'], array $relations = [])
    {
        return $this->model->with($relations)->findOrFail($id, $columns);
    }

    public function findByField(string $field, mixed $value, array $columns = ['*'])
    {
        return $this->model->where($field, $value)->get($columns);
    }

    public function findWhere(array $criteria, array $columns = ['*'])
    {
        return $this->model->where($criteria)->get($columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);

        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        return (bool) $this->model->destroy($id);
    }

    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }
}
