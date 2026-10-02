<?php

namespace App\Interfaces;

interface ProjectServiceInterface
{
    public function getAllProjects(): array;
    public function getPaginatedProjects(int $perPage = 20, int $page = 1, string $search = ''): array;
    public function getProject(int $id): ?array;
    public function createProject(array $data): array;
    public function updateProject(int $id, array $data): ?array;
    public function deleteProject(int $id): bool;
    public function forceDeleteProject(int $id): bool;
    public function restoreProject(int $id): bool;
    public function getStats(): object;
}
