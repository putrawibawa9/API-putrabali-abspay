<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('meetings', function (Blueprint $table) {
        $table->text('lesson_plan')->nullable()->after('teacher_id');
    });
}

public function down(): void
{
    Schema::table('meetings', function (Blueprint $table) {
        $table->dropColumn('lesson_plan');
    });
}

};
