<?php

namespace App\Repositories\Interfaces\Admin;

interface RoleRepositoryInterface
{
    public function findIdByName(string $name): ?string;
}
