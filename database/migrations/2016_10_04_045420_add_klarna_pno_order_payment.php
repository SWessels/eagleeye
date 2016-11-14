<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKlarnaPnoOrderPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_payments', function ($table) {
            $table->string('klarna_pno', 255)->nullable();
            $table->string('klarna_invoice_url', 255)->nullable();
            $table->string('order_key', 255)->nullable();
        });
    }

/**
 * Reverse the migrations.
 *
 * @return void
 */
public function down()
{
    //
    Schema::table('order_payments', function ($table) {
        $table->dropColumn('klarna_pno');
        $table->dropColumn('klarna_invoice_url');
        $table->dropColumn('order_key');
    });
}
}
