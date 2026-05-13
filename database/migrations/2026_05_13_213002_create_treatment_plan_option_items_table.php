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
        Schema::create('treatment_plan_option_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_option_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_template_id')->nullable()->constrained('treatment_templates')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('line_total', 10, 2)->default(0);
            $table->foreignId('material_id')->nullable()->constrained('materials')->nullOnDelete();
            $table->string('variant_name')->nullable();
            $table->integer('position')->default(0);
            $table->text('notes')->nullable();
            $table->json('included_animation_clip_ids')->nullable();
            $table->json('included_before_after_ids')->nullable();
            $table->json('tooth_positions')->nullable();
            $table->string('procedure_phase')->nullable();
            $table->json('populated_fields')->nullable();
            $table->boolean('is_optional')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_option_items');
    }
};
