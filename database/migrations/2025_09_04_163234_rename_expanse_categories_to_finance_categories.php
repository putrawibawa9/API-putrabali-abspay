<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Rename table
        Schema::rename('expanse_categories', 'finance_categories');

        // 2. Alter structure
        Schema::table('finance_categories', function (Blueprint $table) {
            // rename kolom description -> name
            $table->renameColumn('description', 'name');

            // tambah kolom baru
            $table->enum('type', ['income','expense'])->default('expense')->after('code');
            $table->boolean('is_active')->default(true)->after('name');

            // unique code
            $table->unique('code');
        });

        // 3. Update existing data: set semua type = expense
        DB::table('finance_categories')->update(['type' => 'expense']);
    }

    public function down(): void
    {
        Schema::table('finance_categories', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn(['type','is_active']);
            $table->renameColumn('name', 'description');
        });

        Schema::rename('finance_categories', 'expanse_categories');
    }
};
