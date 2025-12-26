<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            $table->string('tracking_code')->nullable();
            
            $table->string('carrier')->nullable();
            
            $table->string('status')->default('pending');
            
            $table->decimal('cod_amount', 15, 2)->default(0);
            
            $table->text('note')->nullable();
            
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};