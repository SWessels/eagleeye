<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Posts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('posts', function(Blueprint $table){
			$table->increments('id');
			$table->string('title',255);
			$table->string('slug',255);
			$table->string('url',255);
			$table->string('author',255);
			$table->text('description');
			$table->text('meta_description');
			$table->enum('status', ['draft', 'publish','deleted']);
			$table->enum('visibilty', ['hidden', 'visible']);
			$table->dateTime('published_at');
			$table->enum('type', ['page', 'post']);
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
        //
		Schema::drop('posts');
    }
}
