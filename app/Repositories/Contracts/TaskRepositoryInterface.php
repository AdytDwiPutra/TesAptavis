<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    /**
     * @param int    $projectId
     * @param array  $filters   
     */
    public function getByProject(int $projectId, array $filters = []): Collection;

    public function findById(int $id): Task;

    public function create(array $data): Task;

    public function update(int $id, array $data): Task;

    public function delete(int $id): bool;

    public function addDependency(int $taskId, int $dependsOnTaskId): void;

    public function removeDependency(int $taskId, int $dependsOnTaskId): void;

    public function getAllByProject(int $projectId): Collection;

    public function getDependencyIds(int $taskId): array;
}