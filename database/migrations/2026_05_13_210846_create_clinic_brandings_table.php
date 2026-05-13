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
        Schema::create('clinic_brandings', function (Blueprint $table) {
            $table->id();

            // One-to-one with clinics
            $table->foreignId('clinic_id')->unique()->constrained()->cascadeOnDelete();

            // Images
            $table->string('cover_page_image')->nullable();
            $table->string('logo_dark')->nullable();
            $table->string('logo_light')->nullable();

            // Brand colours (hex)
            $table->string('primary_colour', 7)->default('#0A2540');
            $table->string('accent_colour', 7)->default('#E8663D');

            // Typography
            $table->string('heading_font')->default('Fraunces');
            $table->string('body_font')->default('Inter');

            $table->text('footer_text')->nullable();

            // JSON arrays of links / accreditation records / image paths
            $table->json('social_links')->nullable();
            $table->json('accreditations')->nullable();
            $table->json('certifications_images')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_brandings');
    }
};
