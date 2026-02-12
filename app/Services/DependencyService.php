<?php

namespace App\Services;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use InvalidArgumentException;

class DependencyService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepo,
        protected TaskRepositoryInterface $taskRepo,
    ) {}

    /**
     * @param int $projectId         
     * @param int $dependsOnId       
     */
    public function validateProjectDependency(int $projectId, int $dependsOnId): void
    {
        if ($projectId === $dependsOnId) {
            throw new InvalidArgumentException(
                'Project tidak dapat bergantung pada dirinya sendiri.'
            );
        }

        if ($this->hasProjectCycle($dependsOnId, $projectId, [])) {
            throw new InvalidArgumentException(
                'Circular dependency terdeteksi: menambah dependency ini akan membuat cycle.'
            );
        }
    }

    private function hasProjectCycle(int $currentId, int $targetId, array $visited): bool
    {
        if ($currentId === $targetId) {
            return true;
        }

        if (in_array($currentId, $visited)) {
            return false;
        }

        $visited[] = $currentId;

        $dependencyIds = $this->projectRepo->getDependencyIds($currentId);

        foreach ($dependencyIds as $depId) {
            if ($this->hasProjectCycle($depId, $targetId, $visited)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $taskId            
     * @param int $dependsOnId       
     */
    public function validateTaskDependency(int $taskId, int $dependsOnId): void
    {
        if ($taskId === $dependsOnId) {
            throw new InvalidArgumentException(
                'Task tidak dapat bergantung pada dirinya sendiri.'
            );
        }

        if ($this->hasTaskCycle($dependsOnId, $taskId, [])) {
            throw new InvalidArgumentException(
                'Circular dependency terdeteksi: menambah dependency ini akan membuat cycle.'
            );
        }
    }

    private function hasTaskCycle(int $currentId, int $targetId, array $visited): bool
    {
        if ($currentId === $targetId) {
            return true;
        }

        if (in_array($currentId, $visited)) {
            return false;
        }

        $visited[] = $currentId;

        $dependencyIds = $this->taskRepo->getDependencyIds($currentId);

        foreach ($dependencyIds as $depId) {
            if ($this->hasTaskCycle($depId, $targetId, $visited)) {
                return true;
            }
        }

        return false;
    }
}