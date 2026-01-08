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
    Schema::table('transactions', function (Blueprint $table) {
        $table->string('customer_name')->nullable()->after('invoice_code');
        $table->decimal('tax', 15, 2)->default(0)->after('total_price');
        $table->decimal('discount', 15, 2)->default(0)->after('tax');
        $table->decimal('grand_total', 15, 2)->default(0)->after('discount');
    });
}

public function down()
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn(['customer_name', 'tax', 'discount', 'grand_total']);
    });
}
};
