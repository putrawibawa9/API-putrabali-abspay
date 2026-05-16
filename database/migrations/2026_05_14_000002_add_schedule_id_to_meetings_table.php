<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->foreignId('schedule_id')
                ->nullable()
                ->after('id')
                ->constrained('schedules')
                ->nullOnDelete();

            $table->index(['schedule_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropIndex(['schedule_id', 'date']);
            $table->dropConstrainedForeignId('schedule_id');
        });
    }
};
