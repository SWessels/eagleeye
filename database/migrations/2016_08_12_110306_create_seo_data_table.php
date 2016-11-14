<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id');
            $table->string('title', 255);
            $table->text('description');
            $table->boolean('is_index');
            $table->boolean('is_follow');
            $table->string('canonical_url');
            $table->string('redirect');
            $table->string('type' , 25);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('seo_data');
    }
}
