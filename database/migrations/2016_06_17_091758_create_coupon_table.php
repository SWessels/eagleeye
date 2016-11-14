<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->text('description');
            $table->enum('type', ['cart', 'cart%','product%']);
            $table->enum('status', ['draft', 'publish']);
            $table->dateTime('published_at');
            $table->float('amount');
            $table->boolean('is_free_shipping');
            $table->date('expiry_date');
            $table->float('max_spend');
            $table->float('min_spend');
            $table->boolean('is_individual');
            $table->boolean('exclude_sale_items');
            $table->boolean('show_on_cart');
            $table->text('products');
            $table->text('exclude_products');
            $table->text('categories');
            $table->text('exclude_categories');
            $table->integer('usage_limit_coupon');
            $table->integer('usage_limit_user');
            $table->integer('usage_count')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('coupons');
    }
}
