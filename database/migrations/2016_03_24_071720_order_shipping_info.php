<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderShippingInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('order_shippings', function(Blueprint $table){
			$table->increments('id');
			$table->string('first_name',45);
            $table->string('last_name',45);
            $table->string('address_1',200);
            $table->string('address_2',200);
            $table->string('city',50);
            $table->string('state',50);
            $table->string('postcode',15);
            $table->string('country',2);
            $table->integer('order_id');
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
        //
		Schema::drop('order_shippings');
    }
}
