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
        Schema::create('finance_entries', function (Blueprint $table) {
    $table->id();         
    $table->foreignId('finance_category_id')->constrained('finance_categories');
    $table->enum('direction', ['income','expense']);   // redundant dgn kategori, tapi mempercepat query
    $table->decimal('amount',14,2);
    $table->text('note')->nullable();

    $table->timestamps();

 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_entries');
    }
};
