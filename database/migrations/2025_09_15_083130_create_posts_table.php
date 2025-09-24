<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Content fields
            $table->string('title')->nullable();
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->string('featured_image_url')->nullable();

            // Post metadata
            $table->enum('type', ['discussion', 'question', 'announcement', 'poll', 'marketplace'])->default('discussion');
            $table->enum('status', ['published', 'draft', 'archived', 'deleted'])->default('published');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);

            // Engagement metrics
            $table->bigInteger('views_count')->default(0);
            $table->bigInteger('likes_count')->default(0);
            $table->bigInteger('comments_count')->default(0);
            $table->bigInteger('shares_count')->default(0);

            // Content moderation
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_reported')->default(false);
            $table->integer('report_count')->default(0);

            // SEO and discovery
            $table->string('slug')->unique()->nullable();
            $table->json('tags')->nullable();
            $table->json('mentions')->nullable(); // @username mentions
            $table->json('attachments')->nullable(); // file URLs from B2

            // Timestamps and activity
            $table->timestamp('published_at')->nullable();
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['user_id', 'status', 'published_at']);
            $table->index(['type', 'status', 'is_pinned', 'last_activity_at']);
            $table->index(['is_featured', 'likes_count']);
            $table->index(['slug']);
            $table->index(['views_count']);
            $table->fullText(['title', 'content', 'excerpt']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
