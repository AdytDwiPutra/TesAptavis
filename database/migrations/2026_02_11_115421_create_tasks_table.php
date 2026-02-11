<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('status', ['draft', 'in_progress', 'done'])->default('draft');
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->unsignedInteger('bobot')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};