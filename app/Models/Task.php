<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'nama',
        'status',
        'project_id',
        'parent_id',
        'bobot',
    ];

    protected $casts = [
        'bobot' => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Project yang memiliki task ini.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Task parent (null jika ini root task).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Child tasks (subtasks) dari task ini.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Recursive: semua descendant subtasks (eager loading).
     */
    public function allChildren(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id')
            ->with('allChildren');
    }

    /**
     * Task-task yang menjadi dependency dari task ini.
     * (task ini bergantung PADA task-task ini)
     */
    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'task_id',
            'depends_on_task_id'
        );
    }

    /**
     * Task-task yang bergantung PADA task ini.
     */
    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'depends_on_task_id',
            'task_id'
        );
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * Cek apakah task ini adalah root task (bukan subtask).
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Cek apakah semua dependency task sudah Done.
     */
    public function allDependenciesDone(): bool
    {
        return $this->dependencies()
            ->where('status', '!=', 'done')
            ->doesntExist();
    }
}