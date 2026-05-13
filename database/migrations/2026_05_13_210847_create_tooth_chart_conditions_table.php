<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * System-wide lookup table -- not clinic-scoped.
     */
    public function up(): void
    {
        Schema::create('tooth_chart_conditions', function (Blueprint $table) {
            $table->id();

            // Short machine-readable code e.g. caries, missing, crown
            $table->string('code')->unique();
            $table->string('name');
            // Hex colour used when rendering the tooth chart
            $table->string('colour', 7);
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tooth_chart_conditions');
    }
};
