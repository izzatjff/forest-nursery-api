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
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('species_id')->constrained('species')->cascadeOnDelete();
            $table->foreignId('seed_batch_id')->nullable()->constrained('seed_batches')->nullOnDelete();
            $table->foreignId('origin_id')->constrained('origins')->cascadeOnDelete();
            $table->decimal('height_cm', 8, 2)->default(0);
            $table->string('health_status')->default('healthy');
            $table->string('growth_stage')->default('seedling');
            $table->string('tray_number')->nullable();
            $table->string('location')->nullable();
            $table->date('potting_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->boolean('is_sold')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('uuid');
            $table->index('species_id');
            $table->index('origin_id');
            $table->index('health_status');
            $table->index('growth_stage');
            $table->index('is_sold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
