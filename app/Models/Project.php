<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = [
        'nama',
        'status',
        'completion_progress',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date'           => 'date',
        'end_date'             => 'date',
        'completion_progress'  => 'float',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Task-task langsung milik project ini (bukan subtask).
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Hanya root task (bukan subtask) milik project ini.
     */
    public function rootTasks(): HasMany
    {
        return $this->hasMany(Task::class)->whereNull('parent_id');
    }

    /**
     * Project-project yang menjadi dependency dari project ini.
     * (project ini bergantung PADA project-project ini)
     */
    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_dependencies',
            'project_id',
            'depends_on_project_id'
        );
    }

    /**
     * Project-project yang bergantung PADA project ini.
     * (dependents = yang butuh project ini selesai lebih dulu)
     */
    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_dependencies',
            'depends_on_project_id',
            'project_id'
        );
    }

    // ──────────────────────────────────────────────
    // Computed helpers (kalkulasi dilakukan di Service)
    // ──────────────────────────────────────────────

    /**
     * Cek apakah semua dependency project sudah Done.
     */
    public function allDependenciesDone(): bool
    {
        return $this->dependencies()
            ->where('status', '!=', 'done')
            ->doesntExist();
    }
}