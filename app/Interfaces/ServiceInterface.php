<?php

namespace App\Interfaces;

interface ServiceInterface
{
    public function getAll(array $filters = []);

    public function getById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;
}
