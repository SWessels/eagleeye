<?php

namespace App;
 

class LinkedProducts extends BaseModel
{
    //

    public  $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function products(){
        return $this->belongsTo('App\Products');
    }
}
