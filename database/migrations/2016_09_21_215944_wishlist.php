<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Wishlist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('customer_wishlist', function (Blueprint $table) {
            $table->integer('customer_id')->index();
            $table->integer('product_id')->index();
            $table->timestamp('date_added');
            //$table->primary(array('customer_id', 'product_id'));
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
        Schema::dropIfExists('customer_wishlist');
    }
}
