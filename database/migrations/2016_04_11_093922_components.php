<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Components extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('components', function(Blueprint $table){
            $table->increments('id');
            $table->string('title',255);
            $table->text('description'); 
            $table->integer('position');
            $table->integer('product_id');
            $table->integer('default_id');
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
        Schema::drop('components');
    }
}
