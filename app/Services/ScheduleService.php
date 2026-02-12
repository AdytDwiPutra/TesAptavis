<?php

namespace App\Services;

use App\Repositories\Contracts\ProjectRepositoryInterface;
use InvalidArgumentException;

class ScheduleService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepo,
    ) {}

    /**
     *
     * @param string   $startDate   Format: Y-m-d
     * @param string   $endDate     Format: Y-m-d
     * @param int|null $excludeId   
     */
    public function validateNoConflict(
        string $startDate,
        string $endDate,
        ?int $excludeId = null
    ): void {
        if ($startDate > $endDate) {
            throw new InvalidArgumentException(
                'start_date tidak boleh lebih besar dari end_date.'
            );
        }

        $conflicting = $this->projectRepo->findConflictingSchedule(
            $startDate,
            $endDate,
            $excludeId
        );

        if ($conflicting->isNotEmpty()) {
            $names = $conflicting->pluck('nama')->implode(', ');

            throw new InvalidArgumentException(
                "Jadwal beririsan dengan project: {$names}. " .
                "Silakan pilih rentang tanggal yang tidak tumpang tindih."
            );
        }
    }
}