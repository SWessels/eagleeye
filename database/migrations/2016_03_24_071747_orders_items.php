<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrdersItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('order_items', function(Blueprint $table){
			$table->increments('id');
			$table->integer('order_id');
			$table->integer('product_id');
			$table->float('unit_price');
			$table->float('unit_tax');
			$table->integer('qty');
			$table->float('total');
			$table->float('total_tax');
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
		Schema::drop('order_items');
    }
}
