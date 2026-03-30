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
        Schema::create('height_price_brackets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('species_id')->nullable()->constrained('species')->cascadeOnDelete();
            $table->decimal('min_height_cm', 8, 2);
            $table->decimal('max_height_cm', 8, 2)->nullable();
            $table->decimal('multiplier', 5, 3)->default(1.000);
            $table->timestamps();

            $table->index('species_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('height_price_brackets');
    }
};
