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
        Schema::create('purchases', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignUuid('supplier_id')->constrained('suppliers')->cascadeOnDelete();

            $table->foreignId('created_by')->constrained('users');   // user yang input
            $table->foreignId('received_by')->nullable()->constrained('users'); // user yang menerima
            $table->string('number')->unique();
            $table->string('invoice_number')->nullable();  // nomor invoice/nota supplier
            // $table->string('reference_number')->nullable(); // referensi internal/PO

            $table->date('purchase_date');                 // tanggal pembelian (dokumen)
            // $table->date('expected_arrival')->nullable();  // estimasi tiba
            // $table->dateTime('received_at')->nullable();   // realisasi diterima

            // $table->enum('status', ['draft', 'ordered', 'partial', 'received', 'cancelled'])->default('draft');

            // Diskon & pajak level header (opsional)
            $table->enum('discount_type', ['percent', 'amount'])->nullable();
            $table->decimal('discount_value', 12, 2)->nullable(); // persen: 0–100, amount: rupiah

            // $table->decimal('tax_percent', 5, 2)->nullable();     // mis. 11 (PPN 11%)
            // $table->decimal('shipping_cost', 12, 2)->default(0);
            // $table->decimal('other_costs', 12, 2)->default(0);

            // Ringkasan total (disimpan agar historis tetap)
            // $table->decimal('subtotal', 15, 2)->default(0);       // sebelum diskon/pajak header
            // $table->decimal('discount_total', 15, 2)->default(0);
            // $table->decimal('tax_total', 15, 2)->default(0);
            // $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('price', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
