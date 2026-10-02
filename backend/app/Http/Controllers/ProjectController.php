<?php

namespace App\Http\Controllers;

use App\Interfaces\ProjectServiceInterface;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectServiceInterface $projectService;

    public function __construct(ProjectServiceInterface $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        try {
            $projects = $this->projectService->getAllProjects();
            return response()->json($projects);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function indexPaginated(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 20);
            $page = $request->input('page', 1);
            $search = $request->input('search') ?? '';
            $projects = $this->projectService->getPaginatedProjects($perPage, $page, $search);
            return response()->json($projects);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve projects',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $project = $this->projectService->getProject($id);
            if (!$project) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            return response()->json($project);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:Planning,In Progress,On Hold,Completed',
            'priority' => 'in:Low,Medium,High',
            'start_date' => 'date',
            'due_date' => 'date|after_or_equal:start_date',
        ]);

        try {
            $project = $this->projectService->createProject($validated);
            return response()->json($project, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'client_name' => 'sometimes|required|string|max:255',
            'project_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:Planning,In Progress,On Hold,Completed',
            'priority' => 'in:Low,Medium,High',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $project = $this->projectService->updateProject($id, $validated);
            if (!$project) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            return response()->json($project);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $deleted = $this->projectService->deleteProject($id);
            if (!$deleted) {
                return response()->json([
                    'message' => 'Project not found',
                ], 404);
            }
            return response()->json([
                'message' => 'Project deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete project',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getStats()
    {
        try {
            $stats = $this->projectService->getStats();
            $stats->total = (int) $stats->total ?? 0;
            $stats->in_progress = (int) $stats->in_progress ?? 0;
            $stats->completed = (int) $stats->completed ?? 0;
            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve stats',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
