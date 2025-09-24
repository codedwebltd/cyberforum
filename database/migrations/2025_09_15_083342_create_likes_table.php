<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            // Polymorphic relationship (can like posts, comments, events, etc.)
            $table->morphs('likeable'); // creates likeable_id and likeable_type

            // Like types and metadata
            $table->enum('type', ['like', 'love', 'laugh', 'angry', 'sad', 'wow'])->default('like');
            $table->boolean('is_active')->default(true);

            // Tracking and analytics
            $table->json('metadata')->nullable(); // IP, user agent, etc. for spam detection
            $table->timestamp('created_at');

            // Performance indexes and constraints
            $table->unique(['user_id', 'likeable_id', 'likeable_type']); // Prevent duplicate likes
            $table->index(['likeable_type', 'likeable_id', 'type']);
            $table->index(['user_id', 'created_at']);
            $table->index(['is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
