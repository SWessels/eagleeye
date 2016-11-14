<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\Domains;
use App\Inventories;
use App\Metas;
use App\Products;
use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use Config;
use DB;

use NumberFormatter;

class BaseController extends Controller
{
    protected $connection;
    public function __construct()
    {
        $this->middleware('auth');
        ini_set('max_execution_time', 200);

        if(Session::has('connection'))
        {
            $this->connection = Session::get('connection');
            unset($baseModel);
            $baseModel = new BaseModel();
        }else{
            session(['connection' => Config::get('database.default')]);
            $this->connection = Config::get('database.default');
            unset($baseModel);
            $baseModel = new BaseModel();
        }


    }

    public function ChangeConnection($domain)
    {
        session(['connection' => $domain]);
        $baseModel = new BaseModel(); 
        return redirect('/');
    }

    public function populateMasterInventory()
    {
 
        $tmh = DB::connection('themusthaves')->table('inventories')->get();

        foreach ($tmh as $item)
        {
            $inv_data = (array) $item;

            unset($inv_data['id']);
            if(Inventories::findBySKU($inv_data['product_sku']))
            {
                Inventories::where('product_sku', $inv_data['product_sku'])->update($inv_data);
            }else{
                Inventories::insert($inv_data);
            }
        }

        $mfr = DB::connection('musthavesforreal')->table('inventories')->get();

        foreach ($mfr as $item)
        {
            $inv_data = (array) $item;

            unset($inv_data['id']);
            if(Inventories::findBySKU($inv_data['product_sku']))
            {
                Inventories::where('product_sku', $inv_data['product_sku'])->update($inv_data);
            }else{
                Inventories::insert($inv_data);
            }

        }
    }
}
