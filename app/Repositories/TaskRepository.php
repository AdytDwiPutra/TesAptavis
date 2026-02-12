<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        protected Task $model
    ) {}

    public function getByProject(int $projectId, array $filters = []): Collection
    {
        $query = $this->model
            ->with(['allChildren.dependencies', 'dependencies'])
            ->where('project_id', $projectId)
            ->whereNull('parent_id');

        $all = $query->get();

        // Filter
        if (!empty($filters['status']) || !empty($filters['search'])) {
            $all = $this->applyHierarchicalFilters($all, $filters);
        }

        return $all;
    }

    public function findById(int $id): Task
    {
        return $this->model
            ->with(['allChildren', 'dependencies', 'project', 'parent'])
            ->findOrFail($id);
    }

    public function create(array $data): Task
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->model->findOrFail($id);
        $task->update($data);

        return $task->fresh(['dependencies', 'children', 'project']);
    }

    public function delete(int $id): bool
    {
        $task = $this->model->findOrFail($id);

        return $task->delete();
    }

    public function addDependency(int $taskId, int $dependsOnTaskId): void
    {
        $task = $this->model->findOrFail($taskId);
        $task->dependencies()->syncWithoutDetaching([$dependsOnTaskId]);
    }

    public function removeDependency(int $taskId, int $dependsOnTaskId): void
    {
        $task = $this->model->findOrFail($taskId);
        $task->dependencies()->detach($dependsOnTaskId);
    }

    public function getAllByProject(int $projectId): Collection
    {
        return $this->model
            ->where('project_id', $projectId)
            ->whereNull('parent_id')
            ->get();
    }

    public function getDependencyIds(int $taskId): array
    {
        $task = $this->model->findOrFail($taskId);

        return $task->dependencies()->pluck('tasks.id')->toArray();
    }

    private function applyHierarchicalFilters(Collection $rootTasks, array $filters): Collection
    {
        return $rootTasks->filter(function (Task $task) use ($filters) {
            return $this->taskOrDescendantMatches($task, $filters);
        })->values();
    }

    private function taskOrDescendantMatches(Task $task, array $filters): bool
    {
        if ($this->taskMatchesFilters($task, $filters)) {
            // Filter children
            $task->setRelation(
                'allChildren',
                $this->filterChildrenRecursive($task->allChildren, $filters)
            );
            return true;
        }

        // Cek subtask-nya
        $matchingChildren = $this->filterChildrenRecursive($task->allChildren, $filters);

        if ($matchingChildren->isNotEmpty()) {
            $task->setRelation('allChildren', $matchingChildren);
            return true;
        }

        return false;
    }

    private function filterChildrenRecursive(Collection $children, array $filters): Collection
    {
        return $children->filter(function (Task $child) use ($filters) {
            return $this->taskOrDescendantMatches($child, $filters);
        })->values();
    }

    private function taskMatchesFilters(Task $task, array $filters): bool
    {
        // Filter by status
        if (!empty($filters['status'])) {
            if ($task->status !== $filters['status']) {
                return false;
            }
        }

        // Filter by search (nama)
        if (!empty($filters['search'])) {
            $keyword = strtolower($filters['search']);
            if (!str_contains(strtolower($task->nama), $keyword)) {
                return false;
            }
        }

        return true;
    }
}