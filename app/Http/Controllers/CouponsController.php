<?php

namespace App\Http\Controllers;
use App\Functions\Functions;
use App\User;
use App\Products;
use App\Categories;
use App\Coupons;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use Session;
use DB;




class CouponsController extends BaseController
{
    private $coupon;
    public function __construct()
    {
        parent::__construct();
        // check if user has products capability
        if(User::userCan('coupons') === false)
        {
            abort(403, 'Unauthorized action.');
        }
        $this->coupon = new Coupons;
        $this->product = new Products;
        $this->user = new User;
        $this->category = new Categories;


    }

    public function index(){


        $coupons_counts = $this->coupon->getCouponsCounts();
        return view('coupons.coupons'  ,['coupons_counts' => $coupons_counts] );
    }

    public function create(){

        $all_categories  = $this->category->getCategories();
        $all_products  = $this->product->getProductsForCoupons();

        $data = array('categories' => $all_categories, 'products' => $all_products);

        return view('coupons.addcoupons', ['data' => $data] );
    }
    public function store(Request $request){

         $input = $request->all();
     //  echo "<pre>";print_r($input);echo "</pre>";exit;
         $coupon_inserted_id = $this->coupon->addNewCoupon($input);
         $data = array('id'=> $coupon_inserted_id);

        Session::flash('flash_message', 'Coupon Successfully Created!');
        return redirect()->action('CouponsController@edit',['id' => $coupon_inserted_id]);
    }

    public function edit($id){

        $id_exists = Functions::checkIdInDB($id ,$this->coupon->table);
        if($id_exists > 0) {
            $all_categories = $this->category->getCategories();
            $all_products = $this->product->getProductsForCoupons();
            $coupon_byId  = $this->coupon->getCouponById($id);
            $data = array('categories' => $all_categories, 'products' => $all_products, 'counponById' => $coupon_byId);
            return view('coupons.editcoupons', ['data' => $data] );

        }
        else{
            abort(404, 'Not Found.');
        }


    }
    public function deleteCouponById($id){

        $delcoupon_byId  = $this->coupon->delCouponById($id);
        $coupons_counts = $this->coupon->getCouponsCounts();
        Session::flash('flash_message', 'Coupon Successfully Trashed!');
        return view('coupons.coupons'  ,['coupons_counts' => $coupons_counts]);
        
    }
    public function copyCouponById($id){

        $ccoupon_byId  = $this->coupon->copyCouponById($id);
        Session::flash('flash_message', 'Coupon Successfully Copied!');
        return redirect()->action('CouponsController@index');

    }
    public function update(Request $request, $id){
        $input = $request->all();
        $id = $input['coupon_id'];
      // echo "<pre>";print_r($input);echo "</pre>";exit;

        $update_coupon = $this->coupon->updateCoupon($input);
        Session::flash('flash_message', 'Coupon Successfully Updated!');
        return redirect()->action('CouponsController@edit',['id' => $id]);
        
        
    }

    public function getAllCoupons($perpage ,$pageno){
        
      return $all_coupons = $this->coupon->get_allCoupons($perpage ,$pageno);


    }
    public function delete(Request $request){
        $this->validate($request, ['remove' => 'required'], ['remove.required' => 'Select atleast one action to apply!']);
        $this->validate($request, ['del' => 'required'], ['del.required' => 'Select atleast one page to remove!']);
        $input = $request->all();
        $delete_coupon = $this->coupon->deleteCoupon($input);
        Session::flash('flash_message', 'Coupons Successfully Removed!');

        return redirect('coupons');

    }
    public function getProducts(Request $request){
        $input = $request->all();
       // print_r($input);
        return  $get_products = $this->coupon->get_AllProducts($input);
       
    }
    public function getCategories(Request $request){
        $input = $request->all();
        // print_r($input);
        return  $get_categories = $this->coupon->get_AllCategories($input);

    }
}