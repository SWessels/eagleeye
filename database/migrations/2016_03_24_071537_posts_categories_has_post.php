<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsCategoriesHasPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('posts_postcategories', function(Blueprint $table){
            $table->increments('id');
			$table->integer('postcategory_id');
			$table->integer('posts_id');

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
		Schema::drop('posts_postcategories');
    }
}
