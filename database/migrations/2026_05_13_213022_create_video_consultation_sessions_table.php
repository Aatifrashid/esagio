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
        Schema::create('video_consultation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('treatment_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->timestamp('scheduled_for');
            $table->unsignedInteger('duration_minutes')->default(30);
            $table->string('daily_room_url')->nullable();
            $table->text('daily_room_token_patient')->nullable();
            $table->text('daily_room_token_clinic')->nullable();
            $table->string('status')->default('scheduled');
            $table->string('recording_url')->nullable();
            $table->boolean('recording_consent_given')->default(false);
            $table->text('notes')->nullable();
            $table->foreignId('conducted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('actual_duration_minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_consultation_sessions');
    }
};
