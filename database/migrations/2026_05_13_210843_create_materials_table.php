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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            // Nullable clinic_id allows system-wide (shared) material records
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('category')->nullable(); // e.g. implant, crown, veneer
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->integer('warranty_years')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->string('image_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
