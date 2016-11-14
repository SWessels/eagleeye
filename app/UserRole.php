<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    protected $connection = 'eagleeye';

    protected $name = ['name'];

    public static function getRoleName($id)
    {
        return UserRole::find($id);
    }

}
