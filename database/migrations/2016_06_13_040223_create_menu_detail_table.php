<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id');
            $table->integer('sub_menu_id');
            $table->integer('parent_id');
            $table->string('url', 255)->nullable();
            $table->string('title',255);
            $table->string('type', 100);
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_details');
    }
}
