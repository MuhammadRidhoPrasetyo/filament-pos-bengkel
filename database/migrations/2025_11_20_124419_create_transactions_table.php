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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Nomor transaksi
            $table->string('number')->unique(); // contoh: POS-20250224-001

            // Relasi utama
            $table->foreignUuid('store_id')->constrained('stores');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignUuid('customer_id')->nullable()->constrained('suppliers');
            $table->foreignUuid('payment_id')->nullable()->constrained('payments'); // QRIS/BCA/BRI dll

            // Waktu transaksi
            $table->dateTime('transaction_date');

            // Angka dasar & diskon
            $table->decimal('subtotal', 15, 2)->default(0);                     // sebelum diskon item
            $table->decimal('item_discount_total', 15, 2)->default(0);          // total diskon item
            $table->decimal('subtotal_after_item_discount', 15, 2)->default(0); // setelah diskon item

            // Diskon universal (header)
            $table->enum('universal_discount_mode', ['percent', 'amount'])->nullable();
            $table->decimal('universal_discount_value', 12, 2)->nullable();
            $table->decimal('universal_discount_amount', 15, 2)->default(0);

            // Pajak (kalau nanti dipakai)
            $table->decimal('tax_total', 15, 2)->default(0);

            // Grand total yang seharusnya dibayar
            $table->decimal('grand_total', 15, 2)->default(0);

            // Pembayaran
            $table->decimal('paid_amount', 15, 2)->default(0);   // uang yang diterima
            $table->decimal('change_amount', 15, 2)->default(0); // kembalian
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])
                ->default('paid');

            // Cost & profit (opsional, tapi sangat berguna)
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);

            $table->enum('status', ['draft', 'completed', 'void'])->default('completed');
            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['store_id', 'transaction_date']);
            $table->index(['user_id', 'transaction_date']);
            $table->index(['payment_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
