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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            
            // Transaction details
            $table->string('transaction_id', 50)->unique(); // Custom transaction ID
            $table->enum('type', ['credit', 'debit']); // Money in or out
            $table->string('category', 50); // earning, withdrawal, purchase, refund, etc.
            $table->bigInteger('amount'); // Amount in cents
            $table->string('description')->nullable();
            
            // Status and metadata
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('currency', 3)->default('USD');
            $table->json('metadata')->nullable(); // For additional data like payment gateway info
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'status']);
            $table->index(['category']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};