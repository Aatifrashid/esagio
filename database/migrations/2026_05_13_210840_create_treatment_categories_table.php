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
        Schema::create('treatment_categories', function (Blueprint $table) {
            $table->id();

            // Nullable clinic_id allows system-wide (shared) categories
            $table->foreignId('clinic_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('slug');

            // Self-referencing parent for nested categories
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('treatment_categories')
                ->nullOnDelete();

            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_categories');
    }
};
