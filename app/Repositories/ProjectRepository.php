<?php

namespace App\Repositories;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        protected Project $model
    ) {}

    public function getAll(): Collection
    {
        return $this->model
            ->with([
                'rootTasks.allChildren',
                'rootTasks.dependencies',
                'dependencies',
            ])
            ->orderBy('start_date')
            ->get();
    }

    public function findById(int $id): Project
    {
        return $this->model
            ->with([
                'rootTasks.allChildren',
                'rootTasks.dependencies',
                'dependencies',
                'dependents',
            ])
            ->findOrFail($id);
    }

    public function create(array $data): Project
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->model->findOrFail($id);
        $project->update($data);

        return $project->fresh([
            'rootTasks.allChildren',
            'dependencies',
        ]);
    }

    public function delete(int $id): bool
    {
        $project = $this->model->findOrFail($id);

        return $project->delete();
    }

    public function findConflictingSchedule(
        string $startDate,
        string $endDate,
        ?int $excludeId = null
    ): Collection {
        return $this->model
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
            })
            ->get();
    }

    public function addDependency(int $projectId, int $dependsOnProjectId): void
    {
        $project = $this->model->findOrFail($projectId);
        $project->dependencies()->syncWithoutDetaching([$dependsOnProjectId]);
    }

    public function removeDependency(int $projectId, int $dependsOnProjectId): void
    {
        $project = $this->model->findOrFail($projectId);
        $project->dependencies()->detach($dependsOnProjectId);
    }

    public function getDependencyIds(int $projectId): array
    {
        $project = $this->model->findOrFail($projectId);

        return $project->dependencies()->pluck('projects.id')->toArray();
    }
}