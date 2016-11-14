<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class CustomerBilling extends BaseModel
{
    //add routine for customer billing section
    public $table = "customer_billing";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    ////////////////BELONGS TO RELATION WITH CUSTOMER//////////////

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id' , 'id');
    }
    public function add($data){
        CustomerBilling::insert($data);
    }
}
