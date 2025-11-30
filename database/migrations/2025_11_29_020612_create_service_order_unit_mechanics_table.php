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
        Schema::create('service_order_unit_mechanics', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('service_order_unit_id')
                ->constrained('service_order_units')
                ->cascadeOnDelete();

            $table->foreignId('mechanic_id')
                ->constrained('users');

            $table->enum('role', ['leader', 'assistant'])->default('leader');
            $table->decimal('work_portion', 5, 2)->nullable();

            $table->timestamps();

            // short name untuk unique index
            $table->unique(['service_order_unit_id', 'mechanic_id'], 'so_unit_mechanic_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_unit_mechanics');
    }
};
