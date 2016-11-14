<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tabs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tabs', function(Blueprint $table){
            $table->increments('id');
            $table->string('name',255);
            $table->text('description');
            $table->integer('parent_id'); 
            $table->enum('type',['global', 'details', 'custom']);
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
        Schema::drop('tabs');
    }
}
