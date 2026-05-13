<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country', 2)->default('GB');
            $table->string('timezone')->default('Europe/London');
            $table->string('currency_default', 3)->default('GBP');
            $table->string('language_default', 5)->default('en');
            $table->string('logo_path')->nullable();
            $table->string('primary_colour', 7)->default('#0A2540');
            $table->string('secondary_colour', 7)->default('#E8663D');
            $table->string('font_family')->default('Inter');
            $table->text('address')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('plan_tier')->default('free');
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('stripe_id')->nullable()->index();
            $table->string('stripe_subscription_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('suspended_at')->nullable();
            $table->string('suspended_reason')->nullable();
            $table->timestamp('monthly_plan_count_reset_at')->nullable();
            $table->unsignedInteger('plans_used_this_month')->default(0);
            $table->unsignedInteger('video_minutes_used_this_month')->default(0);
            $table->json('settings')->nullable();
            $table->json('feature_overrides')->nullable();
            $table->foreignId('parent_clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->foreignId('referred_by_clinic_id')->nullable()->constrained('clinics')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
