<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Media extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('media', function(Blueprint $table){
			$table->increments('id');
			$table->string('path',255);
			$table->string('type',45);
			$table->string('alt_text',255);
			$table->string('title',255);
			$table->text('description');
			$table->enum('is_featured', ['featured', 'gallery', 'null']);
			$table->integer('parent_id')->nullable();
			$table->integer('uploaded_by')->nullable();
			$table->string('size' , 25)->nullable();
			$table->string('image_dimension' , 25)->nullable();
            $table->date('uploaded_on');

		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
		Schema::drop('media');
    }
}
