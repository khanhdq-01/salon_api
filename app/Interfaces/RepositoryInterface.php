<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);

    public function paginate(int $perPage = 15, array $columns = ['*']);

    public function find(int $id, array $columns = ['*'], array $relations = []);

    public function findByField(string $field, mixed $value, array $columns = ['*']);

    public function findWhere(array $criteria, array $columns = ['*']);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;

    public function exists(int $id): bool;
}
