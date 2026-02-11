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
        $data['status'] = 'draft';

        $task = $this->taskRepo->create($data);

        return $task;
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->taskRepo->findById($id);

        $updated = $this->taskRepo->update($id, $data);

        return $updated;
    }

    public function delete(int $id): bool
    {
        $task      = $this->taskRepo->findById($id);

        $result = $this->taskRepo->delete($id);

        return $result;
    }

}