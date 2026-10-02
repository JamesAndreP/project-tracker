<?php

namespace App\Repositories;

use App\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function all(): array
    {
        return Project::all()->toArray();
    }

    public function paginate(int $perPage = 20, int $page = 1, string $search = ''): array
    {
        $query = Project::query()->orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where('client_name', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        $result = $query->paginate($perPage, ['*'], 'page', $page);
        return [
            'data' => $result->items(),
            'current_page' => $result->currentPage(),
            'last_page' => $result->lastPage(),
            'per_page' => $result->perPage(),
            'total' => $result->total(),
        ];
    }

    public function find(int $id): ?Project
    {
        return Project::find($id);
    }

    public function findWithTrashed(int $id): ?Project
    {
        return Project::withTrashed()->find($id);
    }

    public function onlyTrashed(): array
    {
        return Project::onlyTrashed()->get()->toArray();
    }

    public function create(array $data): Project
    {
        return Project::create($data);
    }

    public function update(int $id, array $data): ?Project
    {
        $project = $this->find($id);
        if ($project) {
            $project->update($data);
            return $project->fresh();
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $project = $this->find($id);
        if ($project) {
            return $project->delete();
        }
        return false;
    }

    public function forceDelete(int $id): bool
    {
        $project = $this->findWithTrashed($id);
        if ($project) {
            return $project->forceDelete();
        }
        return false;
    }

    public function restore(int $id): bool
    {
        $project = $this->findWithTrashed($id);
        if ($project) {
            return $project->restore();
        }
        return false;
    }

    public function getStats(): object 
    {
        $stats = Project::query()
            ->selectRaw("
                COUNT(*) as total,
                COALESCE(SUM(status = 'In Progress'), 0) as in_progress,
                COALESCE(SUM(status = 'Completed'), 0) as completed
            ")
            ->first();

        return $stats;
    }
}
