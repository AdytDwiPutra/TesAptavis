<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
    ) {}

    public function index(): View
    {
        return view('projects.index');
    }

    public function list(): JsonResponse
    {
        $projects = $this->projectService->getAll();

        return response()->json([
            'success' => true,
            'data'    => $projects,
        ]);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $project = $this->projectService->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Project berhasil dibuat.',
                'data'    => $project->load(['dependencies', 'rootTasks']),
            ], 201);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $project = $this->projectService->findById($id);

            return response()->json([
                'success' => true,
                'data'    => $project,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Project tidak ditemukan.',
            ], 404);
        }
    }

    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        try {
            $project = $this->projectService->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Project berhasil diperbarui.',
                'data'    => $project,
            ]);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->projectService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Project berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus project.',
            ], 500);
        }
    }
}