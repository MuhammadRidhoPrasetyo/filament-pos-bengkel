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
        Schema::create('product_labels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->foreignId('product_category_id')->constrained();
            $table->foreignId('brand_id')->nullable()->constrained();
            $table->boolean('label_sku')->nullable();
            $table->boolean('label_category')->nullable();
            $table->boolean('label_brand')->nullable();
            $table->boolean('label_type')->nullable();
            $table->boolean('label_unit')->nullable();
            $table->boolean('label_size')->nullable();
            $table->boolean('label_keyword')->nullable();
            $table->boolean('label_compatibility')->nullable();
            $table->boolean('label_description')->nullable();
            $table->string('separator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_labels');
    }
};
