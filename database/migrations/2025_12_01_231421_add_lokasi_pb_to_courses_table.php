<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // tambahkan kolom lokasi_pb (1 atau 2); default 1 biar data existing aman
            $table->tinyInteger('lokasi_pb')->default(1)->after('is_active');
            // ganti after('description') jika kolomnya beda
        });

        // pastikan data existing terisi (sebenarnya default sudah mengisi otomatis)
        DB::table('courses')->whereNull('lokasi_pb')->update(['lokasi_pb' => 1]);
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('lokasi_pb');
        });
    }
};
