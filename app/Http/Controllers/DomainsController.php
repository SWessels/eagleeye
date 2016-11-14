<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\Domains;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Config;
use Session; 

class DomainsController extends Controller
{

    public function ChangeConnection($domain)
    {
        //$active_url = Session::get('_previous')['url'];
        session(['connection' => $domain]);
        $baseModel = new BaseModel();

        return redirect('/');
    }
}
