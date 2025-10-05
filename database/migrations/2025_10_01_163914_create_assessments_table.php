<?php
// database/migrations/2025_10_01_000001_create_assessments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('subject', 100); // "UTS", "UAS", "Quiz 1", dst.
            $table->string('type', 50); // "UTS", "UAS", "QUIZ", "TASK", "PROJECT", "OTHER"
            $table->integer('score');
            $table->text('remarks')->nullable();
            $table->timestamps();

  
        });
    }

    public function down(): void {
        Schema::dropIfExists('assessments');
    }
};
