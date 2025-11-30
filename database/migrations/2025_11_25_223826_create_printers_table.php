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
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('store_id')->constrained('stores');
            $table->string('name');                // Nama printer, contoh: “Kasir 1”
            $table->enum('connection_type', ['usb', 'network', 'bluetooth']);     // usb / network / bluetooth
            $table->string('address');             // IP address atau nama printer USB
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
