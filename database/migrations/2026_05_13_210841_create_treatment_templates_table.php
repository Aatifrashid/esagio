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
        Schema::create('treatment_templates', function (Blueprint $table) {
            $table->id();

            // Nullable clinic_id allows system-wide (shared) templates
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('treatment_categories')
                ->nullOnDelete();

            $table->string('code')->nullable();
            $table->string('name');
            $table->text('description_short')->nullable();
            $table->text('description_long')->nullable();

            // Structured procedure and recovery information
            $table->json('procedure_steps')->nullable();
            $table->text('recovery_info')->nullable();
            $table->json('materials_options')->nullable();

            // Legal and guarantee copy
            $table->text('guarantee_text')->nullable();
            $table->text('legal_disclaimer')->nullable();

            $table->integer('standard_duration_days')->nullable();
            $table->boolean('requires_imaging')->default(false);

            // Linked media asset IDs
            $table->json('default_animation_clip_ids')->nullable();
            $table->json('default_before_after_ids')->nullable();

            $table->boolean('is_active')->default(true);
            // Tracks how many times this template has been used in treatment plans
            $table->integer('usage_count')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_templates');
    }
};
