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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('number')->unique();              // SO-202511-001
            $table->foreignUuid('store_id')->constrained('stores');
            $table->foreignUuid('customer_id')->constrained('suppliers');

            // status global untuk kunjungan ini (digabung dari semua unit)
            $table->enum('status', [
                'checkin',        // baru datang
                'in_progress',    // ada unit yang sedang dikerjakan
                'waiting_parts',  // ada unit yang menunggu sparepart
                'ready',          // semua unit sudah selesai
                'invoiced',       // sudah dibuat invoice POS
                'cancelled',
            ])->default('checkin');

            $table->dateTime('checkin_at');
            $table->dateTime('completed_at')->nullable();

            // keluhan umum (misal: “dua motor, cek semua”)
            $table->text('general_complaint')->nullable();

            // estimasi total untuk seluruh kunjungan (opsional)
            $table->decimal('estimated_total', 15, 2)->default(0);

            // kalau 1 invoice POS untuk seluruh service_order
            $table->foreignUuid('transaction_id')->nullable()
                ->constrained('transactions')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['store_id', 'checkin_at']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
