<?php

namespace App\Repositories\Contracts;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

interface ProjectRepositoryInterface
{
    public function getAll(): Collection;

    public function findById(int $id): Project;

    public function create(array $data): Project;

    public function update(int $id, array $data): Project;

    public function delete(int $id): bool;

    /**
     * @param string $startDate
     * @param string $endDate
     * @param int|null $excludeId
     * @return Collection
     */
    public function findConflictingSchedule(
        string $startDate,
        string $endDate,
        ?int $excludeId = null
    ): Collection;

    public function addDependency(int $projectId, int $dependsOnProjectId): void;

    public function removeDependency(int $projectId, int $dependsOnProjectId): void;

    public function getDependencyIds(int $projectId): array;
}