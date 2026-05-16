<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->enum('frequency', ['weekly', 'monthly']);
            $table->string('day_of_week')->nullable();
            $table->unsignedTinyInteger('day_of_month')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('time');
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['course_id', 'is_active']);
            $table->index(['teacher_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
