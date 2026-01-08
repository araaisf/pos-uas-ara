<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Produk (Sesuai soal: Manajemen Inventori)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->integer('stock');
            $table->string('image')->nullable(); // Foto biar aesthetic
            $table->timestamps();
        });

        // Tabel Transaksi (Sesuai soal: Transaksi Penjualan)
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code');
            $table->decimal('total_price', 15, 2);
            $table->decimal('cash_amount', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('transactions');
    }
};