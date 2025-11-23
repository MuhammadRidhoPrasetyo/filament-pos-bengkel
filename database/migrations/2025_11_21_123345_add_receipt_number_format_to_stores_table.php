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
        Schema::table('stores', function (Blueprint $table) {
            // Format nomor struk, contoh default:
            // {STORE_CODE}/{YYYY}/{MM}/{NUMBER}
            $table->string('receipt_number_format')
                ->default('{STORE_CODE}/{YYYY}/{MM}/{NUMBER}');

            // counter terakhir di tahun aktif
            $table->unsignedInteger('receipt_sequence')->default(0);

            // tahun terakhir counter dipakai (untuk reset tahunan)
            $table->unsignedSmallInteger('receipt_sequence_year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('receipt_number_format');
            $table->dropColumn('receipt_sequence');
            $table->dropColumn('receipt_sequence_year');
        });
    }
};
