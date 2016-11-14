<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TryoutsEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tryouts_emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->integer('likes');
            $table->integer('is_sent');
            $table->timestamps();
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
        Schema::dropIfExists('tryouts_emails');
    }
}
