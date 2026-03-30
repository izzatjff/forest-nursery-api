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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('procurement_number')->unique();
            $table->string('supplier_name');
            $table->string('supplier_contact')->nullable();
            $table->foreignId('species_id')->constrained('species')->cascadeOnDelete();
            $table->foreignId('origin_id')->constrained('origins')->cascadeOnDelete();
            $table->string('source_type');
            $table->decimal('quantity', 10, 2);
            $table->string('unit')->default('pieces');
            $table->decimal('cost_per_unit', 10, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->date('received_date');
            $table->foreignId('seed_batch_id')->nullable()->constrained('seed_batches')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('procurement_number');
            $table->index('received_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
