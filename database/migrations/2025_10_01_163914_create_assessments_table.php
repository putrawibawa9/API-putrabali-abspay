<?php
// database/migrations/2025_10_01_000001_create_assessments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100); // "UTS", "UAS", "Quiz 1", dst.
            $table->enum('type', ['UTS','UAS','QUIZ','TASK','PROJECT','OTHER'])->default('OTHER');
            $table->date('date')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete(); // opsional
            $table->timestamps();

            $table->index(['course_id','type']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('assessments');
    }
};
