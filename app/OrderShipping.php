<?php

namespace App;
use DB;

class OrderShipping extends BaseModel
{
    protected $fillable = [
        'first_name', 'last_name', 'address_1', 'address_2', 'city', 'state' ,'postcode','country','email','phone','order_id'
    ];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    //add routine for order shipping section
    public static function add($data){
        DB::table('order_shippings')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
}
