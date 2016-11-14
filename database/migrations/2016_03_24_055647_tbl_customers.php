<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('customers', function(Blueprint $table){
			$table->increments('id');
			$table->string('username',45);
			$table->string('password',255);
			$table->string('first_name',45);
			$table->string('last_name',45);
			$table->string('email',45);
			/*$table->text('address');
			$table->integer('country_id');
			$table->integer('city_id');
			$table->string('phone',45);*/
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
		Schema::drop('customers');
    }
}
