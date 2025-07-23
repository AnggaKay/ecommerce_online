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
        Schema::table('orders', function (Blueprint $table) {
            // dalam method up()
$table->string('name');
$table->string('phone');
$table->text('address');
$table->string('shipping_courier');
$table->string('shipping_service');
$table->string('invoice_number')->unique();
$table->string('payment_proof')->nullable(); // Untuk menyimpan path bukti bayar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
