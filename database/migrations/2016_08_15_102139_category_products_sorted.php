<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CategoryProductsSorted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('category_products_sorted', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->integer('product_id');
            $table->integer('sort_index');
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
         Schema::drop('category_products_sorted');
    }
}
