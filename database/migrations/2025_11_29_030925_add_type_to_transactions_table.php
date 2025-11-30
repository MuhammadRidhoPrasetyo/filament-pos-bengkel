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
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('type', ['retail', 'service', 'internal', 'warranty'])
                ->default('retail')
                ->after('transaction_date');

            // Kalau 1 invoice bayar 1 service_order (bisa berisi banyak motor)
            $table->foreignUuid('service_order_id')->nullable()
                ->constrained('service_orders')
                ->after('payment_id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('service_order_id');
        });
    }
};
