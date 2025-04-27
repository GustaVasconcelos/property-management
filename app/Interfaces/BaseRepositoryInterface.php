<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    public function create(array $data): int;

    public function find(int $id): ?array;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function all(): array;

    public function findByField(string $field, $value): ?array;
}
