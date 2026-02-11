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

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function rootTasks(): HasMany
    {
        return $this->hasMany(Task::class)->whereNull('parent_id');
    }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_dependencies',
            'project_id',
            'depends_on_project_id'
        );
    }

    public function dependents(): BelongsToMany
    {
        return $this->belongsToMany(
            Project::class,
            'project_dependencies',
            'depends_on_project_id',
            'project_id'
        );
    }

    public function allDependenciesDone(): bool
    {
        return $this->dependencies()
            ->where('status', '!=', 'done')
            ->doesntExist();
    }
}