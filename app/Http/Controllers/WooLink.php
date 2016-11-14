<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Customer;
use App\Coupons;
use App\CustomerBilling;
use App\CustomerShipping;
use App\OrderCoupons;
use App\OrderItems;
use App\OrderPayment;
use App\Orders;
use App\OrderBilling;
use App\OrderShipping;
use App\OrderNotes;
use App\Refunds;
use App\RefundItems;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use DB;
use Woocommerce;
use Session;

class WooLink extends Controller
{
	protected $connection;
	 public function __construct(Request $request)
    { 
		/*parent::__construct();
		$this->connection = Session::get('connection');*/

	}
    //
	public function index(){
		set_time_limit(0);
		dd(Woocommerce::get('orders',['page' => 1]));
		$total_orders = Woocommerce::get('orders/count');
		$total = $total_orders['count'];
		$pages = (int) ($total  / 10) + 1;
		for($page = 1; $page <= $pages ; $page++){
			$data = [
				'page' => $page
			];
			$result = Woocommerce::get('orders',$data);


			foreach($result['orders'] as $data){

				echo $data['id']."<br/>";
				
				unset($data['billing_address']['company']);
				unset($data['shipping_address']['company']);

				$data['billing_address']['order_id'] = $data['id'];
				$data['shipping_address']['order_id'] = $data['id'];

				foreach($data['line_items'] as $items) {
					if($items['product_id'] == '')continue;
				  OrderItems::firstOrCreate([
					  'order_id'        => $data['id'],
					  'product_id'		=> $items['product_id'],
					  'unit_price'		=> $items['price'],
					  'unit_tax'		=> $items['subtotal_tax'],
					  'qty'				=> $items['quantity'],
					  'total'			=> $items['total'],
					  'total_tax'		=> $items['total_tax'],
				  ]);
					
				}
				OrderBilling::firstOrCreate($data['billing_address']);
				OrderShipping::firstOrCreate($data['shipping_address']);
				OrderPayment::firstOrCreate([
					'payment_method_id'        => $data['payment_details']['method_id'],
					'title'=> $data['payment_details']['method_title'],
					'paid' =>$data['payment_details']['paid'] ,
					'txn_id'  =>'11',
					'order_id' => $data['id'],
				]);
				$order_flag = Orders::find($data['id']);
				if(count($order_flag) > 0){
					$order_flag->created_at = str_replace('T', ' ', str_replace('Z', '', $data['created_at']));
					$order_flag->updated_at = str_replace('T', ' ', str_replace('Z', '', $data['updated_at']));

					$order_flag->completed_at = str_replace('T', ' ', str_replace('Z', '', $data['completed_at']));
					$order_flag->amount = $data['total'];
					$order_flag->total_tax= $data['total_tax'];
					$order_flag->discount = $data['total_discount'];
					$order_flag->customer_id = $data['customer_id'];
					$order_flag->shipping_cost = 0;
					$order_flag->status = $data['status'];
					$order_flag->client_details = $data['customer_ip'].'|'.$data['customer_user_agent'];

					$order_flag->save();
				}
				else {
					Orders::firstOrCreate([
						'id' => $data['id'],
						'created_at' => str_replace('T', ' ', str_replace('Z', '', $data['created_at'])),
						'updated_at' => str_replace('T', ' ', str_replace('Z', '', $data['updated_at'])),
						'completed_at' => str_replace('T', ' ', str_replace('Z', '', $data['completed_at'])),
						'amount' => $data['total'],
						'total_tax' => $data['total_tax'],
						'discount' => $data['total_discount'],
						'customer_id' => $data['customer_id'],
						'shipping_cost' => 0,
						'status' => $data['status'],
						'client_details' => $data['customer_ip'].'|'.$data['customer_user_agent'],
					]);
				}
				if(isset($data['coupon_lines']['id'])){
					OrderCoupons::firstOrCreate([
						'order_id' => $data['id'],
						'coupon_id' => $data['coupon_lines']['id'],
					]);
				}


				$notes = Woocommerce::get('orders/'.$data['id'].'/notes');

				foreach($notes['order_notes'] as $note){

					if (strpos($note['note'], 'voorraad verlaagd') === false) {

						OrderNotes::firstOrCreate([
							'order_id' => $data['id'],
							'created_at' => str_replace('T', ' ', str_replace('Z', '', $note['created_at'])),
							'note' => $note['note']
						]);
					}
				}

				$refunds =  Woocommerce::get('orders/'.$data['id'].'/refunds');
				foreach($refunds['order_refunds'] as $refund){

					Refunds::firstOrCreate([
						'id' => $refund['id'],
						'order_id' => $data['id'],
						'created_at' => str_replace('T', ' ', str_replace('Z', '', $refund['created_at'])),
						'reason' => $refund['reason'],
						'amount' => $refund['amount'],
					]);

					foreach($refund['line_items'] as $item){
						RefundItems::firstOrCreate([
							'order_id'			=> $data['id'],
							'refund_id'         => $refund['id'],
							'product_id'		=> $item['product_id'],
							'unit_price'		=> $item['price'],
							'unit_tax'		  	=> $item['subtotal_tax'],
							'qty'				=> $item['quantity'],
							'total'				=> $item['total'],
							'total_tax'			=> $item['total_tax'],
						]);
					}
				}

			}
		}
		
	}

	public function orders(){
		set_time_limit(0);

		$total_orders = Woocommerce::get('orders/count');
		$total = $total_orders['count'];
		$pages = (int) ($total  / 10) + 1;
		for($page = 1; $page <= $pages ; $page++){
			$data = [
				'page' => $page
			];
			$result = Woocommerce::get('orders',$data);

			foreach($result['orders'] as $data){
				echo $data['id']."<br/>";

				unset($data['billing_address']['company']);
				unset($data['shipping_address']['company']);

				$data['billing_address']['order_id'] = $data['id'];
				$data['shipping_address']['order_id'] = $data['id'];

				foreach($data['line_items'] as $items) {
					if($items['product_id'] == '')continue;
					OrderItems::firstOrCreate([
						'order_id'        => $data['id'],
						'product_id'		=> $items['product_id'],
						'unit_price'		=> $items['price'],
						'unit_tax'		=> $items['subtotal_tax'],
						'qty'				=> $items['quantity'],
						'total'			=> $items['total'],
						'total_tax'		=> $items['total_tax'],
					]);

				}
				OrderBilling::firstOrCreate($data['billing_address']);
				OrderShipping::firstOrCreate($data['shipping_address']);
				OrderPayment::firstOrCreate([
					'payment_method_id'        => $data['payment_details']['method_id'],
					'title'=> $data['payment_details']['method_title'],
					'paid' =>$data['payment_details']['paid'] ,
					'txn_id'  =>'11',
					'order_id' => $data['id'],
				]);
				$order_flag = Orders::find($data['id']);
				if(count($order_flag) > 0){
					$order_flag->created_at = str_replace('T', ' ', str_replace('Z', '', $data['created_at']));
					$order_flag->updated_at = str_replace('T', ' ', str_replace('Z', '', $data['updated_at']));

					$order_flag->completed_at = str_replace('T', ' ', str_replace('Z', '', $data['completed_at']));
					$order_flag->amount = $data['total'];
					$order_flag->total_tax= $data['total_tax'];
					$order_flag->discount = $data['total_discount'];
					$order_flag->customer_id = $data['customer_id'];
					$order_flag->shipping_cost = 0;
					$order_flag->status = $data['status'];
					$order_flag->client_details = $data['customer_ip'].'|'.$data['customer_user_agent'];

					$order_flag->save();
				}
				else {
					Orders::firstOrCreate([
						'id' => $data['id'],
						'created_at' => str_replace('T', ' ', str_replace('Z', '', $data['created_at'])),
						'updated_at' => str_replace('T', ' ', str_replace('Z', '', $data['updated_at'])),
						'completed_at' => str_replace('T', ' ', str_replace('Z', '', $data['completed_at'])),
						'amount' => $data['total'],
						'total_tax' => $data['total_tax'],
						'discount' => $data['total_discount'],
						'customer_id' => $data['customer_id'],
						'shipping_cost' => 0,
						'status' => $data['status'],
						'client_details' => $data['customer_ip'].'|'.$data['customer_user_agent'],
					]);
				}

				$notes = Woocommerce::get('orders/'.$data['id'].'/notes');

				foreach($notes['order_notes'] as $note){

					if (strpos($note['note'], 'voorraad verlaagd') === false) {

						OrderNotes::firstOrCreate([
							'order_id' => $data['id'],
							'created_at' => str_replace('T', ' ', str_replace('Z', '', $note['created_at'])),
							'note' => $note['note']
						]);
					}
				}

				$refunds =  Woocommerce::get('orders/'.$data['id'].'/refunds');
				foreach($refunds['order_refunds'] as $refund){

					Refunds::firstOrCreate([
						'id' => $refund['id'],
						'order_id' => $data['id'],
						'created_at' => str_replace('T', ' ', str_replace('Z', '', $refund['created_at'])),
						'reason' => $refund['reason'],
						'amount' => $refund['amount'],
					]);

					foreach($refund['line_items'] as $item){
						RefundItems::firstOrCreate([
							'order_id'			=> $data['id'],
							'refund_id'         => $refund['id'],
							'product_id'		=> $item['product_id'],
							'unit_price'		=> $item['price'],
							'unit_tax'		  	=> $item['subtotal_tax'],
							'qty'				=> $item['quantity'],
							'total'				=> $item['total'],
							'total_tax'			=> $item['total_tax'],
						]);
					}
				}

			}
		}

	}
	
	public function users(){
		set_time_limit(0);
		$total_customers = Woocommerce::get('customers/count');
		$total = $total_customers['count'];
		$pages = (int) ($total  / 10) + 1;
		for($page = 1; $page <= $pages ; $page++){
			$data = [
				'page' => $page
			];
			$result = Woocommerce::get('customers',$data);
			//dd($result);
			foreach($result['customers'] as $data){

				
				echo $data['id']."-".$data['email']."<br/>";
				$customer = new Customer;

				if(!$customer->find($data['id'])) {

					$user_id = $customer->add([
						'id' => $data['id'],
						'first_name' => $data['first_name'],
						'last_name' => $data['last_name'],
						'username' => $data['username'],
						'email' => $data['email'],
						'password' => bcrypt(''),
						'status' => 'active',
						'postcode' => $data['billing_address']['postcode']
					]);
					unset($data['billing_address']['company']);
					unset($data['shipping_address']['company']);
					$data['billing_address']['customer_id'] = $user_id;
					$data['shipping_address']['customer_id'] = $user_id;
					$customer_billing = new CustomerBilling;
					$customer_billing->add($data['billing_address']);
					$customer_shipping = new CustomerShipping;
					$customer_shipping->add($data['billing_address']);
				}
			}
		}
		
	}
	
	public function productCategories()
{
	dump(Woocommerce::get(''));
	$p = Woocommerce::get('products');
	dd($p);
	exit;

	$categories = Woocommerce::get('products/categories');
	if($categories)
	{
		foreach ($categories['product_categories'] as $category)
		{
			if(Categories::where('id', $category['id'])->count() < 1) {

				Categories::insert([
					[
						'id' => $category['id'],
						'name' => $category['name'],
						'slug' => $category['slug'],
						'status' => 'publish',
						'created_at' => date('Y-m-d H:i:s'),
						'updated_at' => date('Y-m-d H:i:s'),
						'parent_category_id' => $category['parent'],
						'description' => $category['description']

					]
				]);
				echo $category['id']. ' inserted <br>';
			}else{
				echo $category['id']. ' exists <br>';
			}
		}
	}


}


	public function coupons()
	{
		set_time_limit(0);
	    $total_coupons = Woocommerce::get('coupons/count');
	 	echo "total coupon". $total = $total_coupons['count'];
		$pages = (int) ($total  / 10) + 1;
		    for($page = 1; $page <= $pages ; $page++) {
				$data = [
					'page' => $page
				];
				$result = Woocommerce::get('coupons', $data);
				//dd($result);
				if(isset($result['coupons'])){
				foreach ($result['coupons'] as $data) {
                    if(!isset($data['woocommerce_api_invalid_coupon_id'])){
						$coupon_flag = Coupons::find($data['id']);
						if(count($coupon_flag) == 0) {
							if ($data['individual_use'] == 'false') {
								$is_individual = 0;
							} else {
								$is_individual = 1;
							}
							if ($data['enable_free_shipping'] == 'false') {
								$is_shipping = 0;
							} else {
								$is_shipping = 1;
							}

							if ($data['exclude_sale_items'] == 'false') {
								$is_exclude = 0;
							} else {
								$is_exclude = 1;
							}
							if ($data['usage_limit_per_user'] == NULL) {
								$data['usage_limit_per_user'] = 0;
							}
							if ($data['limit_usage_to_x_items'] == NULL) {
								$data['limit_usage_to_x_items'] = 0;
							}
							if ($data['usage_limit'] == NULL) {
								$data['usage_limit'] = 0;
							}
							if ($data['type'] == 'percent_product') {
								$data['type'] = 'product%';

							} else if ($data['type'] == 'fixed_cart') {
								$data['type'] = 'cart';
							} else if ($data['type'] == 'percent') {
								$data['type'] = 'cart%';

							}
							$products_array = $data['product_ids'];
							$products = serialize($products_array);

							$exc_products_array = $data['exclude_product_ids'];
							$exc_products = serialize($exc_products_array);

							$categories_array = $data['product_category_ids'];
							$categories = serialize($categories_array);

							$exc_categories_array = $data['exclude_product_category_ids'];
							$exc_categories = serialize($exc_categories_array);

							$data_insert = array(
								'id' => $data['id'],
								'code' => $data['code'],
								'description' => $data['description'],
								'type' => $data['type'],
								'status' => 'publish',
								'published_at' => str_replace('T', ' ', str_replace('Z', '', $data['created_at'])),
								'amount' => $data['amount'],
								'is_free_shipping' => $is_shipping,
								'expiry_date' => str_replace('T', ' ', str_replace('Z', '', $data['expiry_date'])),
								'max_spend' => $data['maximum_amount'],
								'min_spend' => $data['minimum_amount'],
								'is_individual' => $is_individual,
								'show_on_cart' => '1',
								'exclude_sale_items' => $is_exclude,
								'products' => $products,
								'exclude_products' => $exc_products,
								'categories' => $categories,
								'exclude_categories' => $exc_categories,
								'usage_limit_coupon' => $data['limit_usage_to_x_items'],
								'created_at' => date('Y-m-d H:i:s'),
								'usage_limit_user' => $data['usage_limit_per_user'],
								'usage_count' => $data['usage_limit'],
								'created_at' => str_replace('T', ' ', str_replace('Z', '', $data['created_at'])),
								'updated_at' => str_replace('T', ' ', str_replace('Z', '', $data['updated_at']))
							);
							Coupons::insert($data_insert);
							echo $data['id'] . "inserted !";
						}}}
			}}


		//$coupons = Woocommerce::get('coupons');
		//dd($coupons);
		//exit;




	}



}
