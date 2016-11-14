<?php

namespace App;


class Metas extends BaseModel
{
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public  $timestamps = false;

    public function products(){
        return $this->belongsTo('App\Products', 'product_id');
    }

    public function deleteMeta($metaName = null, $productId = null)
    {
        if($metaName && $productId)
        {
            return Metas::where('meta_name', $metaName )->where('product_id', $productId)->delete();
        }

    }
}
