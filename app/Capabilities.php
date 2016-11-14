<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capabilities extends Model
{
    //

    protected $connection = 'eagleeye';


    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    public static function userCapabilities($id)
    {

    }


}
