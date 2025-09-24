<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');

            // Balance fields (in cents/smallest currency unit)
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('pending_balance')->default(0);
            $table->bigInteger('total_earned')->default(0);
            $table->bigInteger('total_spent')->default(0);

            // Wallet metadata
            $table->string('currency', 3)->default('USD');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_transaction_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'is_active']);
            $table->index(['balance']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
};
