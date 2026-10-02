<?php

namespace App\Services;

use App\Interfaces\ProjectRepositoryInterface;
use App\Interfaces\ProjectServiceInterface;

class ProjectService implements ProjectServiceInterface
{
    protected ProjectRepositoryInterface $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getAllProjects(): array
    {
        return $this->projectRepository->all();
    }

    public function getPaginatedProjects(int $perPage = 20, int $page = 1, string $search = ''): array
    {
        return $this->projectRepository->paginate($perPage, $page, $search);
    }

    public function getProject(int $id): ?array
    {
        $project = $this->projectRepository->find($id);
        return $project ? $project->toArray() : null;
    }

    public function createProject(array $data): array
    {
        $project = $this->projectRepository->create($data);
        return $project->toArray();
    }

    public function updateProject(int $id, array $data): ?array
    {
        $project = $this->projectRepository->update($id, $data);
        return $project ? $project->toArray() : null;
    }

    public function deleteProject(int $id): bool
    {
        return $this->projectRepository->delete($id);
    }

    public function forceDeleteProject(int $id): bool
    {
        return $this->projectRepository->forceDelete($id);
    }

    public function restoreProject(int $id): bool
    {
        return $this->projectRepository->restore($id);
    }

    public function getStats(): object
    {
        return $this->projectRepository->getStats();
    }
}
