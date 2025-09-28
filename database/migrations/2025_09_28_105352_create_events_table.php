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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            
            // Event details
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 50); // wedding, birthday, seminar, conference, etc.
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            
            // Date and time
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->string('timezone', 50)->default('UTC');
            
            // Event settings
            $table->string('slug')->unique();
            $table->enum('visibility', ['public', 'connections', 'private'])->default('public');
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('max_attendees')->nullable();
            $table->integer('current_attendees')->default(0);
            
            // Media
            $table->string('image_url')->nullable();
            $table->json('gallery')->nullable(); // Array of image URLs
            
            // Status
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['category']);
            $table->index(['slug']);
            $table->index(['start_datetime']);
            $table->index(['visibility']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};