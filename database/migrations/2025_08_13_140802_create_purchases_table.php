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
            $table->string('invoice_number')->nullable();
            $table->date('purchase_date');                 // tanggal pembelian (dokumen)
            $table->enum('discount_type', ['percent', 'amount'])->nullable();
            $table->decimal('discount_value', 12, 2)->nullable(); // persen: 0–100, amount: rupiah
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
