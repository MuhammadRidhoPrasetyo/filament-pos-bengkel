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
        Schema::table('service_orders', function (Blueprint $table) {
            Schema::table('service_orders', function (Blueprint $table) {
                // drop existing foreign key
                $table->dropForeign(['customer_id']);

                // make column nullable (requires doctrine/dbal)
                $table->uuid('customer_id')->nullable()->change();

                // re-add foreign key, allow null on delete
                $table->foreign('customer_id')->references('id')->on('suppliers')->nullOnDelete();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            Schema::table('service_orders', function (Blueprint $table) {
                $table->dropForeign(['customer_id']);

                // make column NOT NULL again
                $table->uuid('customer_id')->change();

                // re-add original foreign key constraint
                $table->foreign('customer_id')->references('id')->on('suppliers');
            });
        });
    }
};
