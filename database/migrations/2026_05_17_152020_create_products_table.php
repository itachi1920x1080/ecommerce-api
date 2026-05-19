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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique(); // លេខកូដទំនិញ
            $table->decimal('regular_price', 10, 2); // តម្លៃធម្មតា
            $table->foreignId('category_id')->constrained();
            $table->decimal('discount_price', 10, 2)->nullable(); // តម្លៃបញ្ចុះ
            $table->integer('qty')->default(0); // ចំនួនក្នុងស្តុក
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // រូបភាព
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
