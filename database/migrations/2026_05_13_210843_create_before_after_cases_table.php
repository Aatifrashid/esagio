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
        Schema::create('before_after_cases', function (Blueprint $table) {
            $table->id();

            // Tenant scope
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // Anonymised patient demographic details for the case study
            $table->string('patient_age_range')->nullable();  // e.g. 30-40
            $table->string('patient_gender')->nullable();
            $table->integer('treatment_duration_days')->nullable();

            // Media assets
            $table->string('before_image_path')->nullable();
            $table->string('after_image_path')->nullable();
            $table->json('additional_images')->nullable();

            $table->boolean('is_featured')->default(false);
            // Must be true before the case can be shown publicly
            $table->boolean('consent_obtained')->default(false);

            $table->string('tags')->nullable();
            $table->decimal('treatment_value', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('before_after_cases');
    }
};
