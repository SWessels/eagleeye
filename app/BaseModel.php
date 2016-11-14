<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Config;
use Session;
use DB;

class BaseModel extends Model
{
    //
    protected $connection;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if(Session::has('connection'))
        {
            foreach (Config::get('domains') as $domain => $domain_url)
            {
                if(Session::get('connection') == $domain)
                {
                    $this->connection = Session::get('connection');
                    break;
                } 
            }

        }else{
            $this->connection = Config::get('database.default');
        }
        //echo $this->connection;
    } 
}
