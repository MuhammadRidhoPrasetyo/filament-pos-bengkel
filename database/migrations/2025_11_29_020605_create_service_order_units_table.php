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
        Schema::create('service_order_units', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('service_order_id')
                ->constrained('service_orders')
                ->cascadeOnDelete();

            $table->foreignUuid('customer_vehicle_id')
                ->constrained('customer_vehicles');

            // Status khusus untuk motor ini
            $table->enum('status', [
                'checkin',
                'diagnosis',
                'in_progress',
                'waiting_parts',
                'ready',
                'invoiced',
                'cancelled',
            ])->default('checkin');

            $table->dateTime('checkin_at');
            $table->dateTime('completed_at')->nullable();

            $table->text('complaint')->nullable();   // keluhan spesifik motor ini
            $table->text('diagnosis')->nullable();   // analisa montir
            $table->text('work_done')->nullable();   // ringkasan pekerjaan

            $table->decimal('estimated_total', 15, 2)->default(0); // estimasi per motor

            // kalau kamu mau invoice per motor, bisa tambahin:
            // $table->foreignUuid('transaction_id')->nullable()->constrained('transactions')->nullOnDelete();

            $table->timestamps();

            $table->index(['service_order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_units');
    }
};
