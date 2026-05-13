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
        Schema::create('crm_activities', function (Blueprint $table) {
            $table->id();

            // Tenant scope
            $table->foreignId('clinic_id')->constrained()->cascadeOnDelete();

            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Activity details
            $table->string('type'); // e.g. call, email, meeting, note
            $table->string('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('outcome')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamp('occurred_at');

            // Follow-up scheduling
            $table->date('follow_up_date')->nullable();
            $table->string('follow_up_description')->nullable();

            $table->json('attachments')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_activities');
    }
};
