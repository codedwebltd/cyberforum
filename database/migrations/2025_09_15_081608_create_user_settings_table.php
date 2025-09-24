<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Notification settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('push_notifications')->default(true);
            $table->boolean('comment_notifications')->default(true);
            $table->boolean('like_notifications')->default(true);
            $table->boolean('follow_notifications')->default(true);
            $table->boolean('mention_notifications')->default(true);
            $table->boolean('reply_notifications')->default(true);
            $table->boolean('weekly_digest')->default(false);
            $table->boolean('marketing_emails')->default(false);

            // Privacy settings
            $table->enum('profile_visibility', ['public', 'followers', 'private'])->default('public');
            $table->boolean('show_online_status')->default(true);
            $table->boolean('allow_friend_requests')->default(true);
            $table->boolean('allow_messages_from_strangers')->default(false);

            // Content and community preferences
            $table->json('content_preferences')->nullable(); // interests, engagement_level, skills, etc.
            $table->enum('content_language', ['en', 'es', 'fr', 'de', 'pt'])->default('en');
            $table->enum('timezone', ['UTC', 'EST', 'PST', 'GMT', 'CET'])->default('UTC');
            $table->enum('theme', ['light', 'dark', 'auto'])->default('auto');

            // Security settings
            $table->boolean('two_factor_enabled')->default(false);
            $table->boolean('login_alerts')->default(true);

            $table->timestamps();

            // Indexes
            $table->index(['user_id']);
            $table->unique(['user_id']); // Ensure one settings record per user
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
};
