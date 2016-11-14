<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('posttags', function(Blueprint $table){
			$table->increments('id');
			$table->string('name', 255);
            $table->string('slug', 255);
            $table->string('description',2500);
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
		Schema::drop('posttags');
    }
}
