<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            
            $table->string('transaction_code')->nullable();

            $table->string('status')->default('pending');
            
            $table->decimal('amount', 15, 2);

            $table->json('raw_data')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};