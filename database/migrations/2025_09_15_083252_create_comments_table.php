<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('post_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onUpdate('cascade')->onDelete('cascade');

            // Content fields
            $table->text('content');
            $table->json('attachments')->nullable(); // file URLs from B2
            $table->json('mentions')->nullable(); // @username mentions

            // Nested comment paths (for efficient querying)
            $table->string('path')->nullable(); // e.g., "1.5.12" for nested structure
            $table->integer('depth')->default(0);

            // Engagement metrics
            $table->bigInteger('likes_count')->default(0);
            $table->bigInteger('replies_count')->default(0);

            // Content moderation
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_reported')->default(false);
            $table->integer('report_count')->default(0);
            $table->enum('status', ['published', 'edited', 'deleted', 'hidden'])->default('published');

            // Threading and activity
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            // Performance indexes
            $table->index(['post_id', 'parent_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['path', 'depth']);
            $table->index(['likes_count']);
            $table->index(['last_activity_at']);
            $table->fullText(['content']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
