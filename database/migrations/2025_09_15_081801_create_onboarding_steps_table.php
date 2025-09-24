<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('onboarding_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Step tracking
            $table->string('step_name');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->integer('step_order')->default(0);

            // Step data (JSON for flexibility)
            $table->json('step_data')->nullable();

            // Progress tracking
            $table->integer('attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'step_name']);
            $table->index(['user_id', 'is_completed', 'step_order']);
            $table->unique(['user_id', 'step_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('onboarding_steps');
    }
};
