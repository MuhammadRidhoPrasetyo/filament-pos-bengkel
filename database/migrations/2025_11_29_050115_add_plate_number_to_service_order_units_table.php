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
        Schema::table('service_order_units', function (Blueprint $table) {
            $table->string('plate_number');          // KT 1234 AB
            $table->string('brand')->nullable();     // Honda, Yamaha
            $table->string('model')->nullable();     // Beat, Vario
            $table->string('color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_order_units', function (Blueprint $table) {
            $table->dropColumn('plate_number');
            $table->dropColumn('brand');
            $table->dropColumn('model');
            $table->dropColumn('year');
            $table->dropColumn('color');
        });
    }
};
