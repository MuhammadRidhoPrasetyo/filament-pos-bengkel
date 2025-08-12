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
            $table->uuid('id')->primary();
            $table->foreignId('product_category_id')->constrained();
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->foreignId('unit_id')->nullable()->constrained();

            $table->string('sku')->unique()->nullable();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('keyword')->nullable();
            $table->string('compatibility')->nullable();
            $table->string('size')->nullable();
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
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
