<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('clinic_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('role')->default('viewer')->after('email');
            $table->boolean('is_active')->default(true)->after('role');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('locale', 5)->default('en')->after('last_login_at');
            $table->string('avatar_path')->nullable()->after('locale');
            $table->string('signature_path')->nullable()->after('avatar_path');
            $table->string('title', 20)->nullable()->after('signature_path');
            $table->string('specialisation')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('clinic_id');
            $table->dropColumn([
                'role', 'is_active', 'last_login_at', 'locale',
                'avatar_path', 'signature_path', 'title', 'specialisation',
            ]);
        });
    }
};
