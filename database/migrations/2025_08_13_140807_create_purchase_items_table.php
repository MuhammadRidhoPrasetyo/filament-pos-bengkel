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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('purchase_id')->constrained('purchases')->cascadeOnDelete();
            $table->foreignUuid('product_id')->constrained('products');
            $table->enum('price_type', ['toko', 'distributor'])->default('toko'); // tipe harga beli
            $table->unsignedInteger('quantity_ordered');
            $table->decimal('unit_purchase_price', 12, 2);
            $table->enum('item_discount_type', ['percent', 'amount'])->nullable();
            $table->decimal('item_discount_value', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
