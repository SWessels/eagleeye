<?php

namespace App\Http\Controllers;

use App\Posts;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use DB;
use WpApi;

class Postlink extends BaseController
{
    public function __construct(Request $request)
    {

        parent::__construct();
    }
    public function index(){
    $posts = WpApi::posts(1);
        dd($posts);
}
}