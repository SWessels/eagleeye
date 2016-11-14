<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderCoupons extends Model
{
    protected $table = "order_coupons";
    public $timestamps = false;
    protected $fillable = ['order_id','coupon_id'];
}
