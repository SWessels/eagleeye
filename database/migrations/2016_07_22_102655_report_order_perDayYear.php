<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportOrderPerDayYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_bydate_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->float('gross_sale');
            $table->float('sum_tax');
            $table->float('net_sale');
            $table->float('average_sale');
            $table->integer('orders_placed');
            $table->timestamp('created_at');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('report_bydate_orders');
    }
}
