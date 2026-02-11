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

    public function getAllByProject(int $projectId): Collection
    {
        return $this->model
            ->where('project_id', $projectId)
            ->whereNull('parent_id')
            ->get();
    }
}