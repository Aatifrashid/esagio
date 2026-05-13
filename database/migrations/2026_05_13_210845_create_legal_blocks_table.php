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
        Schema::create('legal_blocks', function (Blueprint $table) {
            $table->id();

            // Nullable clinic_id allows system-wide (shared) legal blocks
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();

            // Short machine-readable identifier e.g. gdpr_consent, photo_consent
            $table->string('code');
            $table->string('name');
            $table->text('content');
            // BCP 47 language tag
            $table->string('language', 5)->default('en');
            // e.g. UK, EU, TR
            $table->string('jurisdiction')->nullable();
            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_blocks');
    }
};
