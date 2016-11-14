<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('import_history', function(Blueprint $table){
            $table->increments('id');
            $table->enum('type', ['products', 'product_images' ]);
            $table->integer('last_from_id')->nullable();
            $table->integer('last_to_id')->nullable();
            $table->integer('last_offset')->nullable();
            $table->enum('status', ['failed', 'success', 'initial' ]);
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
        Schema::drop('import_history');
    }
}
