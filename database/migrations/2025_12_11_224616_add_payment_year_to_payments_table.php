<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Tahap 1 — Tambah kolom nullable dulu
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('payment_year')->nullable()->after('payment_month');
        });

        // Tahap 2 — Backfill data lama berdasarkan created_at
        DB::table('payments')->update([
            'payment_year' => DB::raw('YEAR(created_at)')
        ]);

        // Tahap 3 — Jadikan kolom wajib (NOT NULL)
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('payment_year')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('payment_year');
        });
    }
};
