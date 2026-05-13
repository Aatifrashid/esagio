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
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('reference_number');
            $table->string('title');
            $table->string('status')->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('signed_by_dentist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('currency', 3)->default('GBP');
            $table->decimal('exchange_rate_snapshot', 10, 6)->nullable();
            $table->string('language', 5)->default('en');
            $table->date('valid_until')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->unsignedInteger('viewed_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->text('decline_reason')->nullable();
            $table->text('patient_signature')->nullable();
            $table->timestamp('patient_signed_at')->nullable();
            $table->string('patient_ip_at_signing')->nullable();
            $table->text('patient_user_agent_at_signing')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('deposit_amount', 10, 2)->default(0);
            $table->timestamp('deposit_paid_at')->nullable();
            $table->string('deposit_payment_intent_id')->nullable();
            $table->uuid('public_token')->unique();
            $table->string('public_password')->nullable();
            $table->string('pdf_path')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->text('notes_internal')->nullable();
            $table->text('notes_to_patient')->nullable();
            $table->string('video_consultation_room_id')->nullable();
            $table->foreignId('template_used_id')->nullable()->constrained('treatment_plans')->nullOnDelete();
            $table->boolean('is_template')->default(false);
            $table->string('template_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};
