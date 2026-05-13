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
        Schema::create('treatment_plan_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')->constrained()->cascadeOnDelete();
            $table->string('author_type');
            $table->string('author_name');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->text('content');
            $table->boolean('is_internal')->default(false);
            $table->foreignId('parent_comment_id')->nullable()->constrained('treatment_plan_comments')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plan_comments');
    }
};
