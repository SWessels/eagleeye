<?php

namespace App\Http\Controllers;

use App\Orders;
use App\OrderItems;
use App\PredictiveStocks;
use App\Products;
use App\RefundItems;
use App\Refunds;
use App\Inventories;
use Illuminate\Http\Request;

use App\Http\Requests;
use Config;

class ProductAvg extends Controller
{
    public function index(){
        set_time_limit(0);
        echo 'Started calculating averages........<br/>';
        $domains = Config::get('domains');
        foreach($domains as $key => $value) {
            session(['connection' => $key]);


            for ($dayindex = 0; $dayindex < 14; $dayindex++) {
                $date = date('Y-m-d', strtotime(date("Y-m-d") . '  -' . $dayindex . ' day'));
                $objOrder = new Orders();
                $orders = $objOrder->getOrders($date);

                $data = null;
                $ref_data = null;
                foreach ($orders as $order) {
                    $data[] = $order->id;
                }

                $objRefunds = new Refunds();
                $refunds = $objRefunds->getRefunds($date);

                foreach($refunds as $refund){
                    $ref_data[] = $refund->id;
                }
                $returned = null;
                if($ref_data != null) {
                    $objRefundItems = new RefundItems();
                    $returned = $objRefundItems->getRefundAvg($ref_data);
                }
                $product = new Products();
                if ($data != null) {
                    $objItems = new OrderItems();
                    $results = $objItems->getItemsAvg($data);
                    foreach ($results as $average) {
                        $sku = $product->getSKUbyId($average->product_id);
                        if($sku['sku'] == null)
                            $sku['sku'] = '0';

                        $total_return = 0;

                        if($returned != null)
                        foreach($returned as $returnItem){
                            if($returnItem->product_id == $average->product_id){
                                $total_return = $returnItem->product_count;
                                break;
                            }

                        }
                        $predict = PredictiveStocks::firstOrCreate([
                            'date_entry' => $date,
                            'product_id' => $average->product_id,
                            'sku' => $sku['sku'],
                            'domain_id' => $key
                        ]);
                        $predict->avg = $average->product_count - $total_return;
                        $predict->refunds = $total_return;

                        $predict->save();
                    }
                }

            }

            //added to calculate the yellow status
            $products = Products::all();
            $predtivestocks = new PredictiveStocks();
            $objInventory = new Inventories();


            foreach($products as $product){
                //dd($predtivestocks->average($product->sku));
                $inventory = $objInventory->getInventoryByProductSKU($product->sku);
                $avg = round($predtivestocks->average($product->sku) / 14 , 2);
                $refund = round($predtivestocks->refunds($product->sku) / 14 , 2);
                $difference = $avg -  $refund;
                //dd($avg);
                //$include_stock = false;

                /*if($inventory['product_horizon'] == 0)
                    $timespan_for_stock = $inventory['days_for_delivery'];//stock in advance
                else {
                    $timespan_for_stock = $inventory['product_horizon'];
                   // $include_stock = true;
                }*/

                if($difference != 0) {
                    //dd($product->sku);
                    $days_to_outstock = (int)($inventory['stock_qty'] / $difference);
                    /* $date = strtotime("+" . $days_to_outstock . " day");
                     $date_outstock = date('d-m-Y', $date);*/

                    if(($days_to_outstock <= $inventory['days_for_delivery']) && $inventory['stock_qty'] > 0) {
                        $product->near_stock = true;
                        $product->save();
//                    $amount_required = ceil($avg * $timespan_for_stock);
//
//                    if($include_stock)
//                        $amount_required = $amount_required - $inventory['stock_qty'];
//
//                    if($inventory['stock_qty'] < 0)
//                        $amount_required = $amount_required  - $inventory['stock_qty'];
                    }
                    else {
                        $product->near_stock = false;
                        $product->save();
                    }
                }
                else{
                    $product->near_stock = false;
                    $product->save();
                    //dd($product);
                }

        }



        }
        echo 'completed calculating product average!';

    }
}
