<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->string('api_key', 64)->nullable()->unique()->after('id');
        });

        // Generate API keys for existing clinics
        foreach (\App\Models\Clinic::all() as $clinic) {
            $clinic->update(['api_key' => 'esa_' . Str::random(40)]);
        }
    }

    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn('api_key');
        });
    }
};
