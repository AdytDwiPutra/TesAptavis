<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    /**
     * GET /projects/{project}/tasks — Daftar task dalam project.
     * Mendukung query params: ?status=done&search=keyword
     */
    public function index(Request $request, int $projectId): JsonResponse
    {
        $filters = $request->only(['status', 'search']);

        $tasks = $this->taskService->getByProject($projectId, $filters);

        return response()->json([
            'success' => true,
            'data'    => $tasks,
        ]);
    }

    /**
     * POST /projects/{project}/tasks — Buat task baru.
     */
    public function store(StoreTaskRequest $request, int $projectId): JsonResponse
    {
        try {
            $data = array_merge($request->validated(), ['project_id' => $projectId]);

            $task = $this->taskService->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dibuat.',
                'data'    => $task->load(['children', 'dependencies']),
            ], 201);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * GET /tasks/{id} — Detail satu task.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->findById($id);

            return response()->json([
                'success' => true,
                'data'    => $task,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task tidak ditemukan.',
            ], 404);
        }
    }

    /**
     * PUT /tasks/{id} — Update task (termasuk ubah status).
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil diperbarui.',
                'data'    => $task->load(['children', 'dependencies', 'project']),
            ]);

        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * DELETE /tasks/{id} — Hapus task.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->taskService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Task berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus task.',
            ], 500);
        }
    }
}