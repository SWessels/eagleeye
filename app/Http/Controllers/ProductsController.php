<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Functions\Functions;
use App\LinkedProducts;
use App\Tabs;
use DB;
use App\User;
use App\Products;
use App\Categories;
use App\Tags;
use App\Attributes;
use App\Terms;
use App\SeoData;
use App\ProductAttributes;
use App\Inventories;
use Session;
use Config;
use Mail;

use Carbon\Carbon; 


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;


class ProductsController extends BaseController
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
	private $product;	
	private $category; 
	private $tags;
	private $attributes;
	private $productattribute;
	private $user;
	private $inventories;
	private $customers;
	private $seo;
	
	protected $connection; 

 public function __construct(Request $request)
    {
		parent::__construct();
		// check if user has products capability
        if(User::userCan('products') === false)
        {
            abort(403, 'Unauthorized action.');
        }

		$this->product 		= new Products();
		$this->category 	= new Categories();
		$this->tags 		= new Tags();
		$this->productattribute 	= new ProductAttributes();
		$this->attributes 	= new Attributes();
		$this->terms 		= new Terms();
		$this->user 		= new User();
		$this->inventories	= new Inventories();
		$this->customers	= new Customer();
		$this->seo			= new SeoData();
		$this->connection   = Session::get('connection');


    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index(Request $request){

	  $request_all = $request->all();

	  // search arguments
	  $args = array();
	  $dates  = $this->product->productDates();
	  $months = array();
	  foreach($dates as $date)
	  {
		  $months[] = date('M Y', strtotime($date->dates));
	  }

	  $months = array_unique($months);


	  if(isset($request_all['keywords']) && $request_all['keywords']!='')
	  {
		  if(is_numeric($request_all['keywords']))
		  {
			  $args['sku']['table'] 	= 'products';
			  $args['sku']['column'] 	= 'sku';
			  $args['sku']['operator']	= '=';
			  $args['sku']['value']	= str_replace('+','',$request_all['keywords']);
		  }else{
			  $args['keywords']['table'] 	= 'products';
			  $args['keywords']['column'] 	= 'name';
			  $args['keywords']['operator']	= 'like';
			  $args['keywords']['value']	= "%".str_replace('+','',$request_all['keywords'])."%";
		  }

	  }
	  


	  if(isset($request_all['all-dates']) && $request_all['all-dates']!='')
	  {
		  $search_date 			= strtotime(str_replace('+', '', $request_all['all-dates']));
		  $first_day_month 		= date('Y-m-01', $search_date);
		  $last_day_of_month 	= date('Y-m-t', $search_date);

		  $args['date_from']['table'] 		= 'products';
		  $args['date_from']['column'] 		= 'created_at';
		  $args['date_from']['operator']	= '>=';
		  $args['date_from']['value']		= $first_day_month;

		  $args['date_to']['table'] 		= 'products';
		  $args['date_to']['column'] 		= 'created_at';
		  $args['date_to']['operator']		= '<=';
		  $args['date_to']['value']			= $last_day_of_month;
	  }

	  if(isset($request_all['product-type']) && $request_all['product-type']!='')
	  {

		  $search_type = str_replace('+', '', $request_all['product-type']);
		  $args['product_type']['table'] 		= 'products';
		  $args['product_type']['column'] 		= 'product_type';
		  $args['product_type']['operator']		= '=';
		  $args['product_type']['value']		= $search_type;
	  }
 

	  if(isset($request_all['status']) && $request_all['status']!='')
	  {

		  $status = $request_all['status'];
		  $args['status']['table'] 		= 'products';
		  $args['status']['column'] 		= 'status';
		  $args['status']['operator']		= '=';
		  $args['status']['value']		= $status;
	  }else{
		  $args['status']['table'] 		= 'products';
		  $args['status']['column'] 		= 'status';
		  $args['status']['operator']		= '<>';
		  $args['status']['value']		= 'deleted';
	  }

	  $has  = array();
	  $category = '';
	  if(isset($request_all['category']) && $request_all['category']!='')
	  {
		  $has['categories'] = $request_all['category'];
		  $category			 = $request_all['category'];
	  }
	  //dd($args);

	  if(isset($request_all['sort-by']) && $request_all['sort-by']!='')
	  {
		  $sort_by = $request_all['sort-by'];
	  }else{
		  $sort_by = 'id';
	  }

	  if(isset($request_all['sort-order']) && $request_all['sort-order']!='')
	  {
		  $sort_order = $request_all['sort-order'];
	  }else{
		  $sort_order = 'desc';
	  }




		if(!empty($args) || $category!='' || $sort_by!='' || $sort_order!=''){
			$products  = $this->product->productSearch($args, $category, $sort_by, $sort_order);
		}else{
	  		$products  = $this->product->getProducts();
		}



	    $filter_fields = $this->buildFilterFields();
		
		$categories  = $this->category->getCategories();

	  	$products_counts = $this->product->getProductsCounts();


		foreach ($products as $product)
		{
			$variation_data = '';
			$total_stock = 0 ;
			$variations_regular_price 	= array();
			$variations_sale_price 		= array();
			if($product->product_type == 'variable') {

				$stock_status = 'out';
				foreach ($product->children as $child) {
					$child_inv = $child->inventories;
					if ($child_inv) {
						if ($child_inv->stock_status == 'in') {
							$stock_status = 'in';
						}
					}
				}

				$wait_list_str = '';
				foreach ($product->children as $child) {

					$variations_regular_price[]  		= $child->regular_price;
					$variations_sale_price[]		  	= $child->sale_price;

					$inventories 	= $this->inventories->getInventoryByProductSKU($child->sku);
					$attributes 	= $this->attributes->getAttributesByProductId($child->id);

					//$variation_meta	= $child->meta;

                    $variation_meta_db	= $this->getWaitList($child->id);
                    if($variation_meta_db)
                    {
                        $variation_meta = unserialize($variation_meta_db->meta_value) ;

                    }else{
                        $variation_meta = array();
                    }



					if($attributes && $inventories)
					{


						$term_data = $this->terms->getTermBySlug(@implode(',', unserialize($attributes->attributes)));
						if($term_data)
						{
							$variation_data  .=  '<strong>' . strtoupper($term_data->name) . ': </strong>' . $inventories->stock_qty .'<br>';
						}else{
							$variation_data  .=  '<strong>' . strtoupper(@implode(',', unserialize($attributes->attributes))) . ': </strong>' . $inventories->stock_qty .'<br>';
						}

						$total_stock += $inventories->stock_qty;

					}

					if(count(($variation_meta))>0 && $attributes)
					{
						$c_attributes = unserialize($attributes->attributes);
						$term_name = $this->terms->getTermBySlug(reset($c_attributes));
						if($term_name)
						{
							//$wait_list_str .= strtoupper($term_name->name) . ': '. count(Functions::waitList($variation_meta)).'<br>' ;
                            $wait_list_str .= strtoupper($term_name->name) . ': '. count($variation_meta).'<br>' ;
						}else{
							//$wait_list_str .= strtoupper(reset($c_attributes)) . ': '. count(Functions::waitList($variation_meta)).'<br>' ;
                            $wait_list_str .= strtoupper(reset($c_attributes)) . ': '. count($variation_meta).'<br>' ;
						}



					}

				}

				if($wait_list_str == '')
				{
					$wait_list_str = 0 ;
				}

				$product->setAttribute('wait_list_str', $wait_list_str);





				if($stock_status == 'out')
				{
					$product->setAttribute('stock_status_label', '<span class="out_stock">Out Of Stock</span>');
				}else{
					$product->setAttribute('stock_status_label', '<span class="in_stock">In Stock</span>');
				}

				$product->setAttribute('variations_data', $variation_data);
				$product->setAttribute('total_stock', $total_stock);


				if(!empty($variations_regular_price))
				{
					$regular_min_price = min($variations_regular_price);
					$regular_max_price = max($variations_regular_price);
				}else{
					$regular_min_price = 0;
					$regular_max_price = 0;
				}


				$product->setAttribute('regular_min_price', $regular_min_price);
				$product->setAttribute('regular_max_price', $regular_max_price);

				if(!empty($variations_sale_price))
				{
					$sale_min_price = min($variations_sale_price);
					$sale_max_price = max($variations_sale_price);
				}else{
					$sale_min_price = 0;
					$sale_max_price = 0;
				}

				$product->setAttribute('sale_min_price', $sale_min_price);
				$product->setAttribute('sale_max_price', $sale_max_price);



			}elseif($product->product_type == 'simple')
			{

				$product_stock_status  = $product->stock_status;
				$inventories = $this->inventories->getInventoryByProductSKU($product->sku);
				if ($product_stock_status == 'in') {
					$stock_status = '<span class="in_stock">In Stock:</span>';
				} else {
					$stock_status = '<span class="out_stock">Out Of Stock</span>';
				}

				if($inventories) {
					$total_stock = $inventories->stock_qty;
				}else{
					$total_stock = '';
				}

				$product->setAttribute('total_stock', $total_stock);
				$product->setAttribute('stock_status_label', $stock_status);

                $variation_meta_db	= $this->getWaitList($product->id);
                if($variation_meta_db)
                {
                    $variation_meta = unserialize($variation_meta_db->meta_value) ;

                }else{
                    $variation_meta = array();
                }

                $product->setAttribute('wait_list_str', count($variation_meta));

			}elseif ($product->product_type == 'composite')
			{

				$total_stock = 0;
				foreach ($product->components as $component) {
					$sku = $this->product->getSKUbyId($component->default_id);
					$inventories = $this->inventories->getInventoryByProductSKU($sku);
					if($inventories) {
						$total_stock += $inventories->stock_qty;
					}
				}


				$product_stock_status = $product->stock_status;
				if($product_stock_status === 'out')
				{
					$product->setAttribute('stock_status_label', '<span class="out_stock">Out Of Stock</span>');
				}else{
					$product->setAttribute('stock_status_label', '<span class="in_stock">In Stock</span>');
				}

				$product->setAttribute('total_stock', $total_stock);


                $variation_meta_db	= $this->getWaitList($product->id);
                if($variation_meta_db)
                {
                    $variation_meta = unserialize($variation_meta_db->meta_value) ;

                }else{
                    $variation_meta = array();
                }

                $product->setAttribute('wait_list_str', count($variation_meta));

			}

            $color_swatches_new = array();
            $color_swatches  = $this->product->getProductMeta('ce_colors_swatches_prod', $product->id);

            if($color_swatches)
            {
                $color_swatches = unserialize($color_swatches->meta_value);
                foreach ($color_swatches as $color) {

                    $data = $this->product->fGetProductByID($color);
                    if($data)
                    {
                        $pdata = array('id' => $data->id, 'name' => $data->name);
                        $color_swatches_new[] = $pdata;
                    }
                }
            }

            $product->setAttribute('color_swatches', $color_swatches_new);


		}


	    //dd($products);
		$data = array(
						'products'		=> $products,
						'categories' 	=> $categories,
						'created_date'	=> $months
					 );
	   
	   return view('products.products-list', ['data' => $data, 'counts'	=> $products_counts, 'fields' => $filter_fields]);
  }

	/**
	 * create new product
	 *
	 * */

    public function create(){
        $c  = $this->category->getCategories();
		$t  = $this->tags->fGetAllTags();
		$all_products 	= $this->product->getAllProducts();

		$colors = [
			'Antraciet', 'Bordeauxrood', 'Beige', 'Blauw', 'Camel', 'Creme',
			'Geel', 'Goud', 'Groen', 'Grijs', 'Kobaltblauw',
			'Mintgroen', 'Oranje', 'Rood', 'Rose', 'Taupe', 'Wit', 'Zilver', 'Zwart'
		];

		$attributes  	= $this->productattribute->fGetAttributes(); //All Attributes from attributes table
		$tabs 			= tabs::getGlobalTabs();


        return view('products.newproduct', [
											'categories' 	=> $c,
											'tags'			=> $t,
											'products' 		=> $all_products,
											'all_colors'	=> $colors,
											'attributes'	=> $attributes,
											'tabs'			=> $tabs
										]);
    }

	public function store(Request $request)
	{

		if($request->input('product_id') == '')
		{
			$this->validate($request, ['product_name' => 'required']);
			$input = $request->all();
			$add_inserted_id = $this->product->createProduct($input);
			// update stock status
			$this->updateStockStatus([$add_inserted_id]);
			Session::flash('flash_message', 'Product Successfully Created!');
			return redirect()->action('ProductsController@edit',['id' => $add_inserted_id]);
		}else{
			$input = $request->all();
			$this->product->UpdateProductById($input, $request->input('product_id'));
			// update linked products.
			$up_sells 		= '';
			$cross_sells	= '';
			if(!empty($request->input('up_sells')))
			{
				$up_sells 	= implode('|', $request->input('up_sells') );
			}

			if(!empty($request->input('cross_sells'))) {
				$cross_sells = implode('|', $request->input('cross_sells'));
			}

			$link_data 		= array('up_sells' => $up_sells, 'cross_sells' => $cross_sells);

			if($this->product->ifLinkedProductsExist($request->input('product_id')))
			{
				$this->product->updateLinkedProducts($link_data, $request->input('product_id'));
			}else{
				$link_data['product_id'] = $request->input('product_id');
				$this->product->insertLinkedProducts($link_data);
			}

			// update stock status
			$this->updateStockStatus([$request->input('product_id')]);

			// end
			Session::flash('flash_message', 'Product Successfully Created!');
			return redirect()->action('ProductsController@edit',['id' => $request->input('product_id')]);
		}

	}

    public function edit($id){

		if(Functions::checkIdInDB($id, 'products') == 0)
		{
			abort(404, 'Not Found.');
		}

		$up_sells 		= array();
		$cross_sells 	= array();
		$product_terms 	= array();
		$attributes		= array();
		$variations		= array();

		$all_products 	= $this->product->getAllProducts();
		$product  		= $this->product->fGetProductByID($id);
		$categories  	= $this->category->getCategories(0, 0, $id);

		$tags  			= $this->tags->fGetAllTags();
		$product_wait_list 	= array();

		$attributes = $this->productattribute->fGetAttributes(); //All Attributes from productattributes table
		
		if($product->product_type == 'variable') {

			$variations = $this->product->getProductVariations($id);
			foreach ($variations as $variation) {
				$meta = $variation->meta;
				$variation['wait_list'] = array();
				$wait_list = array();
				foreach ($meta as $item) {
					if ($item->meta_name == 'woocommerce_waitlist') {
						$users = unserialize($item->meta_value);
						foreach ($users as $user) {
							$wait_list[] = Customer::getCustomerInfo(['id', 'email'], $user);
						}

						$variation['wait_list'] = $wait_list;
					}
				}
			}

			//
		}else{

			if(!empty($product->Meta))
			{
				$wait_list = array();
				foreach($product->Meta as $meta)
				{
					if ($meta->meta_name == 'woocommerce_waitlist') {
						$users = unserialize($meta->meta_value);
						foreach ($users as $user) {
							$wait_list[] = $this->customers->getCustomerInfo(['id', 'email'], $user);
						}

						$product_wait_list = $wait_list;
					}
				}
			}
		}

		$product['wait_list'] =  $product_wait_list ;



		if(!is_null($product->linkedproducts))
		{
			$linkedProducts	= $product->linkedproducts;
			$up_sells 		= explode('|', $linkedProducts->up_sells);
			$cross_sells	= explode('|', $linkedProducts->cross_sells);
		}

		$colors = [
					'Antraciet', 'Bordeauxrood', 'Beige', 'Blauw', 'Camel', 'Creme',
					'Geel', 'Goud', 'Groen', 'Grijs', 'Kobaltblauw',
					'Mintgroen', 'Oranje', 'Rood', 'Rose', 'Taupe', 'Wit', 'Zilver', 'Zwart'
					];


		$product_colors = $this->product->getProductMeta('ce_colors_entry', $id);

		 
		if($product_colors)
		{
			$db_colors = unserialize($product_colors->meta_value) ;

			if(!empty($db_colors))
			{
				$product_colors = explode(',',unserialize($product_colors->meta_value));
			}else{
				$product_colors = array();
			}

		}else{
			$product_colors = array();
		}


		$color_swatches_new = array();
		$color_swatches  = $this->product->getProductMeta('ce_colors_swatches_prod', $id);

		if($color_swatches)
		{
			$color_swatches = unserialize($color_swatches->meta_value);
			foreach ($color_swatches as $color) {

					$data = $this->product->fGetProductByID($color);
					if($data)
					{
						//$data = $this->product->getProductByName($color);
						$pdata = array('id' => $data->id, 'name' => $data->name);
						$color_swatches_new[] = $pdata;
					}
				}
		}


		$components 	= $product->Components;
		$components_new = array();
		if(!empty($components))
		{
			foreach ($components as $component)
			{

				$product_component = $this->product->fGetProductByID($component->default_id);
				if($product_component)
				{
					$component['default_name'] =   $product_component->name;
				}else{
					$component['default_name'] =   '';
				}

				$components_new[] = $component;

			}
		}else{
			$components_new = array();
		}

		$tabs 			= array();
		$global_tabs	= array();
		$product_tabs 	= array();

		$global_tabs 	= Tabs::getGlobalTabs()->toArray();
		$product_tabs 	= Tabs::getTabsByProductId($id)->toArray();

		$tabs 			= array_merge($global_tabs, $product_tabs);
		$details_tab	= Tabs::getDetailTab($id);


		//dd($product);

        return view('products.editproduct',
											[
												'product' 		=> $product,
												'categories' 	=> $categories,
												'tags'			=> $tags,
												'attributes'	=> $attributes,
												'products' 		=> $all_products,
												'up_sells'		=> $up_sells,
												'cross_sells'	=> $cross_sells,
												'variations'	=> $variations,
												'all_colors'	=> $colors,
												'product_colors' => $product_colors,
												'color_swatches' => $color_swatches_new,
												'components' 	 => $components_new,
												'tabs'	 		=> $tabs,
												'details_tab'	=> $details_tab
											]
									);
    }


	public function update(Request $request, $id)
	{


		$input = $request->all();

		$this->product->UpdateProductById($input ,$id);
 
		// update linked products.
		$up_sells 		= '';
		$cross_sells	= '';
		if(!empty($request->input('up_sells')))
		{
			$up_sells 		=  implode('|', $request->input('up_sells') );
		}

		if(!empty($request->input('cross_sells'))) {
			$cross_sells = implode('|', $request->input('cross_sells'));
		}

		$link_data 		= array('up_sells' => $up_sells, 'cross_sells' => $cross_sells);

		if($this->product->ifLinkedProductsExist($id))
		{
			$this->product->updateLinkedProducts($link_data, $id);
		}else{
			$link_data['product_id'] = $id;
			$this->product->insertLinkedProducts($link_data);
		}

		// end

		// save product colors meta
			if(!empty($request->input('product_color')))
			{
				$colors = serialize(implode(',',$request->input('product_color')));
			}else{
				$colors = serialize(array());
			}

 			if($this->product->hasMeta('ce_colors_entry', $id))
			{
				$this->product->updateProductMeta('ce_colors_entry',$colors, $id);
			}else{
				$this->product->saveProductMeta('ce_colors_entry',$colors, $id);
			}
		// end

 
		Session::flash('flash_message', 'Product Successfully Updated!');
		return redirect()->action('ProductsController@edit', ['id' =>  $id]);
	}


	public function productQuickUpdate(Request $request)
	{
		$this->product->productQuickUpdate($request->all()); 
	}
	

	public function destroy($id)
	{
		if($this->product->moveToTrash($id))
		{
			Session::flash('flash_message', 'Product Successfully Move to Trash!');
			return redirect('/products');
		}else{
			Session::flash('flash_message', 'Error: Product Not Moved to Trash!');
			return redirect('/products');
		}
	}




	//////////////////
	//
	// controller helper functions
	//
	//////////////////


	// build fields html from query string for search filter
	protected function buildFilterFields()
	{
		$str = '';
		if(isset($_GET['sort-by']))
		{
			$str .= '<input type="hidden" name="sort-by" value="'.$_GET['sort-by'].'" >';
		}

		if(isset($_GET['sort-order']))
		{
			$str .= '<input type="hidden" name="sort-order" value="'.$_GET['sort-order'].'" >';
		}

		if(isset($_GET['status']))
		{
			$str .= '<input type="hidden" name="status" value="'.$_GET['status'].'" >';
		}



		return $str;
	}




	public function getAllProductsForJs()
	{
		return $this->product->getAllProductsForJs();
	}

	public function getProductByNameForJs(Request $request)
	{
		 return $this->product->productByName($request['address']);
	}
	
	public function getProductIdByName($name)
	{
		return $this->product->getProductIdByName($name);
	}


	public function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
				return true;
			}
		}

		return false;
	}

	public function power_set($array) {


		$results = [[]];

		foreach($array as $tag => $features) {
			foreach ($results as $key => $combination) {
				foreach ($features as $feature) {
					$results[] = $combination +  [$tag => $feature]  ;
				}
			}
		}

		return $results;
	}

	public function getProductsFromJson()
	{
		if(file_exists($this->connection.'_products.json'))
		{
			return json_decode(file_get_contents($this->connection.'_products.json'));
		}else{
			$this->buildProductsJson();
			return json_decode(file_get_contents($this->connection.'_products.json'));
		}
	}


	public function updateStockStatus($ids = false)
	{

		if($ids)
		{
			$product_with_inventories = $this->product->productsWithInventories($ids);
		}else{
			$product_with_inventories = $this->product->productsWithInventories();	
		}
		

		foreach($product_with_inventories as $product_with_inventory)
		{
				if($product_with_inventory->product_type == 'simple') {

					$product_inv = $this->inventories->getInventoryByProductSKU($product_with_inventory->sku);
					$stock_status = 'out';
					if ($product_inv) {

						if($product_inv->stock_qty < 1 && $product_inv->allow_back_orders === 'no' )
						{
							$stock_status = 'out';
						}elseif($product_inv->stock_qty < 1 && $product_inv->allow_back_orders !== 'no')
						{
							$stock_status = 'backorder';
						}else{
							$stock_status = 'in';
						}


					}

					$this->product->updateProductStockStatus($product_with_inventory->id, $stock_status);
					if ($product_with_inventory->id == 178893) {
						dump($product_inv);
						dump(DB::connection($this->connection)->table('products')->where('id', 178893)->first());
					}

				}

				if($product_with_inventory->product_type == 'variable') {

					$stock_status = 'out';
					foreach ($product_with_inventory->children as $child) {
						$child_inv = $child->inventories;
						if ($child_inv) {
							if ($child_inv->stock_status == 'in' || $child_inv->stock_qty > 0) {
								$stock_status = 'in';
								break;
							}
						}
					}


					$this->product->updateProductStockStatus($product_with_inventory->id, $stock_status);
				}

				if($product_with_inventory->product_type == 'composite') {

					$components = $product_with_inventory->components()->get();
					$stock_status = 'in';
					foreach ($components as $component) {
						$sku = $this->product->getSKUbyId($component->default_id);
						$component_inv = $this->product->getInventoryByProductSKU($sku);

						if ($component_inv) {
							if ($component_inv->stock_status == 'out' || $component_inv->stock_qty < 1) {
								$stock_status = 'out';
								break;
							}
						}
					}
					$this->product->updateProductStockStatus($product_with_inventory->id, $stock_status);
				} 

		}
	}

	// end


	public function bulkDelete(Request $request)
	{
		if($request->input('move-to-trash')  && !empty($request->input('bulk_products')))
		{
			if($this->product->bulkDelete($request->input('bulk_products')))
			{
				Session::flash('flash_message', 'Products Successfully Moved to trash!');
				return redirect('/products');
			}
		}else {

			Session::flash('flash_warning', 'No product was selected bulk action');
			return redirect('/products');
		}

	}


	// ajax functions


	// create component
	public function createProductComponent(Request $request)
	{

		if($request->input('product_id') == '')
		{
			$product_data = array('title' => '', 'type' => 'composite');
			$product_id = $this->product->autoSaveProduct($product_data);

		}else{

			$product_id = $request->input('product_id');
			$product_data = array('product_id' => $product_id, 'type' => 'composite');
			$this->product->updateProductStatus($product_data);
		}

		$component_data = array(
			'title' 	=>	'',
			'product_id'	=> $product_id,
			'default_id'	=> ''

		);

		if($model_response = $this->product->saveComponent($component_data))
		{
			$response = array('component_id' => $model_response['cid'], 'action' => true , 'all_products' => $model_response['all_products'], 'product_id' => $product_id );
			echo json_encode($response);
			exit;
		}else{
			$response = array('action' => false);
			echo json_encode($response);
			exit;
		}


	}
	
	// end 
	
	
	// update components 

	public function updateProductComponents(Request $request)
	{

		if(empty($request->input('all_components') ))
		{
			return json_encode(array('action' => 'true'));
			exit;
		}
		foreach($request->input('all_components') as $component)
		{
			$this->product->updateComponent($component);
		}

		return json_encode(array('action' => 'true'));
	}
	
	// end

	// delete product component

		function deleteProductComponent(Request $request)
		{
			 return $this->product->deleteComponent($request->input('id'));

		}

	// end


	// save attributes
	public function saveProductAttributes(Request $request){

		$raw_custom_terms 	= array();
		$raw_terms 			= array();
		$custom_term_ids	= array();



		if(!empty($request->input('terms')))
		{
			$raw_terms = $request->input('terms') ;
		}

		if($request->input('id') == '')
		{
			$product_data = array('title' => '', 'type' => 'variable');
			$product_id = $this->product->autoSaveProduct($product_data);

		}else{

			$product_id = $request->input('id');
			$product_data = array('product_id' => $product_id, 'type' => 'variable');
			$this->product->updateProductStatus($product_data);
		}


		//dump($request->input('custom_terms'));
		if(!empty($request->input('custom_terms')))
		{
 
			$custom_terms = $request->input('custom_terms');
			foreach ($custom_terms as $custom_attribute => $custom_term)
			{
				//save custom attribute to "productattributes" table
				 
				$att_data = array('name' => $custom_term['att_name'], 'slug' => $custom_term['att_name'], 'type' => 'custom');
				$db_att = $this->productattribute->checkIfExistBySlug($custom_term['att_name']);
				//dump($db_att);
				if( $db_att['count'] == 0 ) {
					$this->productattribute->saveAtt($att_data);
					$lastAttId = DB::connection($this->connection)->getPdo()->lastInsertId();
				}else{
					$lastAttId = $db_att['att_id'];
				}
					// save terms for attribute last saved
					$exp_terms = explode('|',trim($custom_term['att_terms'],'|'));
					foreach ($exp_terms as $single_term)
					{
						if($this->terms->checkIfExistByAttributeId($lastAttId, $single_term) == 0) {

							$term_data = array('name' => $single_term, 'slug' => $single_term, 'desc' => '', 'att_id' => $lastAttId);
							$this->terms->saveTerm($term_data);
						}
					}

					$raw_custom_terms[$lastAttId] = $exp_terms;
			}
		}

		//dump($raw_terms);
		//dump($raw_custom_terms);

		$raw_terms = $raw_terms + $raw_custom_terms  ;

		//dump($raw_terms);

		if(!empty($raw_terms))
		{
			foreach ($raw_terms as $key=>$term)
			{
				$term = array_map('strtolower', $term);
				$terms[$key]  = $term;
			}

			$pairs = $this->power_set($terms);
		}else{
			$pairs = array();
		}




		foreach ($pairs as $combination)
		{
			if(count($terms)>1)
			{
				if (count($combination) > 1) {
					$combinations[] = $combination;
				}
			}else {
				if (count($combination) > 0) {
					$combinations[] = $combination;
				}
			}

		}

		//dump($terms);
		$product_terms = array();
		if(!empty($term)) {

			foreach ($terms as $pterm) {
				if (is_array($pterm)) {
					foreach ($pterm as $sterm) {
						$product_terms[] = $this->terms->getTermBySlug($sterm);
					}

				} else {
					$product_terms[] = $this->terms->getTermBySlug($pterm);
				}

			}
		}else{
			// delete all variations
			$this->product->deleteVariationByParentId($product_id);
			$variations_html = array('product_id' => $product_id );
			echo json_encode($variations_html);
			exit;
		}

		$attributes = serialize($terms);
		$data = array(
			'attributes' 	=> $attributes,
			'product_id' 	=> $product_id
		);
		$this->product->addProductAttributes($data);

		// check if variations exist in database
		$db_variations_exist 	= $this->product->getProductVariationsCount($product_id);
		$db_variations 			= $this->product->getProductVariations($product_id);
		//dd($db_variations);
		$product_all_terms = array_unique(call_user_func_array('array_merge', $terms));
		$delete_variations = array();
		foreach ($db_variations as $db_variation)
		{
			if($db_variation->attributes) {
				$variation_atts = unserialize($db_variation->attributes->attributes);
				//dump($variation_atts);
				foreach ($variation_atts as $var_term) {
					if (!in_array($var_term, $product_all_terms)) {
						$delete_variations[] = $db_variation->id;
					}
				}
			}
		}

		//dump($delete_variations);
		if(!empty($delete_variations))
		{
			foreach ($delete_variations as $del_var_id)
			{
				$this->product->deleteVariation($del_var_id);
			}

		}


		if($db_variations_exist)
		{
			$variations_html = array('product_id' => $product_id ); // do not save variations if exist
			echo json_encode($variations_html);
			exit;
		}
		// else save all variations

		$variations = count($combinations);

		for ($i = 0; $i < $variations; $i++)
		{
			$variation_id = $this->product->saveVariation(array('title' => $request->input('title'), 'parent_id' => $product_id));
			/*$inventory_data = array(
				'product_sku'	=> $variation_id,
				'manage_stock'	=> 'yes',
				'stock_status'	=> '',
				'stock_qty'		=> 0,
				'allow_back_orders' => 'yes'
			);

			$inv_save = $this->product->saveInventory($inventory_data);*/
			
			$data = array(
				'attributes' 	=> serialize($combinations[$i]),
				'product_id' 	=> $variation_id
			);

			$this->product->addProductAttributes($data);
		}

		$variations_html = array('product_id' => $product_id );
		echo json_encode($variations_html);
		exit;

	}
	//end

	// get variations

	public function getProductVariations(Request $request){
		$product_terms = array();
		if($request->input('id') == '')
		{
			$variations_html = array('variations' => '', '' => '', 'product_id' => '' ); // do not save variations if exist
			echo json_encode($variations_html);
			exit;

		}else{
			$product_id 		= $request->input('id');
			$product_terms 		= $this->product->getProductAttributes($product_id);
			if($product_terms)
			{
				$product_terms 		= unserialize($product_terms->attributes);
			}

		}


		$variations_data = $this->product->getProductVariations($product_id);


		$str = '';
		$i = 0 ;
		if(!empty($variations_data)){ $str .='<input type="hidden" name="sku_update" id="sku_update" value="0"> <input type="hidden" name="price_update" id="price_update" value="0"> '; }
		foreach($variations_data as $variation) {

			if($variation->sale_from !='0000-00-00 00:00:00' && $variation->sale_from!='')
			{
				$sale_from = Carbon::parse($variation->sale_from);
				$sale_from = $sale_from->format('m/d/Y');
			}else{
				$sale_from = '';
			}

			if($variation->sale_to !='0000-00-00 00:00:00' && $variation->sale_to !='')
			{
				$sale_to		= Carbon::parse($variation->sale_to);
				$sale_to		= $sale_to->format('m/d/Y');
			}else{
				$sale_to		= '';
			}
			if($sale_from !='' || $sale_to !='')
			{
				$display = 'style="display:block;"';
				$schedule_btn = '<a href="javascript:;" class="toggle-date-range">Cancel</a>';
			}else{
				$display = '';
				$schedule_btn = '<a href="javascript:;" class="toggle-date-range">Schedule</a>';
			}

			if($variation->expected_date_of_delivery != '0000-00-00' && $variation->expected_date_of_delivery != '1970-01-01')
			{
				$expected_date_delivery 	= Carbon::parse($variation->expected_date_of_delivery);
				$expected_date_delivery		= $expected_date_delivery->format('m/d/Y');
			}else{
				$expected_date_delivery = '';
			}

			$stock_in = '';
			$stock_out = '';
			if($variation->inventories)
			{
 				$stock_status = $variation->inventories->stock_status;
				if($stock_status == 'in')
				{
					$stock_in = 'selected="selected"';
				}else{
					$stock_out = 'selected="selected"';
				}
			}else{
				$stock_status = 'out';
				$stock_out = 'selected="selected"';
			}

			if(isset($variation->inventories))
			{
				$stock_qty = $variation->inventories->stock_qty;
			}else{
				$stock_qty = 0; 
			}

			$atts = $variation->attributes['attributes']['attributes'];
			$atts = unserialize($atts);


			$str .='<input type="hidden" name="variations_id[]" value="'.$variation->id.'">';

			$str .= '<div class="col-md-12 col-sm-12"> <div class="portlet box default"> <div class="portlet-title"> <div class="caption">';
			$str .='<input type="hidden" name="updated_'.$variation->id.'" class="update_ids" id="updated_'.$variation->id.'" value="0">';
			$str .='<div class="row">';
			$str .='<div class="col-md-3 col-sm-3">';
			$str .='<span class="variation_no"> # ' . $variation->id . ' </span>';
			$str .='</div> <div class="col-md-9 col-sm-9">';




			foreach($product_terms as $pattribute => $terms)
			{
				$terms = array_unique($terms);

				$str .= '<select class="input input-sm attributes_in_variation variation_item" id="variations_'.$variation->id.'">';
				foreach ($terms as $term) {
					$db_terms = $this->terms->getTermBySlug($term);
					if(!empty($db_terms))
					{
						if (in_array(strtolower(str_replace('/', '', $term)), $atts)) {
							$str .= '<option selected="selected" value="' . $term . '" >' . $db_terms->name . '</option>';
						} else {
							$str .= '<option value="' . $term . '">' . $db_terms->name . '</option>';
						}
					}
				}
				$str .= '</select>';
			}


			$str .='</div>';
			$str .='</div>';
			$str .='</div>';
			$str .='<div class="tools pull-right">';
			$str .='<a href="javascript:;" class="expand" id="collapsed_'.$variation->id.'"> </a>';
			$str .='<a href="javascript:;" id="variation_'.$variation->id.'" class="remove remove_variation" data-original-title="" title=""> </a>';
			$str .='</div>';
			$str .='</div>';
			$str .='<div class="portlet-body  portlet-collapsed">';
			$str .='<div class="row">';
			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">  <label>SKU</label>  <input type="text" class="form-control input-sm variation_item variation_sku sku_'.$i.'" name="variation_sku_'.$variation->id.'" id="variations_'.$variation->id.'" value="'. $variation->sku .'" placeholder="SKU">';

			$str .='</div></div>';

			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">';
			$str .='<label>&nbsp;</label>';
			$str .='<div class="mt-checkbox-inline">';
			$str .='<label class="mt-checkbox"> <input type="checkbox" class="variation_item" id="variations_'.$variation->id.'" name="variation_status_'.$variation->id.'" checked="checked"> Enabled </label>';
			$str .='<label class="mt-checkbox pull-right"> <input type="checkbox" name="manage_stock_'.$variation->id.'" class="var_inventory_level variation_item"  id="var_inventory_level_'.$variation->id.'" checked="checked" >Enable Stock </label>';
			$str .='</div> </div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group"> <label>Regular Price</label> <input type="text" name="price_'.$variation->id.'" class="variation_price form-control input-sm variation_item  variation_price_'.$i.'" id="variations_'.$variation->id.'" value="'.Functions::moneyForField($variation->regular_price).'" placeholder="Regular Price">';
			$str .='</div> </div>';

			$str .='<div class="col-md-6">';
			if($variation->sale_price !='' && $variation->sale_price!=0.00)
			{
				$variation_sale_price = $variation->sale_price;
			}else{
				$variation_sale_price ='';
			}
			$str .='<div class="form-group"> <label>Sale Price '.$schedule_btn.' </label> <input type="text" class="sale_price form-control input-sm variation_item" name="sale_price_'.$variation->id.'" id="variations_'.$variation->id.'" value="'.Functions::moneyForField($variation_sale_price).'" placeholder="Sale Price">';
			$str .='</div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12 var_stock_manage_'.$variation->id.'">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group"> <label>Stock Quantity</label> <input type="text" name="stock_qty_'.$variation->id.'" class="stock_qty form-control input-sm variation_item" id="variations_'.$variation->id.'" value="'.$stock_qty.'" placeholder="Stock Quantity">';
			$str .='</div> </div>';

			$str .='<div class="col-md-6">';
			$str .='<div class="form-group"> <label>Allow Back Orders?</label> ';
			if(isset($variation->inventories->allow_back_orders))
			{
				$allow_back_orders = $variation->inventories->allow_back_orders;
			}else{
				$allow_back_orders = '';
			}
			$str .='<select class="form-control input-sm variation_item" id="variations_'.$variation->id.'"  name="allow_backorder_'.$variation->id.'">';

			$yes_select 	= '';
			$yesd_select	= '';
			$no_select		= '';
			if($allow_back_orders == 'yes')
			{
				$yes_select = 'selected="selected"';
			}elseif($allow_back_orders == 'yesd')
			{
				$yesd_select = 'selected="selected"';
			}elseif($allow_back_orders)
			{
				$no_select = 'selected="selected"';
			}

			if($variation->inventories)
			{
				$back_order_limit = $variation->inventories->back_order_limit;
			}else{
				$back_order_limit = '';
			}


			$str .='<option value="yes" '.$yes_select.'>Allow</option> <option value="yesd" '.$yesd_select.'>Allow with display </option> <option value="no" '.$no_select.'>Not Allowed</option> </select>';
			$str .='</div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">  <label>Back Order Limit</label>  <input type="text" class="form-control input-sm variation_item back_order_limit back_order_limit_'.$i.'" name="back_order_limit_'.$variation->id.'" id="back_order_limit_'.$variation->id.'" value="'. $back_order_limit .'" placeholder="Back Order Limit">';

			$str .='</div></div>';

			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">';
			$str .='<label>Stock Status</label>';
			$str .='<select class="form-control  input-sm variation_item" name="stock_status_'.$variation->id.'" id="variations_'.$variation->id.'">';
			$str .='<option value="in" '.$stock_in.'>In Stock</option> <option value="out" '.$stock_out.'>Out Of Stock</option> </select>';

			$str .='</div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12">';

			$str .='<div class="sale_schedule col-md-12 col-sm-12" '.$display.'>';
			$str .='<div class="form-group">';
			$str .='<label>Sale Duration</label>';
			$str .='<div class="input-group input date-picker input-daterange variation_item"  id="variations_'.$variation->id.'" data-date="" data-date-format="mm/dd/yyyy">';


			$str .='<input type="text" class="form-control variation_item" name="from" id="variations_'.$variation->id.'" value="'.$sale_from.'" > <span class="input-group-addon"> to </span>';
			$str .='<input type="text" class="form-control variation_item" name="to" id="variations_'.$variation->id.'" value="'.$sale_to.'" ></div> <span class="help-block"></span>';
			$str .='</div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12">';

			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">';
			$str .='<label>Expected Date Of Delivery</label>';
			$str .='<input class="form-control form-control-inline input-sm date-picker variation_item" name="expected_date_delivery_'.$variation->id.'" id="variations_'.$variation->id.'" size="16" type="text" value="'.$expected_date_delivery.'">';
			$str .='</div> </div> </div> </div>  </div> </div> </div>';
			$i++;

		}


		$variations_html = array('variations' => $str, 'count' => count($variations_data), 'product_id' => $product_id );

		echo json_encode($variations_html);


	}

	// end

	public function deleteVariation(Request $request)
	{
		return $this->product->deleteVariation($request->input('id'));
	}

	public function deleteAllVariation(Request $request)
	{
		return $this->product->deleteAllVariations($request->input('id'));
	}

	public function saveVariation(Request $request)
	{

		$db_variations 	= 	$this->product->getProductVariationsCount($request->input('parent_id'));
		$variation_id 	=  	$this->product->saveVariation($request);

		$inventory_data = array(
			'product_id'	=> $variation_id,
			'manage_stock'	=> 'yes',
			'stock_status'	=> 'in',
			'stock_qty'		=> 0,
			'allow_back_orders' => 'yes',
			'back_order_limit'	=> 0
		);
		$save_inventory	=	$this->product->saveInventory($inventory_data);

		$parent_terms 	=  $this->product->getProductAttributes($request->input('parent_id'));
		$parent_terms 	=  unserialize($parent_terms->attributes);
		foreach ($parent_terms as $pterm)
		{
			$parent_terms_new[] = $pterm;
		}


		$parent_atts_count 	=  count($parent_terms);
		$terms_set = array();
		if($parent_atts_count == 1)
		{
			$term 		= $parent_terms_new[0][0];
			$att 		= $this->terms->getAttributeIDByTerm($term);
			 

			$terms_set[$att->attributes_id] = $term;
		}else{
			$term1 		= $parent_terms_new[0][0];
			$att1 		= $this->terms->getAttributeIDByTerm($term1);

			$terms_set[$att1->attributes_id] = $term1;

			$term2 		= $parent_terms_new[1][0];
			$att2 		= $this->terms->getAttributeIDByTerm($term2);

			$terms_set[$att2->attributes_id] = $term2;
		}


		$data = array(
			'attributes' 	=> serialize($terms_set),
			'product_id' 	=> $variation_id
		);

		$save_attributes =  $this->product->addProductAttributes($data);

		$variation 		= $this->product->getVariationById($variation_id);
		
		$str = '';

			$str .= '<div class="col-md-12 col-sm-12">';
			$str .= '<input type="hidden" name="variations_id[]" value="'.$variation_id.'"><input type="hidden" name="updated_'.$variation_id.'" class="update_ids" id="updated_'.$variation_id.'" value="0">';
			$str .= '<div class="portlet box default"> <div class="portlet-title"> <div class="caption">';
			$str .='<div class="row">';
			$str .='<div class="col-md-3 col-sm-3">';
			$str .='<span class="variation_no"> # ' . $variation->id . ' </span>';
			$str .='</div> <div class="col-md-9 col-sm-9">';


		//dd($parent_terms);

			foreach ($parent_terms as $pattribute => $terms) {

				$str .= '<select class="input input-sm attributes_in_variation variation_item" id="variations_'.$variation->id.'" >';
				$terms = array_unique($terms);
				foreach ($terms as $term) {

					$db_terms = $this->terms->getTermBySlug($term);
					if(!empty($db_terms))
					{
						$str .= '<option value="'.$db_terms->slug.'">' . $db_terms->name . '</option>';
					}

				}
				$str .= '</select>';
			}


			$str .='</div>';
			$str .='</div>';
			$str .='</div>';
			$str .='<div class="tools pull-right">';
			$str .='<a href="javascript:;" class="expand" id="collapsed_'.$variation->id.'"> </a>';
			$str .='<a href="javascript:;" id="variation_'.$variation->id.'" class="remove remove_variation" data-original-title="" title=""> </a>';
			$str .='</div>';
			$str .='</div>';
			$str .='<div class="portlet-body  portlet-collapsed">';
			$str .='<div class="row">';
			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">  <label>SKU</label>  <input type="text" class="form-control input-sm variation_item variation_sku sku_'.$db_variations.'" name="variation_sku_'.$variation->id.'" id="variations_'.$variation->id.'" value="'. $variation->sku .'" placeholder="SKU">';

			$str .='</div></div>';

			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">';
			$str .='<label>&nbsp;</label>';
			$str .='<div class="mt-checkbox-inline">';
			$str .='<label class="mt-checkbox"> <input type="checkbox" class="variation_item" id="variations_'.$variation->id.'" name="variation_status_'.$variation->id.'" checked="checked"> Enabled </label>';
			$str .='<label class="mt-checkbox pull-right"> <input type="checkbox" name="manage_stock_'.$variation->id.'" class="var_inventory_level variation_item"  id="var_inventory_level_'.$variation->id.'" checked="checked" >Enable Stock </label>';
			$str .='</div> </div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group"> <label>Regular Price</label> <input type="text" name="price_'.$variation->id.'" class="variation_price form-control input-sm variation_item   variation_price_'.$variation->id.'" id="variations_'.$variation->id.'" value="'.$variation->regular_price.'" placeholder="Regular Price">';
			$str .='</div> </div>';

			$str .='<div class="col-md-6">';
			if($variation->sale_price !='' && $variation->sale_price!=0.00)
			{
				$variation_sale_price = $variation->sale_price;
			}else{
				$variation_sale_price ='';
			}

			$str .='<div class="form-group"> <label>Sale Price <a href="javascript:;"  class="toggle-date-range">Schedule</a> </label> <input type="text" class="sale_price form-control input-sm variation_item" name="sale_price_'.$variation->id.'" id="variations_'.$variation->id.'" value="'.Functions::moneyForField($variation_sale_price).'" placeholder="Sale Price">';
			$str .='</div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12 var_stock_manage_'.$variation->id.'">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group"> <label>Stock Quantity</label> <input type="text" name="stock_qty_'.$variation->id.'" class="stock_qty form-control input-sm variation_item" id="variations_'.$variation->id.'" value="" placeholder="Stock Quantity">';
			$str .='</div> </div>';

			$str .='<div class="col-md-6">';
			$str .='<div class="form-group"> <label>Allow Back Orders?</label> ';
			$str .='<select class="form-control input-sm variation_item" id="variations_'.$variation->id.'"  name="allow_backorder_'.$variation->id.'"> <option value="yes">Allow</option> <option value="yesd">Allow with display </option> <option value="no">Not Allowed</option> </select>';
			$str .='</div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">  <label>Back Order Limit</label>  <input type="text" class="form-control input-sm variation_item back_order_limit back_order_limit_'.$variation->id.'" name="back_order_limit_'.$variation->id.'" id="back_order_limit_'.$variation->id.'" value="'. $variation->back_order_limit .'" placeholder="Back Order Limit">';

			$str .='</div></div>';

			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">';
			$str .='<label>Stock Status</label>';
			$str .='<select class="form-control  input-sm variation_item" name="stock_status_'.$variation->id.'" id="variations_'.$variation->id.'">';
			$str .='<option value="in" >In Stock</option> <option value="out" >Out Of Stock</option> </select>';

			$str .='</div> </div> </div>';


			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="sale_schedule col-md-12 col-sm-12">';
			$str .='<div class="form-group">';
			$str .='<label>Sale Duration</label>';
			$str .='<div class="input-group input date-picker input-daterange variation_item"  id="variations_'.$variation->id.'" data-date="" data-date-format="mm/dd/yyyy">';
			$str .='<input type="text" class="form-control variation_item" name="from" id="variations_'.$variation->id.'" > <span class="input-group-addon"> to </span>';
			$str .='<input type="text" class="form-control variation_item" name="to" id="variations_'.$variation->id.'" ></div> <span class="help-block"></span>';
			$str .='</div> </div> </div>';

			$str .='<div class="col-md-12 col-sm-12">';
			$str .='<div class="col-md-6">';
			$str .='<div class="form-group">';
			$str .='<label>Expected Date Of Delivery</label>';
			$str .='<input class="form-control form-control-inline input-sm date-picker variation_item" name="expected_date_delivery_'.$variation->id.'" id="variations_'.$variation->id.'" size="16" type="text" value="">';
			$str .='</div> </div> </div> </div>  </div> </div> </div>';

		$variations_html = array('variation' => $str);

		echo json_encode($variations_html);
	}


	//update variations 
	public function updateVariation(Request $request)
	{
		if(empty($request->input('all_variations') ))
		{
			return json_encode(array('action' => 'true'));
			exit;
		}
		foreach($request->input('all_variations') as $variation)
		{
			$n_attributes = array();
			foreach ($variation['attributes'] as $key => $attribute)
			{
				$att_id = $this->terms->getAttributeIDByTerm($attribute)->attributes_id ;
				$n_attributes[$att_id] =	$attribute;
			}
			$variation['attributes'] = $n_attributes;
			$this->product->updateVariation($variation);
		}

		return json_encode(array('action' => 'true'));
	}


	public function addCustomAttribute($product_id)
	{
		$attributes = serialize(array());
		$data = array(
			'attributes' 	=> $attributes,
			'product_id' 	=> $product_id
		);
		$this->product->addProductAttributes($data);
	}


	// fetch wait list

	public function getWaitList($product_id)
	{
		return  $this->product->getProductMeta('woocommerce_waitlist', $product_id);
	}

    public function getCompositeWaitList($product_id)
    {
        return  $this->product->getCompositeProductMeta('woocommerce_waitlist', $product_id);
    }


	// end

	// get color swatches

	public function getColorSwatches($product_id)
	{
		return  $this->product->getProductMeta('ce_colors_swatches_prod', $product_id);
	}

	// end
	
	// add to wait list

	public function addToWaitList(Request $request)
	{


		if(!empty($request->input('email') ))
		{
			$new_wait_list = array();
			$product_id = $request->input('product_id') ;

			$email = $request->input('email');

			if($this->customers->checkIfExist('email', $email) !== false) // check if user already exist with this email
			{
				$user_id = $this->customers->getCustomerId('email', $email);

			}else{  // if user does not exist with this email, create user and add to wait list.

				$user_data = array(
					'email'     => $email
				);
				$user_id = $this->customers->add($user_data);
			}


			if($user_id !==false)
			{
				// fetch current wait list
				$db_wait_list = $this->getWaitList($product_id);

				if($db_wait_list)
				{

					$db_wait_list = unserialize($db_wait_list->meta_value);
					$new_wait_list = $db_wait_list;
					if(!in_array($user_id, $db_wait_list))
					{

						$new_wait_list[] = $user_id;
						$new_wait_list = serialize($new_wait_list);
						// save wait list meta
						$update_meta = $this->product->updateProductMeta('woocommerce_waitlist', $new_wait_list, $product_id);

						if($update_meta)
						{
							return json_encode(array('action' => 'true', 'user_id' => $user_id));
							exit;
						}
					}else{
						return json_encode(array('action' => 'false', 'msg' => 'Email already exist in wait list.'));
						exit;
					}

				}else{

					$new_wait_list = serialize(array('0' => $user_id));
					// save wait list meta
					$update_meta = $this->product->saveProductMeta('woocommerce_waitlist', $new_wait_list, $product_id);

					if($update_meta)
					{
						return json_encode(array('action' => 'true', 'user_id' => $user_id));
						exit;
					}
					
				}


			}

		}

		return json_encode(array('action' => 'false'));
		exit;
	}
	
	// end



	// remove form wait list

	public  function removeFromWaitList(Request $request)
	{
		if($request->input('product_id') !='' && $request->input('user_id') !='')
		{
			$product_id 	= $request->input('product_id');
			$user_id 		= $request->input('user_id');

			$db_wait_list = $this->getWaitList($product_id);

			if($db_wait_list) {

				$db_wait_list = unserialize($db_wait_list->meta_value);


				if (in_array($user_id, $db_wait_list))
				{
                    if($request->input('send_mail')) {

                        // get user email
                        $user_db = $this->customers->getCustomerEmail($user_id);
                        $sku_db = $this->product->getSKUbyId($product_id);
                        if (!is_null($sku_db)) {

                            $sku = $sku_db->sku;

                            $product_DB = $this->product->getProductForWaitList($sku);

                            if ($user_db) {


                                $customerEmail = $user_db->email;

                                $data = [];
                                $data['images'] = array();
                                $data['product_name'] = $product_DB->name;
                                $data['site_url'] = 'https://www.' . strtolower(Functions::getDomainName($this->connection));

                                if($product_DB->product_type =='variation')
                                {

                                    $product_variation_DB = $this->product->getProductForWaitListById($product_DB->parent_id);

                                    if($product_variation_DB)
                                    {

                                        if($product_variation_DB->media) {

                                            $i = 0;
                                            foreach ($product_variation_DB->media as $media) {
                                                if ($i++ > 3)
                                                    break;

                                                $data['images'][] = featuredThumb($media->path);
                                            }
                                        }
                                        $data['product_url'] = 'https://www.' . strtolower(Functions::getDomainName($this->connection)) . '/product/' . $product_variation_DB->slug;
                                    }

                                }else{

                                    if($product_DB->media) {

                                        $i = 0 ;
                                        foreach ($product_DB->media as $media) {
                                            if($i++ >3)
                                                break;

                                            $data['images'][] = featuredThumb($media->path);
                                        }
                                    }
                                    $data['product_url'] = 'https://www.' . strtolower(Functions::getDomainName($this->connection)) . '/product/' . $product_DB->slug;

                                }

                                $mailSent = Mail::send('email-templates.waitlist', array('data' => $data), function ($mail) use ($customerEmail) {
                                    // use $customerEmail for too email

                                    //$mail->to('s.wessels@themusthaves.nl')
                                    $mail->to($customerEmail)
                                        ->from('klantenservice@themusthaves.nl', 'themusthaves.nl')
                                        ->subject('Jouw favoriete Musthave is weer op voorraad bij ' . Functions::getDomainName($this->connection));
                                });

                            }
                        }
                    }

					if (($key = array_search($user_id, $db_wait_list)) !== false) {
						unset($db_wait_list[$key]);
					}

					$new_wait_list = serialize($db_wait_list);
					// save wait list meta
					$update_meta = $this->product->updateProductMeta('woocommerce_waitlist', $new_wait_list, $product_id);

					if ($update_meta) {
						return json_encode(array('action' => 'true', 'user_id' => $user_id));
						exit;
					}
				} else {
					return json_encode(array('action' => 'false', 'msg' => 'Email already removed'));
					exit;
				}
			}
		}

		return json_encode(array('action' => 'false'));
		exit;

	}

	// end


	// addColorSwatch

	public function addColorSwatch(Request $request)
	{
			if($request->input('product_id') !='' && $request->input('swatch') !='' )
			{
				$product_id 	= $request->input('product_id');
				$swatch_id 		= $request->input('swatch');

				$db_swatch 		= $this->getColorSwatches($product_id);
				if($db_swatch) {
					$db_swatch = unserialize($db_swatch->meta_value);
					$new_db_swatch = $db_swatch;

					if (!in_array($swatch_id, $db_swatch)) {

						$new_db_swatch[] = $swatch_id;
						$new_db_swatch = serialize($new_db_swatch);
						// save wait list meta
						$update_meta = $this->product->updateProductMeta('ce_colors_swatches_prod', $new_db_swatch, $product_id);
						if($update_meta)
						{
							return json_encode(array('action' => 'true', 'swatch_id' => $swatch_id));
							exit;
						}
					} else {
						return json_encode(array('action' => 'false', 'msg' => 'Already exist.'));
						exit;
					}
				}else{
					$new_db_swatch = serialize(array('0' => $swatch_id));
					// save wait list meta
					$update_meta = $this->product->saveProductMeta('ce_colors_swatches_prod', $new_db_swatch, $product_id);

					if($update_meta)
					{
						return json_encode(array('action' => 'true', 'swatch_id' => $swatch_id));
						exit;
					}
				}

			}

		return json_encode(array('action' => 'false'));
		exit;
	}

	// end

	// removeColorSwatch

	public function removeColorSwatch(Request $request)
	{

		if($request->input('product_id') !='' && $request->input('swatch_id') !='')
		{
			$product_id 	= $request->input('product_id');
			$swatch_id 		= $request->input('swatch_id');

			$db_swatch = $this->getColorSwatches($product_id);

			if($db_swatch) {

				$db_swatch = unserialize($db_swatch->meta_value);


				if (in_array($swatch_id, $db_swatch))
				{
					if (($key = array_search($swatch_id, $db_swatch)) !== false) {
						unset($db_swatch[$key]);
					}

					$new_db_swatch = serialize($db_swatch);
					// save wait list meta
					$update_meta = $this->product->updateProductMeta('ce_colors_swatches_prod', $new_db_swatch, $product_id);

					if ($update_meta) {
						return json_encode(array('action' => 'true', 'swatch_id' => $swatch_id));
						exit;
					}
				} else {
					return json_encode(array('action' => 'false', 'msg' => 'Already removed'));
					exit;
				}
			}
		}

		return json_encode(array('action' => 'false'));
		exit;
	}

	public function buildAllProductsDropdown(Request $request)
	{
		 if($request->input('type'))
		 {
			 if($request->input('type') == 'linked_products') {

				 $up_sells = array();
				 $cross_sells = array();

				 if ($request->input('product_id') != '') {
					 $linked_products = LinkedProducts::where('product_id', $request->input('product_id'))->first();

					 if($linked_products)
					 {
						 if ($linked_products->up_sells != '') {
							 $up_sells = explode('|', $linked_products->up_sells);
						 }
						 if ($linked_products->cross_sells != '') {
							 $cross_sells = explode('|', $linked_products->cross_sells);
						 }
					 }




				 }

				 //$products = Products::all(['id', 'sku', 'name']);
				 $products = $this->getProductsFromJson();
				 $up_sells_drop = '';
				 $cross_sells_drop = '';
				 foreach ($products as $product) {

					 if (in_array($product->id, $up_sells)) {
						 if ($product->sku) {
							 $up_sells_drop .= '<option value="' . $product->id . '" selected="selected">' . $product->sku . ' - ' . $product->name . '</option>';
						 } else {
							 $up_sells_drop .= '<option value="' . $product->id . '" selected="selected">#' . $product->id . ' ' . $product->name . '</option>';
						 }

					 }else{
						 if ($product->sku) {
							 $up_sells_drop .= '<option value="' . $product->id . '" >' . $product->sku . ' - ' . $product->name . '</option>';
						 } else {
							 $up_sells_drop .= '<option value="' . $product->id . '" >#' . $product->id . ' ' . $product->name . '</option>';
						 }
					 }

					 if (in_array($product->id, $cross_sells)) {
						 if ($product->sku) {
							 $cross_sells_drop .= '<option value="' . $product->id . '" selected="selected">' . $product->sku . ' - ' . $product->name . '</option>';
						 } else {
							 $cross_sells_drop .= '<option value="' . $product->id . '"  selected="selected">#' . $product->id . ' ' . $product->name . '</option>';
						 }

					 }else{
						 if ($product->sku) {
							 $cross_sells_drop .= '<option value="' . $product->id . '">' . $product->sku . ' - ' . $product->name . '</option>';
						 } else {
							 $cross_sells_drop .= '<option value="' . $product->id . '">#' . $product->id . ' ' . $product->name . '</option>';
						 }
					 }
				 }

				 echo json_encode(['action' => true, 'up_sells' => $up_sells_drop, 'cross_sells' => $cross_sells_drop]);
				 exit;
			 } // end linked products



		 }
	}

	public function buildProductsJson()
	{
		$json_exist = false;
		$json_count = '';
		$products_count = Products::where('status', 'publish')->where('product_type', '<>', 'variation')->count();

		if(file_exists($this->connection.'_products.json'))
		{
			$json_count = count(json_decode(file_get_contents($this->connection.'_products.json')));
			$json_exist = true;
		}


		if($products_count != $json_count || $json_exist == false) {

			$products = Products::where('status', 'publish')->where('product_type', '<>', 'variation')->get(['id', 'sku', 'name']);
			$fp = fopen($this->connection.'_products.json', 'w');
			fwrite($fp, json_encode($products));
			fclose($fp);
		}

		echo json_encode(['action' => 'true']);
		exit;
	}

	public function productsDropDownFromJson()
	{
		if(file_exists($this->connection.'_products.json'))
		{
			return json_decode(file_get_contents($this->connection.'_products.json'));
		}else{
			$this->buildProductsJson();
			return json_decode(file_get_contents($this->connection.'_products.json'));
		}

	}


	public function checkSKU(Request $request)
    {
        $response = $this->product->checkSKU($request->all());
        if($response)
        {
            echo json_encode(array('action' => false, 'msg' => 'SKU is associated with another product'));
        }else{
            echo json_encode(array('action' => true));
        }
    }



    public function getQuickEditHtml($product_id)
    {
        if ($product_id) {
            $product = Products::with('Categories')
                ->with('Tags')
                ->with('Inventories')
                ->with('meta')
                ->where('product_type', '<>', 'variation')
                ->where('id', $product_id)
                ->first();
            if ($product) {

                $categories = $this->category->getCategories();

                $variation_data = '';
                $total_stock = 0;
                $variations_regular_price = array();
                $variations_sale_price = array();
                if ($product->product_type == 'variable') {

                    $stock_status = 'out';
                    foreach ($product->children as $child) {
                        $child_inv = $child->inventories;
                        if ($child_inv) {
                            if ($child_inv->stock_status == 'in') {
                                $stock_status = 'in';
                            }
                        }
                    }

                    $wait_list_str = '';
                    foreach ($product->children as $child) {

                        $variations_regular_price[] = $child->regular_price;
                        $variations_sale_price[] = $child->sale_price;

                        $inventories = $this->inventories->getInventoryByProductSKU($child->sku);
                        $attributes = $this->attributes->getAttributesByProductId($child->id);

                        //$variation_meta	= $child->meta;

                        $variation_meta_db = $this->getWaitList($child->id);
                        if ($variation_meta_db) {
                            $variation_meta = unserialize($variation_meta_db->meta_value);

                        } else {
                            $variation_meta = array();
                        }


                        if ($attributes && $inventories) {

                            $term_data = $this->terms->getTermBySlug(@implode(',', unserialize($attributes->attributes)));
                            if ($term_data) {
                                $variation_data .= '<strong>' . strtoupper($term_data->name) . ': </strong>' . $inventories->stock_qty . '<br>';
                            } else {
                                $variation_data .= '<strong>' . strtoupper(@implode(',', unserialize($attributes->attributes))) . ': </strong>' . $inventories->stock_qty . '<br>';
                            }

                            $total_stock += $inventories->stock_qty;

                        }

                        if (count(($variation_meta)) > 0 && $attributes) {
                            $c_attributes = unserialize($attributes->attributes);
                            $term_name = $this->terms->getTermBySlug(reset($c_attributes));
                            if ($term_name) {
                                //$wait_list_str .= strtoupper($term_name->name) . ': '. count(Functions::waitList($variation_meta)).'<br>' ;
                                $wait_list_str .= strtoupper($term_name->name) . ': ' . count($variation_meta) . '<br>';
                            } else {
                                //$wait_list_str .= strtoupper(reset($c_attributes)) . ': '. count(Functions::waitList($variation_meta)).'<br>' ;
                                $wait_list_str .= strtoupper(reset($c_attributes)) . ': ' . count($variation_meta) . '<br>';
                            }


                        }

                    }

                    if ($wait_list_str == '') {
                        $wait_list_str = 0;
                    }

                    $product->setAttribute('wait_list_str', $wait_list_str);


                    if ($stock_status == 'out') {
                        $product->setAttribute('stock_status_label', '<span class="out_stock">Out Of Stock</span>');
                    } else {
                        $product->setAttribute('stock_status_label', '<span class="in_stock">In Stock</span>');
                    }

                    $product->setAttribute('variations_data', $variation_data);
                    $product->setAttribute('total_stock', $total_stock);


                    if (!empty($variations_regular_price)) {
                        $regular_min_price = min($variations_regular_price);
                        $regular_max_price = max($variations_regular_price);
                    } else {
                        $regular_min_price = 0;
                        $regular_max_price = 0;
                    }


                    $product->setAttribute('regular_min_price', $regular_min_price);
                    $product->setAttribute('regular_max_price', $regular_max_price);

                    if (!empty($variations_sale_price)) {
                        $sale_min_price = min($variations_sale_price);
                        $sale_max_price = max($variations_sale_price);
                    } else {
                        $sale_min_price = 0;
                        $sale_max_price = 0;
                    }

                    $product->setAttribute('sale_min_price', $sale_min_price);
                    $product->setAttribute('sale_max_price', $sale_max_price);


                } elseif ($product->product_type == 'simple') {

                    $product_stock_status = $product->stock_status;
                    $inventories = $this->inventories->getInventoryByProductSKU($product->sku);
                    if ($product_stock_status == 'in') {
                        $stock_status = '<span class="in_stock">In Stock:</span>';
                    } else {
                        $stock_status = '<span class="out_stock">Out Of Stock</span>';
                    }

                    if ($inventories) {
                        $total_stock = $inventories->stock_qty;
                    } else {
                        $total_stock = '';
                    }

                    $product->setAttribute('total_stock', $total_stock);
                    $product->setAttribute('stock_status_label', $stock_status);

                    $variation_meta_db = $this->getWaitList($product->id);
                    if ($variation_meta_db) {
                        $variation_meta = unserialize($variation_meta_db->meta_value);

                    } else {
                        $variation_meta = array();
                    }

                    $product->setAttribute('wait_list_str', count($variation_meta));

                } elseif ($product->product_type == 'composite') {

                    $total_stock = 0;
                    foreach ($product->components as $component) {
                        $sku = $this->product->getSKUbyId($component->default_id);
                        $inventories = $this->inventories->getInventoryByProductSKU($sku);
                        if ($inventories) {
                            $total_stock += $inventories->stock_qty;
                        }
                    }


                    $product_stock_status = $product->stock_status;
                    if ($product_stock_status === 'out') {
                        $product->setAttribute('stock_status_label', '<span class="out_stock">Out Of Stock</span>');
                    } else {
                        $product->setAttribute('stock_status_label', '<span class="in_stock">In Stock</span>');
                    }

                    $product->setAttribute('total_stock', $total_stock);


                    $variation_meta_db = $this->getWaitList($product->id);
                    if ($variation_meta_db) {
                        $variation_meta = unserialize($variation_meta_db->meta_value);

                    } else {
                        $variation_meta = array();
                    }

                    $product->setAttribute('wait_list_str', count($variation_meta));

                }

                $color_swatches_new = array();
                $color_swatches = $this->product->getProductMeta('ce_colors_swatches_prod', $product->id);

                if ($color_swatches) {
                    $color_swatches = unserialize($color_swatches->meta_value);
                    foreach ($color_swatches as $color) {

                        $data = $this->product->fGetProductByID($color);
                        if ($data) {
                            $pdata = array('id' => $data->id, 'name' => $data->name);
                            $color_swatches_new[] = $pdata;
                        }
                    }
                }

                $product->setAttribute('color_swatches', $color_swatches_new);


                return view('products.products-quick-edit', ['product' => $product, 'categories' => $categories]);
                exit;
            }
        }
    }

	// end ajax functions
}