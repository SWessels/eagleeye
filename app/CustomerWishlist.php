<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class customerWishlist extends BaseModel
{
    ////////////////BELONGS TO RELATION WITH CUSTOMER//////////////
    public $table = "customer_wishlist";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }

    //add routine for customer shipping section
    public function add($data){
        customerWishlist::insert($data);
    }
}
