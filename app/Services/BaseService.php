<?php

namespace App\Services;

use App\Interfaces\RepositoryInterface;
use App\Interfaces\ServiceInterface;

class BaseService implements ServiceInterface
{
    public function __construct(
        protected RepositoryInterface $repository
    ) {}

    public function getAll(array $filters = [])
    {
        if (!empty($filters)) {
            return $this->repository->findWhere($filters);
        }

        return $this->repository->all();
    }

    public function getPaginated(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function getById(int $id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
