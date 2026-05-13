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
        Schema::create('price_list_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('price_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Allows pricing variants of the same template (e.g. upper arch / lower arch)
            $table->string('variant_name')->nullable();
            $table->decimal('unit_price', 10, 2);
            // Pricing model: fixed, per_tooth, per_arch, etc.
            $table->string('unit_label')->default('fixed');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_list_items');
    }
};
