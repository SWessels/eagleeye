<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCapabilities extends Model
{
    // user capabilities

    protected $connection = 'eagleeye'; 

    public  $timestamps = false;

    protected $fillable = [
        'user_id', 'capabilities_id'
    ];




}
