<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_notes', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id');
            $table->timestamp('created_at');
            $table->enum('type',['admin','user']);
            $table->string('note',1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_notes');
    }
}
