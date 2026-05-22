<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('utm_source')->nullable()->after('source');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');
            $table->string('landing_page')->nullable()->after('utm_content');
            $table->string('referrer_url')->nullable()->after('landing_page');
            $table->string('lead_channel')->nullable()->after('referrer_url'); // website_form, facebook_ads, google_ads, instagram_ads, api, manual
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'utm_source', 'utm_medium', 'utm_campaign', 'utm_term',
                'utm_content', 'landing_page', 'referrer_url', 'lead_channel',
            ]);
        });
    }
};
