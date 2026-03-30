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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->string('item_type'); // 'seed_batch', 'plant'
            $table->foreignId('seed_batch_id')->nullable()->constrained('seed_batches')->nullOnDelete();
            $table->foreignId('plant_id')->nullable()->constrained('plants')->nullOnDelete();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 10, 2); // base price at time of sale
            $table->decimal('calculated_price', 10, 2); // after all pricing rules
            $table->decimal('subtotal', 12, 2);
            $table->json('price_breakdown')->nullable(); // JSON showing how price was calculated
            $table->timestamps();

            $table->index(['sale_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
