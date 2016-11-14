<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderPaymentInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function(Blueprint $table){
			$table->increments('id');
			$table->string('payment_method_id',45);
            $table->string('title',45);
            $table->boolean('paid');
            $table->string('txn_id',200);
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_payments');
    }
}
