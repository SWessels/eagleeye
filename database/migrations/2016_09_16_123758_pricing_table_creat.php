<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PricingTableCreat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('pricing', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->enum('discount_type',['percentage','fixed', 'freeshipping', 'freeproduct' ]);
            $table->integer('min_past_orders');
            $table->dateTime('date_from');
            $table->dateTime('date_to');
            $table->integer('discount_value');
            $table->enum('applies_on',['all', 'products', 'category']);
            $table->integer('order_value_id');
            $table->integer('min_order_value');
            $table->integer('min_products_order');
            $table->enum('visible_in',['catalog', 'cart']);
            $table->integer('coupon_id');
            $table->enum('visible_with_success',['no', 'yes']);
            $table->enum('use_as_discount',['cheapest', 'product']);
            $table->integer('use_as_discount_product');
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
        Schema::drop('pricing');
    }
}