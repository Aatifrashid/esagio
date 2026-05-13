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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            // Tenant scope
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();

            // Identification
            $table->string('reference_code');
            $table->unique(['clinic_id', 'reference_code']);

            // Personal details
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('country_of_residence')->nullable();
            $table->string('city')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('preferred_language')->nullable();
            $table->string('occupation')->nullable();

            // Medical information
            $table->json('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->text('current_medications')->nullable();
            $table->text('dental_history_notes')->nullable();

            // Lead / referral tracking
            $table->string('source')->nullable();
            $table->foreignId('referred_by_patient_id')
                ->nullable()
                ->constrained('patients')
                ->nullOnDelete();

            // Assignments
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // CRM status
            $table->string('status')->default('lead');
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            // FK to crm_pipeline_stages added after that table is created (see stages migration)
            $table->unsignedBigInteger('pipeline_stage_id')->nullable();

            // Pipeline scheduling
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamp('next_action_at')->nullable();
            $table->string('next_action_description')->nullable();
            $table->decimal('deal_value', 10, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
