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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id')
            ->with('allChildren');
    }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'task_id',
            'depends_on_task_id'
        );
    }

    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            Task::class,
            'task_dependencies',
            'depends_on_task_id',
            'task_id'
        );
    }

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function allDependenciesDone(): bool
    {
        return $this->dependencies()
            ->where('status', '!=', 'done')
            ->doesntExist();
    }
}