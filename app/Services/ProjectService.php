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

        $newStatus   = $this->calculateStatus($tasks);
        $newProgress = $this->calculateProgress($tasks);

        // Load project dengan dependencies untuk validasi
        $project = $this->projectRepo->findById($projectId);
        $oldStatus = $project->status;

        if ($newStatus !== $oldStatus) {
            if (in_array($newStatus, ['in_progress', 'done'])) {
                $this->validateProjectStatusTransition($projectId, $newStatus);
            }
        }

        $updated = $this->projectRepo->update($projectId, [
            'status'               => $newStatus,
            'completion_progress'  => $newProgress,
        ]);

        if ($newStatus !== $oldStatus) {
            $this->revalidateDependents($project);
        }

        return $updated;
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
        $this->recalculate($projectId);
    }

    public function validateProjectStatusTransition(int $projectId, string $newStatus): void
    {
        if (!in_array($newStatus, ['in_progress', 'done'])) {
            return;
        }

        $project = $this->projectRepo->findById($projectId);
        $project->loadMissing('dependencies');

        if (!$project->allDependenciesDone()) {
            $blockers = $project->dependencies
                ->filter(fn($d) => $d->status !== 'done')
                ->pluck('nama')
                ->implode(', ');

            throw new InvalidArgumentException(
                "Project \"{$project->nama}\" tidak dapat berstatus \"" . ucfirst(str_replace('_', ' ', $newStatus)) . "\". " .
                    "Project dependency berikut belum selesai: {$blockers}."
            );
        }
    }

    private function revalidateDependents(Project $project): void
    {
        $project->loadMissing('dependents');

        foreach ($project->dependents as $dependent) {
            // Jika dependent sudah in_progress/done tapi dependency-nya tidak lagi done
            if (in_array($dependent->status, ['in_progress', 'done']) && !$dependent->allDependenciesDone()) {

                // Downgrade ke draft karena dependency-nya tidak lagi Done
                $this->projectRepo->update($dependent->id, [
                    'status' => 'draft',
                ]);

                // Rekursif: cek dependents dari dependent ini juga
                $this->revalidateDependents($dependent->fresh());
            }
        }
    }
}
