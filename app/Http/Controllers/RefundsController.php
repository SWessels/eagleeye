<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class RefundsController extends BaseController
{
 
    protected $connection;

    public function __construct()
    {
        parent::__construct();
        // check if user has employees capability
        if (User::userCan('refunds') === false) {
            abort(403, 'Unauthorized action.');
        }

        $this->connection = Session::get('connection');
    }
}
