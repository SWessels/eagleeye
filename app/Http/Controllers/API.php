<?php

namespace App\Http\Controllers;
use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;

class API extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
	  $prod = Products::all();
	  echo json_encode($prod);
	}
    //
}
