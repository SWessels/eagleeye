<?php

namespace App\Http\Controllers;

use App\Pricing;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Products;
use App\Categories;
use App\Coupons;
use Session;
use App\Functions\Functions;
use Illuminate\Support\Facades\View;


class PricingController extends BaseController
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    private $product;
    private $category;
    private $coupons;
    private $pricing;
    private $inventories;
    private $customer;

    protected $connection;

    public function __construct(Request $request)
    {
        parent::__construct();
        // check if user has products capability
        if(User::userCan('pricing') === false)
        {
            abort(403, 'Unauthorized action.');
        }

        $this->product 		= new Products();
        $this->category 	= new Categories();
        $this->coupons 	    = new Coupons();
        $this->pricing 		= new Pricing();
        $this->connection   = Session::get('connection');


    }

    public function index()
    {

        $pricing        = $this->pricing->getAllPricing();

       /* $pricing_actions = array();
        foreach ($pricing as $action)
        {
            $pricing_actions[] = $action;
            $pricing_ids    = $this->pricing->getPricingIds($action->id);
            $p_ids          = array();
            foreach ($pricing_ids as $pricing_id)
            {
                $p_ids[] = $pricing_id->applies_on;
            }
        }


        $exclude_ids    = $this->pricing->getExcludeIds($id);
        $e_ids          = array();
        foreach ($exclude_ids as $exclude_id)
        {
            $e_ids[] = $exclude_id->product_id;
        }*/

        return view('pricing.index', ['pricing' => $pricing]);
    }

    public function create()
    {
        $products       = $this->product->getAllProducts();
        $categories     = $this->category->getCategories(0, 0, 0) ;
        $coupons        = $this->coupons->getCoupons();
        $orderValues    = $this->pricing->getOrderValues();

        return view('pricing.create', [
            'products' => $products,
            'categories' => $categories,
            'coupons' => $coupons ,
            'order_values' => $orderValues
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all() ;

        $pricing = array();
        $pricing_ids = array();
        $exclude_ids = array();
        $order_value_ids    = array();


        $pricing['title']                      = $input['action_title'];
        //$pricing['eligible_customer']          = $input['eligible_customers_type'];
        if($input['min_past_orders'] != '' && $input['eligible_customers_type'] == 'specific')
        {
            $pricing['min_past_orders']            = $input['min_past_orders'];
        }else{
            $pricing['min_past_orders']            = 0;
        }

        $from_date                             = $input['p_from_year'].'-'.$input['p_from_month'].'-'.$input['p_from_day']. ' '.$input['p_from_hour'].':'.$input['p_from_min'].':00';
        $pricing['date_from']                  = date('Y-m-d H:i:s', strtotime($from_date));

        $to_date                               = $input['p_to_year'].'-'.$input['p_to_month'].'-'.$input['p_to_day']. ' '.$input['p_to_hour'].':'.$input['p_to_min'].':00';
        $pricing['date_to']                    = date('Y-m-d H:i:s', strtotime($to_date));

        $method                                = $input['pricing_method'];

        switch ($method){
            case 1:
                $pricing['discount_type']               = 'percentage';
                $pricing['discount_value']              = $input['p_discount'];
                $applies_on                             = $input['p_applies_on'];
                $pricing['applies_on']                  = $applies_on;
                if(isset($input['p_all_exclude']))
                {
                    $exclude_ids['exclude']                     = $input['p_all_exclude'];
                }

                if(is_array($input['p_order_value']))
                {
                    $order_value_ids               = $input['p_order_value'];
                    $pricing['order_value_id']              = 0;
                }else{
                    $pricing['order_value_id']              = $input['p_order_value'];
                }

                $pricing['min_products_order']          = $input['p_number_of_products'];
                $pricing['visible_in']                  = $input['p_visible_in'];

                if($input['p_coupon_based'] == 'yes')
                {
                    $pricing['coupon_id']                   = $input['p_coupon_id'];
                }else{
                    $pricing['coupon_id']                   = 0;
                }


                if($applies_on == 'category')
                {
                    $pricing_ids['applies_on']                = $input['p_applies_category'];
                }


                if($applies_on == 'products')
                {
                    $pricing_ids['applies_on']                  = $input['p_applies_on_products'];
                }

                break;
            case 2:
                $pricing['discount_type']               = 'fixed';
                $pricing['discount_value']              = $input['f_discount'];
                $applies_on                             = $input['f_applies_on'];
                $pricing['applies_on']                  = $applies_on;
                if(isset($input['f_all_exclude']))
                {
                    $exclude_ids['exclude']                     = $input['f_all_exclude'];
                }


                if(is_array($input['f_order_value']))
                {
                    $order_value_ids               = $input['f_order_value'];
                    $pricing['order_value_id']              = 0;
                }else{
                    $pricing['order_value_id']              = $input['f_order_value'];
                }

                $pricing['min_products_order']          = $input['f_number_of_products'];
                $pricing['visible_in']                  = $input['f_visible_in'];

                if($input['f_coupon_based'] == 'yes')
                {
                    $pricing['coupon_id']                   = $input['f_coupon_id'];
                }else{
                    $pricing['coupon_id']                   = 0;
                }


                if($applies_on == 'category')
                {
                    $pricing_ids['applies_on']                = $input['f_applies_on_category'];
                }


                if($applies_on == 'products')
                {
                    $pricing_ids['applies_on']                  = $input['f_applies_on_products'];
                }
                break;
            case 3:
                $pricing['discount_type']               = 'freeshipping';
                $pricing['min_order_value']               = $input['fs_min_order_value'];


                $pricing['visible_with_success']           = $input['fs_visible_on_success'];

                break;
            case 4:
                $pricing['discount_type']                   = 'freeproduct';
                $pricing['min_products_order']	            = $input['fp_number_of_products'];
                $applies_on                                 = $input['fp_applies_on'];
                $pricing['applies_on']                      = $applies_on;
                if($applies_on == 'category')
                {
                    $pricing_ids['applies_on']              = $input['fp_applies_on_category'];
                }


                if($applies_on == 'products')
                {
                    $pricing_ids['applies_on']              = $input['fp_applies_on_products'];
                }

                $use_as_discount                            = $input['fp_as_discount'];
                $pricing['use_as_discount']                 = $use_as_discount ;
                if($use_as_discount == 'product')
                {
                    $pricing['use_as_discount_product']                 = $input['fp_use_as_discount'];
                }else{
                    $pricing['use_as_discount_product']                 = '0';
                }


                break;
        }

        if(isset($input['action_id']))
        {
            $save_pricing = $this->pricing->savePricing($pricing, $pricing_ids, $exclude_ids, $order_value_ids, $input['action_id']);
        }else{
            $save_pricing = $this->pricing->savePricing($pricing, $pricing_ids, $exclude_ids, $order_value_ids);
        }


        if(is_array($save_pricing))
        {
            if(isset($input['action_id'])) {
                Session::flash('error_message', 'Action update Failed!');
                return redirect()->action('PricingController@edit', ['id' => $input['action_id']]);
                exit;
            }else{
                Session::flash('error_message', 'Action create failed!');
                return redirect()->action('PricingController@create');
            }
        }else{

            if(isset($input['action_id']))
            {
                Session::flash('flash_message', 'Action Successfully Updated!');
                return redirect('actions');
                //echo json_encode(array('action' => 'true', 'msg' => 'Action Updated successfully'));
                exit;
            }else{

                Session::flash('flash_message', 'Action Successfully Created!');
                return redirect()->action('PricingController@index');
                //echo json_encode(array('action' => 'true', 'msg' => 'Action saved successfully'));
                exit;
            }
        }


    }

    public function edit($id)
    {
        if(Functions::checkIdInDB($id, 'pricing') == 0)
        {
            abort(404, 'Not Found.');
        }
        $products       = $this->product->getAllProducts();
        $categories     = $this->category->getCategories(0, 0, 0) ;
        $coupons        = $this->coupons->getCoupons();

        $pricing        = $this->pricing->getPricingById($id);

        $pricing_ids    = $this->pricing->getPricingIds($id);
        $p_ids          = array();
        foreach ($pricing_ids as $pricing_id)
        {
            $p_ids[] = $pricing_id->applies_on;
        }

        $exclude_ids    = $this->pricing->getExcludeIds($id);
        $e_ids          = array();
        foreach ($exclude_ids as $exclude_id)
        {
            $e_ids[] = $exclude_id->product_id;
        }

        $orderValues    = $this->pricing->getOrderValues();

        $pricing_categories     = $this->pricing->getPricingCategories($id);
        $p_cat = [];
        foreach ($pricing_categories   as $pricing_category  )
        {
            $p_cat[] = $pricing_category->order_value_id;
        }

        return view('pricing.edit', [
            'pricing'       => $pricing,
            'pricing_ids'   => $p_ids,
            'exclude_ids'   => $e_ids,
            'products'      => $products,
            'categories'    => $categories,
            'coupons'       => $coupons,
            'order_values'   => $orderValues,
            'pricing_categories'    => $p_cat
        ]);
    }


    public function destroy($id)
    {
        //
        $pricing = Pricing::find($id);
        if($pricing->forceDelete())
        {
            $this->pricing->deletePricingData($id);
            Session::flash('flash_message', 'Action Successfully Deleted!');
            return redirect()->action('PricingController@index');
        }else{
            Session::flash('error_message', 'Error Deleting Action.');
            return redirect()->action('PricingController@edit', ['id' =>  $id]);
        }
    }


}
