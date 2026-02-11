<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectDependencyController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskDependencyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index'])->name('home');

Route::prefix('projects')->group(function () {

    Route::get('/',         [ProjectController::class, 'list'])->name('projects.list');

    // CRUD
    Route::post('/',        [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/{id}',     [ProjectController::class, 'show'])->name('projects.show');
    Route::put('/{id}',     [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/{id}',  [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Project Dependencies
    Route::post('/{project}/dependencies',
        [ProjectDependencyController::class, 'store']
    )->name('projects.dependencies.store');

    Route::delete('/{project}/dependencies/{dependsOnProject}',
        [ProjectDependencyController::class, 'destroy']
    )->name('projects.dependencies.destroy');

    // Tasks yg ada di dalam project
    Route::get('/{project}/tasks',
        [TaskController::class, 'index']
    )->name('projects.tasks.index');

    Route::post('/{project}/tasks',
        [TaskController::class, 'store']
    )->name('projects.tasks.store');
});

Route::prefix('tasks')->group(function () {

    Route::get('/{id}',     [TaskController::class, 'show'])->name('tasks.show');
    Route::put('/{id}',     [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/{id}',  [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Task Dependencies
    Route::post('/{task}/dependencies',
        [TaskDependencyController::class, 'store']
    )->name('tasks.dependencies.store');

    Route::delete('/{task}/dependencies/{dependsOnTask}',
        [TaskDependencyController::class, 'destroy']
    )->name('tasks.dependencies.destroy');
});