<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Countries extends BaseModel
{

    public $table = "countries";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }



    public function getCountries()
    {
        return Countries::all();
    }
}
