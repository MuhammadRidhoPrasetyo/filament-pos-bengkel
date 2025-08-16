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
        Schema::create('product_movements', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('product_id')->constrained('products');
            $table->foreignUuid('store_id')->constrained('stores');

            $table->enum('movement_type', ['in', 'out']); // in = masuk (purchase/adjust+), out = keluar (sale/adjust-)
            $table->unsignedInteger('quantity');          // selalu positif

            // referensi sumber movement (harus bisa ke transaction_items / purchase_items / adjustment_items / transfer_items)
            $table->nullableUuidMorphs('movementable');   // movementable_type, movementable_id (UUID-safe)

            $table->timestamp('occurred_at');             // kapan kejadian (bukan created_at)
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->string('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_movements');
    }
};
