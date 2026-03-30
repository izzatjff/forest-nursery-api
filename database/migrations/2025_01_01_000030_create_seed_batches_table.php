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
        Schema::create('seed_batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batch_code')->unique();
            $table->foreignId('species_id')->constrained('species')->cascadeOnDelete();
            $table->foreignId('origin_id')->constrained('origins')->cascadeOnDelete();
            $table->string('source_type');
            $table->string('supplier_name')->nullable();
            $table->date('collection_date');
            $table->decimal('initial_quantity', 10, 2);
            $table->decimal('remaining_quantity', 10, 2);
            $table->string('unit')->default('pieces');
            $table->string('storage_location')->nullable();
            $table->decimal('viability_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['species_id', 'origin_id', 'batch_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seed_batches');
    }
};
