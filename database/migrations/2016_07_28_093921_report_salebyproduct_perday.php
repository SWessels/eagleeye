<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportSalebyproductPerday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_byproducts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('product_id');
            $table->string('name',255);
            $table->string('sku',45);
            $table->integer('qty');
            $table->float('gross');
            $table->float('tax');
            $table->float('net');
            $table->integer('refund_qty');
            $table->float('refund_total');
            $table->float('refund_tax');
            $table->float('refund_net');
            $table->integer('parent');
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
        Schema::drop('report_byproducts');
    }
}
