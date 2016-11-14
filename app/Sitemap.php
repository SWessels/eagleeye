<?php

namespace App;

use App\Functions\Functions;
use DB;



class Sitemap extends BaseModel
{

    public $timestamps = false;
    public $table = "sitemap_index";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

 //////////////////////////Media relation with posts/////////////////

    public function get_sitemap(){

      return  Sitemap::orderBy('id', 'asc')->get();
    }

    
}


?>