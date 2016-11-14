<?php

namespace App;

use App\Functions\Functions;
use App\Input;
use App\Categories;
use App\Products;
use App\Media;

use DB;
use Auth;
class Coupons extends BaseModel
{
    public $timestamps = false;
    public $table = "coupons";
    protected $fillable = [
        'id' , 'code',  'description' , 'type', 'status', 'published_at', 'amount',
        'is_free_shipping',
        'expiry_date',
        'max_spend',
        'min_spend',
        'is_individual',
        'show_on_cart',
        'exclude_sale_items',
        'products',
        'exclude_products',
        'categories',
        'exclude_categories',
        'usage_limit_coupon',
        'created_at',
        'usage_limit_user',
        'usage_count'
        ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getCoupons()
    {
        return Coupons::where('status', 'publish')->where('expiry_date', '>', date('Y-m-d H:i:s'))->get();
    }
    public function getCouponsCounts(){

        $counts = array('all' => 0, 'published' => 0, 'draft' => 0);

        $all = Coupons::count();
        $counts['all'] = $all ;

        $query = Coupons::query('');
        $query->where('status', '=', 'publish');
        $counts['published'] = $query->count();

        $query = Coupons::query('');
        $query->where('status', '=', 'draft');
        $counts['draft'] = $query->count();


        return $counts;

    }
    public function addNewCoupon($input){

        $code = $input['code'];
        $description = $input['description'];
        $discount_type = $input['discount_type'];
        $coupon_amount = $input['coupon_amount'];
        if(isset($input['is_shipping']) && $input['is_shipping'] =='on' ){
          $is_shipping =  $input['is_shipping'] = 1;
        }
        else{
             $is_shipping = 0;
        }
        $expiry_date_str = strtotime($input['coupon_expiry_date']);
        $expiry_date = date('Y-m-d',$expiry_date_str );

        $min_spend = $input['min_spend'];
        $max_spend = $input['max_spend'];
        if(isset($input['is_individual']) && $input['is_individual'] =='on' ){
            $is_individual =  $input['is_individual'] = 1;
        }
        else{
            $is_individual = 0;
        }
        if(isset($input['is_exclude']) && $input['is_exclude'] =='on' ){
            $is_exclude =  $input['is_exclude'] = 1;
        }
        else{
            $is_exclude = 0;
        }

        if(isset($input['action']) && $input['action']  == 'draft'){

            $status = 'draft';
        }
        elseif(isset($input['action']) && $input['action']  == 'publish'){
            $status =  trim($input['status']);
        }
        if(isset($input['show_cart']) && $input['show_cart'] =='on' ){
            $show_cart =  $input['show_cart'] = 1;
        }
        else{
            $show_cart = 0;
        }
        if(isset($input['products'])){
            $products_array = array();
            foreach($input['products'] as $pid){

            $p =    DB::connection($this->connection)->table('products')->where('id', '=',$pid)->select('name')->first();
            $name =  $p->name;
                $products_array[] =[ $pid , $name];

            }
        echo   $products = serialize($products_array);

         }
        else{ $products = '' ;}

        if(isset($input['exc_products'])) {
            $exc_products_array = array();
            foreach($input['exc_products'] as $epid){

                $p =    DB::connection($this->connection)->table('products')->where('id', '=',$epid)->select('name')->first();
                $name =  $p->name;
                $exc_products_array[] =[ $epid , $name];

            }


            $exc_products = serialize($exc_products_array);
        }
        else{ $exc_products = '' ;}

        if(isset($input['categories'])) {

            $categories_array = array();
            foreach($input['categories'] as $cid){

                $p =    DB::connection($this->connection)->table('categories')->where('id', '=',$cid)->select('name')->first();
                $name =  $p->name;
                $categories_array[] =[ $cid , $name];

            }

        $categories = serialize($categories_array);
        }
        else{ $categories = '' ;}

        if(isset( $input['exc_categories'])){

            $exc_categories_array = array();
            foreach($input['exc_categories'] as $ecid){

                $p =    DB::connection($this->connection)->table('categories')->where('id', '=',$ecid)->select('name')->first();
                $name =  $p->name;
                $exc_categories_array[] =[ $ecid , $name];

            }

        $exc_categories = serialize($exc_categories_array);
        }
        else{ $exc_categories = '' ;}

        $limit_coupon = $input['limit_coupon'];
        $limit_user = $input['limit_user'];
        $published_at = $input['yy'].'-'.$input['mm'].'-'.$input['dd'].' '.$input['hr'].':'.$input['min'].':00';
       // exit;
        $data_insert = array('code' => $code,
            'description' => $description,
            'type' => $discount_type,
            'status' => $status,
            'published_at' => $published_at,
            'amount' => $coupon_amount,
            'is_free_shipping' => $is_shipping,
            'expiry_date' => $expiry_date,
            'max_spend' => $max_spend,
            'min_spend' => $min_spend,
            'is_individual' =>$is_individual,
            'show_on_cart' =>$show_cart,
            'exclude_sale_items' => $is_exclude,
            'products' => $products,
            'exclude_products' => $exc_products,
            'categories' => $categories,
            'exclude_categories' => $exc_categories,
            'usage_limit_coupon' => $limit_coupon,
            'created_at'		=> date('Y-m-d H:i:s'),
            'usage_limit_user' =>$limit_user);

        Coupons::insert($data_insert);
       return $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
        
        
       }

    public function getCouponById($id){

      return  Coupons::where('id',$id)->first();

    }
    public function updateCoupon($input){

        $id  = $input['coupon_id'];
        $code = $input['code'];
        $description = $input['description'];
        $discount_type = $input['discount_type'];
        $coupon_amount = $input['coupon_amount'];
        if(isset($input['is_shipping']) && $input['is_shipping'] =='on' ){
            $is_shipping =  $input['is_shipping'] = 1;
        }
        else{
            $is_shipping = 0;
        }
        $expiry_date_str = strtotime($input['coupon_expiry_date']);
        $expiry_date = date('Y-m-d',$expiry_date_str );

        $min_spend = $input['min_spend'];
        $max_spend = $input['max_spend'];
        if(isset($input['is_individual']) && $input['is_individual'] =='on' ){
            $is_individual =  $input['is_individual'] = 1;
        }
        else{
            $is_individual = 0;
        }
        if(isset($input['is_exclude']) && $input['is_exclude'] =='on' ){
            $is_exclude =  $input['is_exclude'] = 1;
        }
        else{
            $is_exclude = 0;
        }

        if(isset($input['action']) && $input['action']  == 'draft'){

           $status = 'draft';
        }
        elseif(isset($input['action']) && $input['action']  == 'publish'){
           $status =  trim($input['status']);
        }
        if(isset($input['show_cart']) && $input['show_cart'] =='on' ){
            $show_cart =  $input['show_cart'] = 1;
        }
        else{
            $show_cart = 0;
        }

        if(isset($input['products'])){
            $products_array = array();
            foreach($input['products'] as $pid){

                $p =    DB::connection($this->connection)->table('products')->where('id', '=',$pid)->select('name')->first();
                $name =  $p->name;
                $products_array[] =[ $pid , $name];

            }
            $products = serialize($products_array);

        }
        else{ $products = '' ;}

        if(isset($input['exc_products'])) {
            $exc_products_array = array();
            foreach($input['exc_products'] as $epid){

                $p =    DB::connection($this->connection)->table('products')->where('id', '=',$epid)->select('name')->first();
                $name =  $p->name;
                $exc_products_array[] =[ $epid , $name];

            }


            $exc_products = serialize($exc_products_array);
        }
        else{ $exc_products = '' ;}

        if(isset($input['categories'])) {

            $categories_array = array();
            foreach($input['categories'] as $cid){

                $p =    DB::connection($this->connection)->table('categories')->where('id', '=',$cid)->select('name')->first();
                $name =  $p->name;
                $categories_array[] =[ $cid , $name];

            }

         $categories = serialize($categories_array);
        }
        else{ $categories = '' ;}
       // exit;
        if(isset( $input['exc_categories'])){

            $exc_categories_array = array();
            foreach($input['exc_categories'] as $ecid){

                $p =    DB::connection($this->connection)->table('categories')->where('id', '=',$ecid)->select('name')->first();
                $name =  $p->name;
                $exc_categories_array[] =[ $ecid , $name];

            }

            $exc_categories = serialize($exc_categories_array);
        }
        else{ $exc_categories = '' ;}
        $limit_coupon = $input['limit_coupon'];
        $limit_user = $input['limit_user'];
        $published_at = $input['yy'].'-'.$input['mm'].'-'.$input['dd'].' '.$input['hr'].':'.$input['min'].':00';



        $data_update = array('code' => $code,
            'description' => $description,
            'type' => $discount_type,
            'status' => $status,
            'published_at' => $published_at,
            'amount' => $coupon_amount,
            'is_free_shipping' => $is_shipping,
            'expiry_date' => $expiry_date,
            'max_spend' => $max_spend,
            'min_spend' => $min_spend,
            'is_individual' =>$is_individual,
            'show_on_cart' =>$show_cart,
            'exclude_sale_items' => $is_exclude,
            'products' => $products,
            'exclude_products' => $exc_products,
            'categories' => $categories,
            'exclude_categories' => $exc_categories,
            'usage_limit_coupon' => $limit_coupon,
            'updated_at'		=> date('Y-m-d H:i:s'),
            'usage_limit_user' =>$limit_user);

        Coupons::where('id','=', $id)->update($data_update);
       
    }

    public function get_allCoupons($perpage ,$pageno){

        $skip = ($pageno-1)*$perpage;
        $result = Coupons::orderBy('id', 'desc')->skip($skip)->take($perpage)->get();
        // dd($result);
        
        $total_count = Coupons::count();
        $data_array= array('error' => 0 , 'total_count'=>$total_count , 'data'=>   $result );

        return json_encode($data_array);

    }

    public function delCouponById($id){

        return Coupons::where('id' ,$id)->update(['status' => 'draft']);
    }
    public function copyCouponById($id){

      $coupon_details =  Coupons::where('id' ,$id)->first();
      $coupon_code = $coupon_details['code'];
      $new_coupon_code =  $this->makeCodeById($coupon_code, $duplicates_count = 0,$id);


        $data_copy = array('code' => $new_coupon_code,
            'description' => $coupon_details['description'],
            'type' => $coupon_details['type'],
            'status' => $coupon_details['status'],
            'published_at' => $coupon_details['published_at'],
            'amount' => $coupon_details['amount'],
            'is_free_shipping' => $coupon_details['is_free_shipping'],
            'expiry_date' => $coupon_details['expiry_date'],
            'max_spend' => $coupon_details['max_spend'],
            'min_spend' => $coupon_details['min_spend'],
            'is_individual' => $coupon_details['is_individual'],
            'show_on_cart' => $coupon_details['show_on_cart'],
            'exclude_sale_items' => $coupon_details['exclude_sale_items'],
            'products' => $coupon_details['products'],
            'exclude_products' => $coupon_details['exclude_products'],
            'categories' => $coupon_details['categories'],
            'exclude_categories' => $coupon_details['exclude_categories'],
            'usage_limit_coupon' => $coupon_details['usage_limit_coupon'],
            'created_at'		=> date('Y-m-d H:i:s'),
            'usage_limit_user' => $coupon_details['usage_limit_user'] );

       return Coupons::insert($data_copy);



    }
    public function makeCodeById($title, $duplicates_count = 0)
    {
        $duplicates_count = (int) $duplicates_count ;

        $slug = $title = str_slug($title);


        if ($duplicates_count > 0) {
            $slug = $slug.'-'.$duplicates_count;
            $rowCount = DB::connection($this->connection)->table($this->getTable())->where('code', $slug)->count();
            if ($rowCount > 0) {
                return $this->makeCodeById($title, ++$duplicates_count);
            } else {
                return $slug;
            }
        } else {
            $rowCount = DB::connection($this->connection)->table($this->getTable())->where('code', $title)->count();
            if ($rowCount > 0) {
                return $this->makeCodeById($title, ++$duplicates_count);
            } else {
                return $title;
            }
        }

    }

    public function deleteCoupon($input){

        if(isset($input['remove'])  && $input['remove'] == 'rm'){

            foreach($input['del'] as $key => $del){

               // echo $del;
                // exit;
                Coupons::where('id', '=',$del)->update(['status' => 'draft']);

            }


        }


    }
    public function get_AllProducts($input){

        $keyword =  "%".str_replace('+','',$input['q'])."%";
        $products = DB::connection($this->connection)->table('products')->where('name' , 'like', $keyword) ->select('name as text', 'id')->get();

        return json_encode($products);

    }
    public function get_AllCategories($input){
        $keyword =  "%".str_replace('+','',$input['q'])."%";
        $categories = DB::connection($this->connection)->table('categories')->where('name' , 'like', $keyword) ->select('name as text', 'id')->get();

        return json_encode($categories);

    }
}