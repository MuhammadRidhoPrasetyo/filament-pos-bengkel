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
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('transaction_id')
                ->constrained('transactions')
                ->cascadeOnDelete();

            $table->foreignUuid('product_id')->constrained('products');
            $table->foreignUuid('store_id')->constrained('stores');              // redundan untuk laporan cepat
            $table->foreignUuid('product_stock_id')->nullable()
                ->constrained('product_stocks');                                // jejak stok asal (opsional)

            $table->unsignedInteger('quantity');

            // ========== HARGA PENJUALAN ==========

            // harga dasar per unit saat transaksi (sebelum diskon item)
            $table->decimal('unit_price', 12, 2);                                // Dari cart['selling_price']

            // mode & nilai diskon item (berdasarkan discount_type + product_discounts)
            $table->enum('item_discount_mode', ['percent', 'amount'])
                ->nullable();                                                   // null = tidak ada diskon item
            $table->decimal('item_discount_value', 12, 2)
                ->nullable();                                                   // 10 (10%) atau 5000 (Rp)
            $table->decimal('item_discount_amount', 15, 2)
                ->default(0);                                                   // nominal potongan utk line ini (qty * perUnitDisc)

            // harga per unit setelah diskon item
            $table->decimal('final_unit_price', 12, 2);                           // unit_price - perUnitDisc

            // subtotal line sebelum diskon item (qty * unit_price)
            $table->decimal('line_subtotal', 15, 2);                              // qty * unit_price

            // total line setelah diskon item (qty * final_unit_price)
            $table->decimal('line_total', 15, 2);                                 // qty * final_unit_price

            // kalau mau catat jenis diskon yang dipakai (P1, P2, dst)
            $table->foreignId('discount_type_id')
                ->nullable()
                ->constrained('discount_types')
                ->nullOnDelete();

            // ========== COST & PROFIT ==========

            // harga modal per unit
            $table->decimal('unit_cost', 12, 2)->default(0);                      // ambil dari product_prices.purchase_price / rata-rata

            // total modal untuk line (qty * unit_cost)
            $table->decimal('line_cost_total', 15, 2)->default(0);

            // laba kotor utk line ini (line_total - line_cost_total)
            $table->decimal('line_profit', 15, 2)->default(0);

            // info tambahan
            $table->boolean('price_edited')->default(false);                      // true kalau kasir ubah harga manual (kategori editable)
            $table->string('pricing_mode')->nullable();                           // 'fixed' / 'editable'

            $table->timestamps();

            $table->index(['transaction_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
