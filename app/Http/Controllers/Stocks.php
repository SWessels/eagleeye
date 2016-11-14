<?php

namespace App\Http\Controllers;

use App\Inventories;
use App\PredictiveStocks;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;

class Stocks extends BaseController
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        parent::__construct();
        // check if user has products capability
        if (User::userCan('products') === false) {
            abort(403, 'Unauthorized action.');
        }
    }
    //
    public function index(Request $request){
        $predtivestocks = new PredictiveStocks();
        $count = $predtivestocks->counts();
        $request_all = $request->all();
        $args = null;
        $status = null;

        if(isset($request_all['keyword']))
            $args = $request_all['keyword'];

        if(isset($request_all['filter']))
            $status = $request_all['filter'];

        $results = $predtivestocks->listing($args,$status);

        $inventory = null;
        $avg = null;
        $attribute = null;
        $amount_required = null;
        $date_outstock = null;
        $refund = null;
        
        $objInventory = new Inventories();
        foreach($results as $product){
           
            $attr_data = unserialize($product['relations']['attributes']['attributes']['attributes']);

            if(!empty($attr_data)) {
                foreach ($attr_data as $attr) {
                    if (!is_array($attr))
                        $attribute[$product['id']] = $attr;

                }
            }
            else {
                $attribute[$product['id']] = null;
            }
            
            $inventory[$product['id']] = $objInventory->getInventoryByProductSKU($product['sku']);
            $avg[$product['id']] = round($predtivestocks->average($product['sku']) / 14 , 2);
            $refund[$product['id']] = round($predtivestocks->refunds($product['sku']) / 14 , 2);
            $difference = $avg[$product['id']] -  $refund[$product['id']];
            $margin = .2;
            $include_stock = false;

            if($inventory[$product['id']]['product_horizon'] == 0)
                $timespan_for_stock = $inventory[$product['id']]['days_for_delivery'];//stock in advance
            else {
                $timespan_for_stock = $inventory[$product['id']]['product_horizon'];
                $include_stock = true;
            }

            if($difference != 0) {

                $days_to_outstock = (int)($inventory[$product['id']]['stock_qty'] / $difference);
                $date = strtotime("+" . $days_to_outstock . " day");
                $date_outstock[$product['id']] = date('d-m-Y', $date);

                if($days_to_outstock <= $inventory[$product['id']]['days_for_delivery']) {
                    $amount_required[$product['id']] = ceil($avg[$product['id']] * $timespan_for_stock);

                    if($include_stock)
                    $amount_required[$product['id']] = $amount_required[$product['id']] - $inventory[$product['id']]['stock_qty'];

                    if($inventory[$product['id']]['stock_qty'] < 0)
                    $amount_required[$product['id']] = $amount_required[$product['id']]  - $inventory[$product['id']]['stock_qty'];
                }
                else
                    $amount_required[$product['id']] = 0;
            }
            else{
                $date_outstock[$product['id']] = "-";
                $amount_required[$product['id']] ='-';
            }

        }

        $pagination = $request->session()->get('pagination', 50);

        return view('reports.stocks', ['stocks' => $results, 'counts'	=> $count ,'amount_required'=>$amount_required, 'date_outstock' => $date_outstock,'inventory' => $inventory , 'average' => $avg , 'attribute' =>$attribute , 'refund' => $refund , 'pagination'=>$pagination]);
    }

    // used to update delivery time in inventory
    public function updateDelivery(Request $request){
        $request_all = $request->all();
        $objInventory = new Inventories();
        $inventory = $objInventory->getInventoryByProductSKU($request_all['sku']);
        $inventory->days_for_delivery = $request_all['delivery'];
        $inventory->save();
        echo $request_all['delivery'];
    }

    // used to update product horizon in inventory
    public function updateHorizon(Request $request){
        $request_all = $request->all();
        $objInventory = new Inventories();
        $inventory = $objInventory->getInventoryByProductSKU($request_all['sku']);
        $inventory->product_horizon = $request_all['horizon'];
        $inventory->save();
        echo $request_all['horizon'];
    }

    public function pagination(Request $request)
    {
        $request_all = $request->all();
        session(['pagination' => $request_all['pagination']]);

    }
}
