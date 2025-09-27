<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Default Laravel fields
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('password_confirmation')->nullable();
            $table->rememberToken();

            // Profile fields
            $table->string('username')->unique()->nullable();
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('cover_url')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            $table->integer('capped_file_size')->default(1024);//in mb 

            // Community metrics
            $table->bigInteger('points')->default(0);
            $table->integer('reputation')->default(0);
            $table->bigInteger('followers_count')->default(0);
            $table->bigInteger('following_count')->default(0);
            $table->bigInteger('posts_count')->default(0);

            // Account status
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_active_at')->nullable();

            // Profile completion and onboarding
            $table->integer('profile_completion_percentage')->default(0);
            $table->boolean('onboarding_completed')->default(false);

            // Privacy settings
            $table->boolean('profile_public')->default(true);
            $table->boolean('show_email')->default(false);
            $table->boolean('allow_messages')->default(true);
            $table->boolean('show_online_status')->default(true);

            // Referral system
            $table->string('affiliate_id')->unique()->nullable();
            $table->string('referral_code')->unique()->nullable();
            $table->foreignId('referred_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('referrals_count')->default(0);

            // Location tracking for legal compliance
            $table->json('location_history')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['username']);
            $table->index(['email']);
            $table->index(['is_active', 'last_active_at']);
            $table->index(['points', 'reputation']);
            $table->index(['followers_count']);
            $table->index(['is_admin']);
            $table->index(['affiliate_id']);
            $table->index(['referral_code']);
            $table->index(['referred_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
