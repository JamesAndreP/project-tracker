<?php

namespace App\Interfaces;

use App\Models\Project;

interface ProjectRepositoryInterface
{
    public function all(): array;
    public function paginate(int $perPage = 20, int $page = 1, string $search = ''): array;
    public function find(int $id): ?Project;
    public function findWithTrashed(int $id): ?Project;
    public function onlyTrashed(): array;
    public function create(array $data): Project;
    public function update(int $id, array $data): ?Project;
    public function delete(int $id): bool;
    public function forceDelete(int $id): bool;
    public function restore(int $id): bool;

    public function getStats(): object;
}
