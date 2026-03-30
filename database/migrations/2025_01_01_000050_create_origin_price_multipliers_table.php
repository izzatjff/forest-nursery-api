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
        Schema::create('origin_price_multipliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_id')->unique()->constrained('origins')->cascadeOnDelete();
            $table->decimal('multiplier', 5, 3)->default(1.000);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('origin_price_multipliers');
    }
};
