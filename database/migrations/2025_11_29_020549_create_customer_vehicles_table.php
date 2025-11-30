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
        Schema::create('customer_vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('customer_id')->nullable()->constrained('suppliers'); // atau 'customers'
            $table->string('plate_number');          // KT 1234 AB
            $table->string('brand')->nullable();     // Honda, Yamaha, dll
            $table->string('model')->nullable();     // Beat, Vario, Nmax
            $table->year('year')->nullable();
            $table->string('color')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique(['customer_id', 'plate_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_vehicles');
    }
};
