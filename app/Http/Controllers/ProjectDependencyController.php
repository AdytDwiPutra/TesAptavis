<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ProjectDependencyController extends Controller
{
    public function __construct(
        protected ProjectService $projectService,
    ) {}

    public function store(Request $request, int $projectId): JsonResponse
    {
        $request->validate([
            'depends_on_project_id' => ['required', 'integer', 'exists:projects,id'],
        ]);

        try {
            $this->projectService->addDependency(
                $projectId,
                $request->integer('depends_on_project_id')
            );

            $project = $this->projectService->findById($projectId);

            return response()->json([
                'success' => true,
                'message' => 'Dependency project berhasil ditambahkan.',
                'data'    => $project,
            ]);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function destroy(int $projectId, int $dependsOnProjectId): JsonResponse
    {
        try {
            $this->projectService->removeDependency($projectId, $dependsOnProjectId);

            return response()->json([
                'success' => true,
                'message' => 'Dependency project berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dependency.',
            ], 500);
        }
    }
}