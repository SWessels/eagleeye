<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddToCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('domain_id');
            $table->timestamps();
        });

        Schema::create('cart_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('domain_id');
            $table->integer('qty');
            $table->integer('price');
            $table->integer('discount');
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
        Schema::drop('cart');
        Schema::drop('cart_details');
    }
}
