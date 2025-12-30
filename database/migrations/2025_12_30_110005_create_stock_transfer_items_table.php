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
        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('stock_transfer_id')->constrained('stock_transfers')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products');

            $table->unsignedInteger('quantity');

            // optional: store product_price_id if you track price per stock lot
            $table->foreignUuid('product_price_id')->nullable()->constrained('product_prices');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfer_items');
    }
};
