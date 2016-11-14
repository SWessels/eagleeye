<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_payments', function ($table) {
            $table->string('res_no',50);
            $table->string('inv_no',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_payments', function ($table) {
            $table->dropColumn('res_no');
            $table->dropColumn('inv_no');
        });
    }
}
