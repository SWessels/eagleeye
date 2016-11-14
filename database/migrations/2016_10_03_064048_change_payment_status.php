<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaymentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('order_payments', function(Blueprint $table)
        {
            $table->dropColumn('paid');
        });

        Schema::table('order_payments', function(Blueprint $table)
        {
            $table->enum('payment_status', ['pending','failed','processing','cancelled','on-hold','completed','refunded','deleted']);
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
        Schema::table('order_payments', function(Blueprint $table)
        {
            $table->dropColumn('payment_status');
        });

        Schema::table('order_payments', function(Blueprint $table)
        {
            $table->boolean('paid');
        });
    }
}
