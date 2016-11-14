<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportPurchasedPerDayYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_bydate_purchased', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('purchased_item');
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
        Schema::drop('report_bydate_purchased');
    }
}
