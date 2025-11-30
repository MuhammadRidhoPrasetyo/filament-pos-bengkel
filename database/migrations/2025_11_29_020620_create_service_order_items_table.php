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
        Schema::create('service_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('service_order_unit_id')
                ->constrained('service_order_units')
                ->cascadeOnDelete();

            $table->enum('item_type', ['part', 'labor']);          // barang / jasa
            $table->foreignUuid('product_id')->nullable()
                ->constrained('products');                        // optional kalau pakai master product

            $table->string('description')->nullable();            // jasa custom atau catatan khusus
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('line_total', 15, 2)->default(0);

            $table->timestamps();

            $table->index(['service_order_unit_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_items');
    }
};
