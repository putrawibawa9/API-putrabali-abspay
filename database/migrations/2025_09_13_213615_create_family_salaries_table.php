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
    Schema::create('family_salaries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('family_member_id')->constrained()->cascadeOnDelete();
        $table->date('date');
        $table->decimal('amount', 10, 2);
        $table->timestamps();

        $table->unique(['family_member_id', 'date']); // 1 salary per orang per hari
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_salaries');
    }
};
