<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Components extends BaseModel
{
    //

    public  $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function products(){
        return $this->belongsTo('App\Products', 'product_id');
    }
}
