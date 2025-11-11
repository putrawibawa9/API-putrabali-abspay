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
        Schema::create('course_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id'); // id kursus
            $table->year('year');                    // tahun
            $table->tinyInteger('month');            // bulan 1-12
            $table->decimal('price', 10, 2);         // harga
            $table->string('note')->nullable();      // catatan opsional
            $table->timestamps();

            // opsional, jika ada tabel courses
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_prices');
    }
};
