<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitemapIndexTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sitemap_index', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sitemap', 255);
            $table->dateTime('last_modified');
            $table->string('type' , 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sitemap_index');
    }
}
