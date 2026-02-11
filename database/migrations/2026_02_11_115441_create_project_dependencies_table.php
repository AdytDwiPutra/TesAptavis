<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();
            $table->foreignId('depends_on_project_id')
                ->constrained('projects')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['project_id', 'depends_on_project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_dependencies');
    }
};