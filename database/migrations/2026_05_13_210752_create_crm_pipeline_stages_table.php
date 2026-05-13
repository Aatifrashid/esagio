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
        Schema::create('crm_pipeline_stages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('crm_pipeline_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            // Hex colour for Kanban board display
            $table->string('colour', 7)->default('#6B7280');
            $table->tinyInteger('probability_percentage')->default(0);
            $table->integer('sort_order')->default(0);
            // Flags to mark terminal stages
            $table->boolean('is_won')->default(false);
            $table->boolean('is_lost')->default(false);

            $table->timestamps();
        });

        // Add FK from patients to crm_pipeline_stages now that both tables exist
        Schema::table('patients', function (Blueprint $table) {
            $table->foreign('pipeline_stage_id')
                ->references('id')
                ->on('crm_pipeline_stages')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['pipeline_stage_id']);
        });

        Schema::dropIfExists('crm_pipeline_stages');
    }
};
