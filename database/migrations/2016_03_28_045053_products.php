<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('products', function(Blueprint $table){
			$table->increments('id');
			$table->string('name',255);
			$table->string('slug',255);
			$table->float('sale_price')->nullable();
			$table->float('regular_price')->nullable();
			$table->string('sku',45); 
			$table->string('seo_title',255);
			$table->text('meta_description');
			$table->string('featured_image',255);
			$table->enum('status',['draft','publish','deleted']);
			$table->enum('visibility',['hidden','visible']);
			$table->enum('is_featured',['yes','no']);
			$table->datetime('sale_from');
			$table->datetime('sale_to');
			$table->enum('product_type',['simple','composite','variable']);
			$table->integer('taxes_id'); 
			$table->integer('users_id');
			$table->timestamps();
			$table->dateTime('published_at');
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

		Schema::drop('products');
    }
}
