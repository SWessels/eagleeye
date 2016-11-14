<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportRefundsPerDayYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_bydate_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->float('refund_amount');
            $table->integer('refund_orders');
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
        Schema::drop('report_bydate_refunds');
    }
}
