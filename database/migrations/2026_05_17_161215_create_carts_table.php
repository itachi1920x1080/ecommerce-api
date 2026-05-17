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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            // ភ្ជាប់ទៅតារាង users (ដឹងថាជារបស់អ្នកណា)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // ភ្ជាប់ទៅតារាង products (ដឹងថាគេទិញអី)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // ចំនួនដែលគេទិញ
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
