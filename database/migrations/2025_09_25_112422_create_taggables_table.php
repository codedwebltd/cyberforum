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
        // Polymorphic pivot table
        Schema::create('taggables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->onDelete('cascade');
            $table->morphs('taggable'); // Creates taggable_id and taggable_type
            $table->timestamps();

            // Prevent duplicate tag assignments
            $table->unique(['tag_id', 'taggable_id', 'taggable_type']);
            
            // Performance indexes
            // $table->index(['taggable_type', 'taggable_id']);
            $table->index(['tag_id']);
        });
  
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
    }
};
