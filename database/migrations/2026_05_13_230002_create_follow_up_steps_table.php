<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow_up_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follow_up_sequence_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('delay_hours');
            $table->string('channel')->default('email'); // email, sms
            $table->string('subject')->nullable();
            $table->text('body_template');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['follow_up_sequence_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow_up_steps');
    }
};
