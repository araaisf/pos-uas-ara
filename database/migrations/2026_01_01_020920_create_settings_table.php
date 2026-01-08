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
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('shop_name')->default('Kashara Boutique');
        $table->string('shop_address')->default('Jakarta, Indonesia');
        $table->string('shop_slogan')->nullable()->default('Maison de Luxe');
        $table->timestamps();
    });

    // Langsung isi data default biar gak error
    DB::table('settings')->insert([
        'shop_name' => 'Kashara Store',
        'shop_address' => 'Grand Indonesia, West Mall',
        'shop_slogan' => 'Luxury & Elegant',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

public function down()
{
    Schema::dropIfExists('settings');
}
};
