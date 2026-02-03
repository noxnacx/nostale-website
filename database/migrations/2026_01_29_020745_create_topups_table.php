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
        Schema::create('topups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->integer('points_received');
            $table->string('method'); // เช่น QR PromptPay, TrueMoney
            $table->string('transaction_id')->unique();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topups');
    }
};
