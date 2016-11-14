<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsTagsHasPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('posts_posttags', function(Blueprint $table){
            $table->increments('id');
			$table->integer('posts_posttag_id');
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
		Schema::drop('posts_posttags');
    }
}
