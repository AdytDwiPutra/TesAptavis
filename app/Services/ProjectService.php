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

        $data['status']               = 'draft';
        $data['completion_progress']  = 0.00;

        return $this->projectRepo->create($data);
    }

    public function update(int $id, array $data): Project
    {

        unset($data['status'], $data['completion_progress']);

        return $this->projectRepo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->projectRepo->delete($id);
    }

}