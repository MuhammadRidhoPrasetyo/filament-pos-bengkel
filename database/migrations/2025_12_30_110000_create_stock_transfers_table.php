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
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // source and destination stores
            $table->foreignUuid('from_store_id')->constrained('stores');
            $table->foreignUuid('to_store_id')->constrained('stores');

            // status workflow for the transfer
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft');

            $table->string('reference_number')->nullable();
            $table->timestamp('occurred_at')->useCurrent();

            // auditing
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('posted_by')->nullable()->constrained('users');
            $table->timestamp('posted_at')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transfers');
    }
};
