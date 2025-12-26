<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('provider'); // 'google', 'facebook'
            $table->string('provider_user_id'); // ID trả về từ Google/FB
            $table->string('provider_email')->nullable();
            $table->timestamps();
            
            // Đảm bảo 1 user ID của Google ko add trùng lặp
            $table->unique(['provider', 'provider_user_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};