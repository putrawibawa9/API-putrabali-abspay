<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()   // default ke users.id
                  ->nullOnDelete()
                  ->after('course_id');

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Laravel 8+ helper untuk drop FK + kolom
            $table->dropConstrainedForeignId('user_id');
            $table->dropIndex(['user_id']);
        });
    }
};
