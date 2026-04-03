<?php

namespace App\Repositories\Eloquent\Admin;

use App\Models\Role;
use App\Repositories\Interfaces\Admin\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(
        protected Role $model
    ) {}

    public function findIdByName(string $name): ?string
    {
        $id = $this->model->newQuery()->where('name', $name)->value('id');

        return $id !== null ? (string) $id : null;
    }
}
