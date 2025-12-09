<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('meetings', function (Blueprint $table) {
        $table->foreignId('original_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
        $table->boolean('is_canceled')->default(false);
        $table->text('change_note')->nullable();
        $table->time('end_time')->nullable();
        $table->string('location')->nullable();
    });
}

public function down()
{
    Schema::table('meetings', function (Blueprint $table) {
        $table->dropConstrainedForeignId('original_teacher_id');
        $table->dropColumn(['is_canceled','change_note','end_time','location']);
    });
}

};
