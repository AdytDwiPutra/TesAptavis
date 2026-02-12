<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepo,
        protected TaskRepositoryInterface    $taskRepo,
        protected DependencyService          $dependencyService,
        protected ScheduleService            $scheduleService,
    ) {}

    public function getAll(): Collection
    {
        return $this->projectRepo->getAll();
    }

    public function findById(int $id): Project
    {
        return $this->projectRepo->findById($id);
    }

    public function create(array $data): Project
    {
        // 1. Validasi jadwal tidak beririsan
        $this->scheduleService->validateNoConflict(
            $data['start_date'],
            $data['end_date']
        );

        // 2. Status dan progress selalu dimulai dari default
        $data['status']               = 'draft';
        $data['completion_progress']  = 0.00;

        return $this->projectRepo->create($data);
    }

    public function update(int $id, array $data): Project
    {
        // 1. Validasi jadwal tidak beririsan (kecualikan project itu sendiri)
        if (isset($data['start_date'], $data['end_date'])) {
            $this->scheduleService->validateNoConflict(
                $data['start_date'],
                $data['end_date'],
                excludeId: $id
            );
        }

        // 2. Status dan progress tidak boleh diubah manual via update biasa
        unset($data['status'], $data['completion_progress']);

        return $this->projectRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->projectRepo->delete($id);
    }

    public function recalculate(int $projectId): Project
    {
        $tasks = $this->taskRepo->getAllByProject($projectId);

        $status   = $this->calculateStatus($tasks);
        $progress = $this->calculateProgress($tasks);

        return $this->projectRepo->update($projectId, [
            'status'               => $status,
            'completion_progress'  => $progress,
        ]);
    }

    private function calculateStatus(Collection $tasks): string
    {
        if ($tasks->isEmpty()) {
            return 'draft';
        }

        if ($tasks->every(fn($t) => $t->status === 'done')) {
            return 'done';
        }

        if ($tasks->contains(fn($t) => $t->status === 'in_progress')) {
            return 'in_progress';
        }

        return 'draft';
    }

    private function calculateProgress(Collection $tasks): float
    {
        if ($tasks->isEmpty()) {
            return 0.00;
        }

        $totalBobot = $tasks->sum('bobot');

        if ($totalBobot === 0) {
            return 0.00;
        }

        $bobotDone = $tasks
            ->filter(fn($t) => $t->status === 'done')
            ->sum('bobot');

        return round(($bobotDone / $totalBobot) * 100, 2);
    }

    public function addDependency(int $projectId, int $dependsOnProjectId): void
    {
        // 1. Validasi circular
        $this->dependencyService->validateProjectDependency($projectId, $dependsOnProjectId);

        // 2. Simpan
        $this->projectRepo->addDependency($projectId, $dependsOnProjectId);
    }

    public function removeDependency(int $projectId, int $dependsOnProjectId): void
    {
        $this->projectRepo->removeDependency($projectId, $dependsOnProjectId);
    }

    public function validateProjectStatusTransition(int $projectId, string $newStatus): void
    {
        if (!in_array($newStatus, ['in_progress', 'done'])) {
            return;
        }

        $project = $this->projectRepo->findById($projectId);

        if (!$project->allDependenciesDone()) {
            throw new InvalidArgumentException(
                'Project tidak dapat berstatus "' . $newStatus . '" ' .
                'karena masih ada dependency project yang belum selesai (Done).'
            );
        }
    }
}