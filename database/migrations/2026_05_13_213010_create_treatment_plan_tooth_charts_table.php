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
        Schema::create('treatment_plan_tooth_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')->constrained()->cascadeOnDelete();
            $table->string('tooth_number');
            $table->json('conditions')->nullable();
            $table->json('planned_treatments')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['treatment_plan_id', 'tooth_number'], 'tptc_plan_tooth_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_tooth_charts');
    }
};
