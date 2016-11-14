<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('users', function(Blueprint $table){
			$table->increments('id');
			$table->integer('role_id');
			$table->string('username',45);
			$table->string('password',255);
			$table->string('email',45);
			$table->string('name',45);
			$table->string('remember_token',255);
			$table->integer('domain_id');
			$table->enum('status', ['active', 'inactive','deleted']);
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
		Schema::drop('users');
    }
}
