<?php

namespace App;
use DB; 

class OrderBilling extends BaseModel
{
    protected $fillable = [
        'first_name', 'last_name', 'address_1', 'address_2', 'city', 'state' ,'postcode','country','email','phone','order_id'
    ];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    //add routine for orders billing section
    public static function add($data){
        DB::table('order_billings')->insert($data);
        return DB::getPdo()->lastInsertId();
    }
}
