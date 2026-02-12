<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepo,
        protected ProjectService          $projectService,
        protected DependencyService       $dependencyService,
    ) {}

    public function getByProject(int $projectId, array $filters = []): Collection
    {
        return $this->taskRepo->getByProject($projectId, $filters);
    }

    public function findById(int $id): Task
    {
        return $this->taskRepo->findById($id);
    }

    public function create(array $data): Task
    {
        if (!empty($data['parent_id'])) {
            $this->validateBobotAgainstParent($data['parent_id'], $data['bobot'] ?? 1);
        }

        $data['status'] = 'draft';

        $task = $this->taskRepo->create($data);

        $this->projectService->recalculate($task->project_id);

        return $task;
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->taskRepo->findById($id);

        // Validasi bobot subtask tidak boleh melebihi bobot parent
        $parentId = $data['parent_id'] ?? $task->parent_id;
        $bobot    = $data['bobot']     ?? $task->bobot;
        if ($parentId) {
            $this->validateBobotAgainstParent($parentId, $bobot, $id);
        }

        // Jika ada perubahan status, lakukan validasi
        if (isset($data['status']) && $data['status'] !== $task->status) {
            $this->validateStatusTransition($task, $data['status']);
        }

        $updated = $this->taskRepo->update($id, $data);

        $this->projectService->recalculate($task->project_id);

        // Jika status dependency task ini berubah, validasi ulang task yang bergantung padanya
        if (isset($data['status']) && $data['status'] !== $task->status) {
            $this->revalidateDependents($task);
        }

        return $updated;
    }

    public function delete(int $id): bool
    {
        $task      = $this->taskRepo->findById($id);
        $projectId = $task->project_id;

        $result = $this->taskRepo->delete($id);

        // Recalculate project setelah task dihapus
        $this->projectService->recalculate($projectId);

        return $result;
    }

    public function addDependency(int $taskId, int $dependsOnTaskId): void
    {
        // 1. Validasi circular dependency via DFS
        $this->dependencyService->validateTaskDependency($taskId, $dependsOnTaskId);

        // 2. Simpan
        $this->taskRepo->addDependency($taskId, $dependsOnTaskId);
    }

    public function removeDependency(int $taskId, int $dependsOnTaskId): void
    {
        $this->taskRepo->removeDependency($taskId, $dependsOnTaskId);
    }

    private function validateStatusTransition(Task $task, string $newStatus): void
    {
        if ($newStatus !== 'done') {
            return; // Hanya perpindahan ke Done yang ada constraint
        }

        // Cek task dependencies
        $task->loadMissing('dependencies');

        if (!$task->allDependenciesDone()) {
            $blockers = $task->dependencies
                ->filter(fn($d) => $d->status !== 'done')
                ->pluck('nama')
                ->implode(', ');

            throw new InvalidArgumentException(
                "Task tidak dapat berstatus Done. " .
                "Dependency berikut belum selesai: {$blockers}."
            );
        }

        // Cek subtask — parent tidak boleh Done jika masih ada subtask yang belum Done
        $task->loadMissing('children');

        if ($task->children->isNotEmpty()) {
            $notDone = $task->children->filter(fn($c) => $c->status !== 'done');

            if ($notDone->isNotEmpty()) {
                $blockers = $notDone->pluck('nama')->implode(', ');
                throw new InvalidArgumentException(
                    "Task tidak dapat berstatus Done. " .
                    "Subtask berikut belum selesai: {$blockers}."
                );
            }
        }

        // Cek project dependency (via ProjectService)
        $this->projectService->validateProjectStatusTransition(
            $task->project_id,
            'done'
        );
    }

    /**
     * @param int $parentId    
     * @param int $bobot      
     * @param int|null $selfId 
     */
    private function validateBobotAgainstParent(int $parentId, int $bobot, ?int $selfId = null): void
    {
        $parent = $this->taskRepo->findById($parentId);

        // Bobot subtask tidak boleh melebihi bobot parent
        if ($bobot > $parent->bobot) {
            throw new InvalidArgumentException(
                "Bobot subtask ({$bobot}) tidak boleh melebihi bobot parent task \"{$parent->nama}\" ({$parent->bobot})."
            );
        }

        // Total bobot semua subtask (kecuali diri sendiri jika update) tidak boleh melebihi bobot parent
        $siblings     = $parent->children ?? $parent->load('children')->children;
        $totalSibling = $siblings
            ->when($selfId, fn($c) => $c->where('id', '!=', $selfId))
            ->sum('bobot');

        $remaining = $parent->bobot - $totalSibling;

        // Kasus: bobot parent sudah habis terpakai subtask yang ada
        if ($remaining <= 0) {
            throw new InvalidArgumentException(
                "Tidak dapat menambahkan subtask baru. " .
                "Total bobot subtask pada \"{$parent->nama}\" sudah penuh " .
                "({$totalSibling}/{$parent->bobot})."
            );
        }

        // Kasus: bobot yang diminta melebihi sisa yang tersedia
        if (($totalSibling + $bobot) > $parent->bobot) {
            throw new InvalidArgumentException(
                "Bobot subtask ({$bobot}) melebihi sisa bobot yang tersedia pada \"{$parent->nama}\". " .
                "Sisa bobot: {$remaining} (total parent: {$parent->bobot}, terpakai: {$totalSibling})."
            );
        }
    }

    private function revalidateDependents(Task $task): void
    {
        $task->loadMissing('dependents');

        foreach ($task->dependents as $dependent) {
            if ($dependent->status === 'done' && !$dependent->allDependenciesDone()) {
                // Downgrade ke in_progress karena dependency-nya tidak lagi Done
                $this->taskRepo->update($dependent->id, ['status' => 'in_progress']);
                $this->projectService->recalculate($dependent->project_id);

                // Rekursif: cek dependents dari dependent ini juga
                $this->revalidateDependents($dependent->fresh());
            }
        }
    }
}