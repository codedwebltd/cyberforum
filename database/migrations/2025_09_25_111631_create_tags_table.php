<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tags table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 7)->nullable(); // HEX color code
            $table->bigInteger('usage_count')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index(['usage_count']);
            $table->index(['name']);
            $table->fullText(['name', 'description']);
        });

       
    }

    public function down(): void
    {
     
        Schema::dropIfExists('tags');
    }
};