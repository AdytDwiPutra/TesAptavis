<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TaskDependencyController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    public function store(Request $request, int $taskId): JsonResponse
    {
        $request->validate([
            'depends_on_task_id' => ['required', 'integer', 'exists:tasks,id'],
        ]);

        try {
            $this->taskService->addDependency(
                $taskId,
                $request->integer('depends_on_task_id')
            );

            $task = $this->taskService->findById($taskId);

            return response()->json([
                'success' => true,
                'message' => 'Dependency task berhasil ditambahkan.',
                'data'    => $task,
            ]);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function destroy(int $taskId, int $dependsOnTaskId): JsonResponse
    {
        try {
            $this->taskService->removeDependency($taskId, $dependsOnTaskId);

            return response()->json([
                'success' => true,
                'message' => 'Dependency task berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dependency.',
            ], 500);
        }
    }
}