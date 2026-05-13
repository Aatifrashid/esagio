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
        Schema::create('animation_clips', function (Blueprint $table) {
            $table->id();

            // Nullable clinic_id allows system-wide (shared) animation assets
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();

            $table->string('code')->nullable();
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('treatment_category_id')
                ->nullable()
                ->constrained('treatment_categories')
                ->nullOnDelete();

            $table->string('procedure_type')->nullable(); // e.g. extraction, implant, veneer
            // JSON array of FDI tooth numbers this clip applies to
            $table->json('tooth_positions')->nullable();
            $table->string('jaw')->nullable(); // upper, lower, both
            $table->integer('duration_seconds')->nullable();

            // Storage paths
            $table->string('video_path')->nullable();
            $table->string('thumbnail_path')->nullable();

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animation_clips');
    }
};
