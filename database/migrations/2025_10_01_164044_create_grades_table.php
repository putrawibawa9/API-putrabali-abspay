<?php
// database/migrations/2025_10_01_000002_create_grades_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('students_courses_id')->constrained('students_courses')->cascadeOnDelete();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->decimal('score', 5, 2); // 0..max_score
            $table->timestamps();
            $table->unique(['students_courses_id', 'assessment_id']); // cegah duplikasi nilai
            $table->index(['assessment_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('grades');
    }
};
