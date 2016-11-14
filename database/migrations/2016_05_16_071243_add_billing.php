<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBilling extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_billing', function(Blueprint $table){
            $table->increments('id');
            $table->integer('customer_id');
            $table->string('first_name',45);
            $table->string('last_name',45);
            $table->string('address_1',200);
            $table->string('address_2',200);
            $table->string('city',50);
            $table->string('state',50);
            $table->string('postcode',15);
            $table->string('country',50);
            $table->string('phone',15);
            $table->string('email',255);
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
        Schema::drop('customer_billing');
    }
}
