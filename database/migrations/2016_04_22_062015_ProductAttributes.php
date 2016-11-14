<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('productattributes', function(Blueprint $table){
            $table->increments('id');
            $table->string('name',50);
            $table->string('slug', 255);
            $table->enum('type',['default','custom']);
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
        Schema::drop('productattributes');
       
    }
}
