<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('orders', function(Blueprint $table){
			$table->increments('id');
			$table->integer('customer_id');
			$table->enum('status',['pending','failed','processing','cancelled','on-hold','completed','refunded', 'deleted']);
			$table->float('amount');
			$table->float('discount');
			$table->float('shipping_cost');
			$table->float('total_tax');
			$table->timestamp('completed_at');
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
		Schema::drop('orders');
    }
}
