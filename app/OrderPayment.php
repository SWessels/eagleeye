<?php

namespace App;
use DB; 

class OrderPayment extends BaseModel
{
    protected $fillable = [
        'payment_method_id', 'title', 'paid', 'txn_id', 'order_id'
    ];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

     //add routine for payment information of orders
    public static function add($data){
        DB::table('order_payments')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
}
