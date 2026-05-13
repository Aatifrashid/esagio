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
        Schema::create('clinic_team_members', function (Blueprint $table) {
            $table->id();

            // Tenant scope
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();

            $table->string('first_name');
            $table->string('last_name');
            // Honorific or professional title e.g. Dr, Prof
            $table->string('title', 20)->nullable();
            $table->string('role')->nullable();         // e.g. Dentist, Coordinator
            $table->string('specialisation')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('qualifications')->nullable();
            $table->integer('years_experience')->nullable();
            // Comma-separated list of languages
            $table->string('languages_spoken')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_team_members');
    }
};
