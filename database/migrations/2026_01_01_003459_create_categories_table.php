<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // Nama kategori (Jewelry, Bags, dll)
        $table->timestamps();
    });

    Schema::table('products', function (Blueprint $table) {
        // Tambahkan kolom category_id ke tabel products
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropColumn('category_id');
    });
    Schema::dropIfExists('categories');
}
};
