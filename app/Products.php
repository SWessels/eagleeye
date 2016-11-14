<?php

namespace App;

use DB;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Symfony\Component\CssSelector\Parser\Reader;
use App\Media;
use App\SeoData;
use App\Functions\Functions;

class Products extends BaseModel
{
	//

	private $product_id;
	private $product_title;
	private $product_slug;

	public $table = "products";


	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}



	public function media(){
		return $this->belongsToMany('App\Media', 'media_products', 'product_id' ,'media_id');
	}
	public function media_featured_image(){
		return $this->hasone('App\Media', 'id' ,'featured_image_id');
	}

	public  function seo_details()
	{
		return $this->hasone('App\SeoData', 'page_id' ,'id');
	}

	 



	public function createProduct($input = false)
	{

		$slug =	$this->makeSlug($input['product_name']);

		$name = trim($input['product_name']);
		$desc = trim($input['product-editor']);

		if(isset($input['action']) && $input['action']  == 'save_draft'){

			$status = 'draft';
		}
		elseif(isset($input['action']) && $input['action']  == 'publish'){
			$status = 'publish';
		}else{
			$status =  trim($input['status']);
		}

		$visible =  trim($input['visible']);
		if($input['publish_on'] == 1)
		{
			$published_at = $input['yy'].'-'.$input['mm'].'-'.$input['dd'].' '.$input['hr'].':'.$input['min'].':00';
		}else{
			$published_at = date('Y-m-d H:i:s');
		}


		$sale_price 			= Functions::priceForDB($input['sale_price']);;
		$regular_price 			= Functions::priceForDB($input['regular_price']);
		$sku					= $input['product_sku'];
		$sale_from				= '';
		$sale_to				= '';
		if($input['sale_from'] != '')
		{
			$sale_from				= $this->fromDateTime(strtotime($input['sale_from']));
		}
		if($input['sale_to'] != '')
		{
			$sale_to				= $this->fromDateTime(strtotime($input['sale_to']));
		}


		$updated_at				= date('Y-m-d H:i:s');
		$parent_id				= 0;
		$product_type			= $input['product_type'];

		if($product_type == 'composite')
		{
			$regular_price = Functions::priceForDB($input['discount_value_fld']);
		}


		$dataUpdate = array(
			'sku'				=> $sku,
			'name' 	    		=> $name,
			'user_id'        	=> Auth::id(),
			'sale_price'		=> $sale_price,
			'regular_price'		=> $regular_price,
			'description'   	=> $desc,
			'product_type'      => $product_type,
			'status'        	=> $status,
			'visibility'     	=> $visible,
			'updated_at'  		=> $updated_at,
			'publish_at'		=> $published_at,
			'sale_from'			=> $sale_from,
			'sale_to'			=> $sale_to,
			'parent_id'			=> $parent_id,
			'stock_status'		=> $input['stock_status']

		);



		Products::insert($dataUpdate);
		$lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
		if($product_type == 'simple')
		{
			$inventory_data = array(
				'product_sku'	=> $input['product_sku'],
				'manage_stock'	=> $input['inventory_level'],
				'stock_status'	=> $input['stock_status'],
				'stock_qty'		=> $input['stock_qty'],
				'allow_back_orders' => $input['allow_back_orders'],
				'allow_back_orders' => $input['back_order_limit'],
				'days_for_delivery' => $input['days_for_delivery']
			);
			Products::saveInventory($inventory_data);
		}


		$data = array(
			'name' 	        => '',
			'description'   => '',
			'parent_id'          => $lastInsertedId,
			'type'			=> 'details'
		);
		Tabs::saveTab($data);

		$product_id = $lastInsertedId;
		$seo_title = trim($input['seo_title']);
		$seo_desc = trim($input['seo_desc']);
		if(isset($input['is_index'])){
			$index = trim($input['is_index']);
			if($index == 'index' ){ $is_index = 1;}else if($index == 'non-index')  { $is_index = 0;}}
		else{
			$is_index = 1;
		}
		if(isset($input['is_follow'])){
			$follow = trim($input['is_follow']);
			if($follow == 'follow'){ $is_follow = 1;}else if($follow == 'no-follow')  { $is_follow = 0;}
		}
		else{
			$is_follow = 1;
		}
		$can_url = trim($input['can_url']);
		$red_url = trim($input['red_url']);

		$dataSeo = array(
			'page_id'  => $product_id,
			'title'				=> $seo_title,
			'description' 	    		=> $seo_desc,
			'is_index'        	=> $is_index,
			'is_follow'		=> $is_follow,
			'canonical_url'		=> $can_url,
			'redirect'   	=> $red_url,
			'type'      => 'product'
		);
		
		SeoData::saveSeoDetails($dataSeo);

		$categories = explode(',', $input['product-choose-category']);
		Products::find($lastInsertedId)->categories()->sync($categories);

		return $lastInsertedId;
	}

	public function fSaveProduct($array)
	{

		$type =  $array['type'];
		$slug =  $this->makeSlug($array['title']);
		$slug = str_slug($slug);

		$title = trim($array['title']);
		$data = array(
			'name' 			=> $title,
			'slug' 			=> $slug,
			'product_type'	=> $type,
			'created_at'	=> date('Y-m-d H:i:s')
		);

		Products::insert( $data );

		$lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
		$data = array(
			'name' 	        => '',
			'description'   => '',
			'parent_id'    => $lastInsertedId,
			'type'			=> 'details'
		);
		Tabs::saveTab($data);


		$link_data 		= array('up_sells' => '', 'cross_sells' => '', 'product_id' => $lastInsertedId);
		Products::insertLinkedProducts($link_data);



		$result = array(
			'last_inserted_product_id' 	=> $lastInsertedId,
			'product_slug'				=> $slug
		);
		echo json_encode($result);
	}

	public function autoSaveProduct($array)
	{

		$type =  $array['type'];
		$slug = str_slug($array['title']);

		$title = trim($array['title']);
		$data = array(
			'name' 			=> $title,
			'slug' 			=> $slug,
			'product_type'	=> $type,
			'created_at'	=> date('Y-m-d H:i:s')
		);

		Products::insert( $data );
		$lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
		$data = array(
			'name' 	        => '',
			'description'   => '',
			'parent_id'          => $lastInsertedId,
			'type'			=> 'details'
		);
		Tabs::saveTab($data);

		return $lastInsertedId ;

	}

	public function UpdateProductById($input, $id)
	{

		$name = trim($input['product_name']);
		$desc = trim($input['product-editor']);
		$status_upd  = true;
		if(isset($input['action']) && $input['action'] == 'publish')
		{
			$status = 'publish';
		}elseif(isset($input['action']) && $input['action'] == 'save_draft' ){
			$status = 'draft';
		}else{
			$status =  trim($input['status']);
		}

		$visible =  trim($input['visible']);
		if($input['publish_on'] == 1)
		{
			$published_at = $input['yy'].'-'.$input['mm'].'-'.$input['dd'].' '.$input['hr'].':'.$input['min'].':00';
		}else{
			$published_at = date('Y-m-d H:i:s');
		}


		$sale_price 			= Functions::priceForDB($input['sale_price']);
		if($input['product_type'] == 'composite')
		{
			$regular_price 			= Functions::priceForDB($input['discount_value_fld']);
		}else{
			$regular_price 			= Functions::priceForDB($input['regular_price']);
		}

		$sku					= $input['product_sku'];
		if($input['sale_from']!='')
		{
			$sale_from				= $this->fromDateTime(strtotime($input['sale_from']));
		}else{
			$sale_from				= '';
		}

		if($input['sale_to']!='')
		{
			$sale_to				= $this->fromDateTime(strtotime($input['sale_to']));
		}else{
			$sale_to				= '';
		}

		$updated_at				= date('Y-m-d H:i:s');
		$parent_id				= 0;
		$product_type			= $input['product_type'];


		$dataUpdate = array(
			'sku'				=> $sku,
			'name' 	    		=> $name,
			'user_id'        	=> Auth::id(),
			'sale_price'		=> $sale_price,
			'regular_price'		=> $regular_price,
			'description'   	=> $desc,
			'product_type'      => $product_type,
			'status'        	=> $status,
			'visibility'     	=> $visible,
			'updated_at'  		=> $updated_at,
			'published_at'		=> $published_at,
			'sale_from'			=> $sale_from,
			'sale_to'			=> $sale_to,
			'parent_id'			=> $parent_id,
			'stock_status'		=> $input['stock_status']

		);
		$product_id = trim($id);
		$seo_title = trim($input['seo_title']);
		$seo_desc = trim($input['seo_desc']);



		$can_url = trim($input['can_url']);
		$red_url = trim($input['red_url']);
		if(isset($input['is_index'])){
			$index = trim($input['is_index']);
			if($index == 'index' ){ $is_index = 1;}else if($index == 'non-index')  { $is_index = 0;}}
		else{
			$is_index = 1;
		}
		if(isset($input['is_follow'])){
			$follow = trim($input['is_follow']);
			if($follow == 'follow'){ $is_follow = 1;}else if($follow == 'no-follow')  { $is_follow = 0;}
		}
		else{
			$is_follow = 1;
		}

		$dataSeo = array(
			'page_id'  => $product_id,
			'title'				=> $seo_title,
			'description' 	    		=> $seo_desc,
			'is_index'        	=> $is_index,
			'is_follow'		=> $is_follow,
			'canonical_url'		=> $can_url,
			'redirect'   	=> $red_url,
			'type'      => 'product'
		);

		//echo $id; dd();

		DB::connection($this->connection)->beginTransaction();

		try {


			$categories = explode(',', $input['product-choose-category']);
			

			Products::find($id)->categories()->sync($categories);


			if (isset($input['inventory_level']) == 'on') {
				$manage_stock = 'yes';
			} else {
				$manage_stock = 'no';
			}
			if (isset($input['sold_individual']) == 1) {
				$sold_individual = 'yes';
			} else {
				$sold_individual = 'no';
			}


			if ($input['product_type'] == 'simple' || $input['product_type'] == 'composite') {
				if (Inventories::findBySKU($sku)) {


					$inventory_data = array(
						'manage_stock' => $manage_stock,
						'stock_status' => $input['stock_status'],
						'stock_qty' => $input['stock_qty'],
						'allow_back_orders' => $input['allow_back_orders'],
						'back_order_limit' => $input['back_order_limit'],
						'sold_individually' => $sold_individual,
						'days_for_delivery' => $input['days_for_delivery']

					);
					Inventories::updateInventory($inventory_data, $sku);

				} else {

					$inventory_data = array(
						'product_sku' => $sku,
						'manage_stock' => $manage_stock,
						'stock_status' => $input['stock_status'],
						'stock_qty' => $input['stock_qty'],
						'allow_back_orders' => $input['allow_back_orders'],
						'back_order_limit' => $input['back_order_limit'],
						'sold_individually' => $sold_individual,
						'days_for_delivery' => $input['days_for_delivery']

					);
					Inventories::insert($inventory_data);

				}

			}



			Products::where('id', $id)->update($dataUpdate);
			SeoData::saveSeoDetails($dataSeo);
			DB::connection($this->connection)->commit();
		}
		catch (\Exception $e) {


			DB::connection($this->connection)->rollback();
			echo $e->getMessage();
			echo $e->getCode();
			return $e->getCode();

		}
	}

	public function updateLinkedProducts($input,$id)
	{
		return LinkedProducts::where('product_id', $id)->update($input);
	}

	public function insertLinkedProducts($input)
	{
		return LinkedProducts::insert($input);
	}


	public function ifLinkedProductsExist($product_id = false)
	{
		$count = false;
		if($product_id)
		{
			$count = LinkedProducts::Where('product_id', '=', $product_id)->get();

			if(count($count)  == 0 )
				$count = false;
		}

		return $count;
	}

	public function hasMeta($meta = false, $product_id = false)
	{
		$count = false;
		if($meta && $product_id)
		{
			$count = Metas::where('meta_name', $meta )->Where('product_id', $product_id)->get();

			if(count($count)  == 0 )
				$count = false;
		}

		return $count;
	}



	public function updateProductStatus($array)
	{

		$product_id = $array['product_id'];
		$type = $array['type'];

		$data = array(
			'product_type'	=> $type
		);

		Products::where('id', $product_id)->update( $data );

	}

	public function saveVariation($array)
	{
		$slug = str_slug($array['title']);

		$title = trim($array['title']);
		$data = array(
			'name' 			=> $title,
			'slug' 			=> $slug,
			'created_at'	=> date('Y-m-d H:i:s'),
			'parent_id'		=> $array['parent_id'],
			'product_type'	=> 'variation'
		);

		Products::insert( $data );
		$lastInsertId = DB::connection($this->connection)->getPdo()->lastInsertId();
		$data = array(
			'name' 	        => '',
			'description'   => '',
			'parent_id'          => $lastInsertId,
			'type'			=> 'details'
		);
		Tabs::saveTab($data);

		return $lastInsertId ;

	}



	public function fUpdateProductSlugTitle($array)
	{
		$slug = str_slug($array['title']);
		$type = $array['type'];

		$title = trim($array['title']);
		$data = array(
			'name' 			=> $title,
			'slug' 			=> $slug,
			'product_type'	=> $type
		);

		Products::where('id', $_POST['id'])->update( $data );

		$result = array(

			'product_slug'				=> $slug
		);

		echo json_encode($result);
	}




	public function fGetProductByID($id){

		return Products::with('Inventories')
				->with('Categories')
				->with('LinkedProducts')
				->with('Tags')
				->with('Attributes')
				->with('Meta')
				->with('Components')
				->with('tabs')
				->with('media')
				->with('media_featured_image')
				->with('seo_details')
				->where('products.id',$id)
				->first();
	}

	public function getProductByName($name)
	{
		return Products::select('id', 'name')->where('products.name',$name)
			->first();
	}


    public function getProductForWaitList($sku)
    {
        return Products::select('id', 'sku', 'name', 'slug', 'parent_id', 'product_type')->with('parent')->with('media')->where('products.sku',$sku)->first();
    }

    public function getProductForWaitListById($id)
    {
        return Products::select('id', 'sku', 'name', 'slug', 'parent_id', 'product_type')->with('parent')->with('media')->where('products.id',$id)->first();
    }

	public function getAllProducts()
	{
		return Products::select(array('id', 'sku', 'name'))
			->where('product_type', '<>', 'variation')
			->orderBy('id')->get();
	}

	public function getAllProductsForJs()
	{
		$products = Products::select(array('id', 'sku', 'name'))
			->where('product_type', '<>', 'variation')
			->orderBy('id')->get();

		return json_encode($products);
	}

	public function productByName($name)
	{
		$products = Products::select(array('id', 'sku', 'name'))
			->where('product_type', '<>', 'variation')
			->where('name', 'like',  '%'.$name.'%')
			->orderBy('id')->get();

		return json_encode($products);
	}
 
	// get all product counts
	public function getProductsCounts()
	{
		$counts = array('all' => 0, 'published' => 0, 'draft' => 0, 'deleted' => 0);

		$all = Products::where('product_type', '<>', 'variation')->count();
		$counts['all'] = $all ;

		$query = Products::query('');
		$query->where('products.status', '=', 'publish')->where('product_type', '<>', 'variation');
		$counts['published'] = $query->count();

		$query = Products::query('');
		$query->where('products.status', '=', 'draft')->where('product_type', '<>', 'variation');
		$counts['draft'] = $query->count();

		$query = Products::query('');
		$query->where('products.status', '=', 'deleted')->where('product_type', '<>', 'variation');
		$counts['deleted'] = $query->count();


		return $counts;

	}
	// end

	public function productsWithInventories($ids = false)
	{

		if($ids)
		{
			return Products::with('children')->with('inventories')->whereIn('products.id', $ids)->get();
		}else{
			return Products::with('children')->with('inventories')->get();	
		}
		
	}

	// get product variations

	public function getProductVariations($id = false){
		$variations = array();

		if($id)
		{
			return Products::with('meta')->with('attributes')->with('inventories')->Where('parent_id', '=', $id)->orderBy('id', 'asc')->get();
		}

		return $variations;
	}

	public function getVariationById($id)
	{
		return $variations =  Products::with('Meta')->with('attributes')->Where('id', '=', $id)->first();
	}

	public function getProductVariationsCount($product_id = false)
	{
		$count  = 0 ;
		if($product_id)
		{
			$count =  Products::Where('parent_id', '=', $product_id)->get()->count();
		}
		return $count;
	}

	// end



	public function fUpdateSlug($id,$slug){

		$slug = str_slug($slug);  
		$slug = $this->makeSlug($slug, 0);  
		 
		$result = Products::where('id', $id)->update(['slug' => $slug]);

		return $slug;

	}

	public function tags(){
		return $this->belongsToMany('App\Tags','product_tag','product_id','tag_id');
	}

	public function categories(){
		return $this->belongsToMany('App\Categories','category_product','product_id','category_id');
	}

	/*public function attributes(){
		return $this->belongsToMany('App\Attributes','products_attributes','products_id','attributes_id')->select('*');
	}*/

	public function attributes()
	{
		return $this->hasOne('App\Attributes', 'product_id', 'id');
	}

	public function inventories(){
		return $this->hasOne('App\Inventories','product_sku','sku')->orderBy('stock_status');
	}

	public function linkedProducts()
	{
		return $this->hasOne('App\LinkedProducts', 'product_id', 'id');
	}

	public function meta()
	{
		return $this->hasMany('App\Metas', 'product_id', 'id');
	}

	public function components()
	{
		return $this->hasMany('App\Components', 'product_id', 'id');
	}

	public function tabs()
	{
		return $this->hasMany('App\Tabs', 'type', 'id');
	}

	public function parent()
	{
		return $this->belongsTo('App\Products', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('App\Products', 'parent_id')->orderBy('id', 'desc');
	}




	public function getProducts(){
		return Products::with('Categories')
			->with('Tags')
			->with('Inventories')
			->with('meta')
			->where('product_type', '<>', 'variation')
			->orderBy('id', 'desc')
			->paginate(60);
	}

	public function productSearch($args, $category, $sort_by = '', $sort_order = ''){

 		DB::connection($this->connection)->enableQueryLog();

		$query  = Products::query();
		if($category!='') {
			$query->whereHas('Categories', function ($query) use ($category) {
				$query->where('categories.id', '=', $category);
			});
		}

		$query->with('Categories')
				->with('Inventories')
				->with('meta')
				->with('children.meta')
				->with('components')
				->with('media_featured_image')
					->where(function($query) use ($args){
						$query->where('products.product_type', '<>', 'variation');
						foreach($args as $arg)
						{
							if($arg['column'] == 'sku')
							{
								//SELECT * FROM `products` WHERE id = (SELECT parent_id FROM products WHERE sku =99169002656 )
								//SELECT * FROM `products` WHERE products.id = (SELECT components.product_id from products left JOIN components ON products.id = components.default_id WHERE products.sku = 99169000427)

								$query->Where(
									'products.'.$arg['column'] ,
									$arg['operator'] ,
									$arg['value']);

								$query->orWhere(
									'products.id' ,
									'=' ,
									DB::raw('(SELECT parent_id FROM products WHERE sku ='.$arg['value'].')'));

								$query->orWhere(
									'products.id' ,
									'=' ,
									DB::raw('(SELECT components.product_id from products left JOIN components ON products.id = components.default_id WHERE products.sku ='.$arg['value'].')'));


							}else{
								$query->Where(
									'products.'.$arg['column'] ,
									$arg['operator'] ,
									$arg['value']);
							}

						}

					});

		// sort by
		if($sort_by!='' && $sort_order!='')
		{
			$sort_by=='price'?$sort_by='regular_price':$sort_by;
			$query->orderBy('products.'.$sort_by, $sort_order);
		}elseif($sort_by!='' && $sort_order=='')
		{
			$sort_order == 'asc';
			$sort_by=='price'?$sort_by='regular_price':$sort_by;
			$query->orderBy('products.'.$sort_by, $sort_order);
		}else{
			$query->orderBy('products.id', 'desc');
		}


		$products = $query->paginate(60);
		//$products = $query->get();
		//$products = $query->toSql();
		//dd($products);
		//$query = DB::connection($this->connection)->getQueryLog();
		//dd($query);
		//dd($products);
		return $products;


	}


	public function productDates(){

		return Products::select(DB::raw('DATE(created_at) as dates'))
			->Where('created_at','<>','0000-00-00 00:00:00')
			->groupBy('dates')
			->orderBy('dates', 'desc')
			->get();
	}

	public function saveProductMeta($meta_name = false, $meta_value = false, $product_id = false )
	{
		if($meta_name && $meta_value  && $product_id)
		{
			$data = array( 'meta_name' => $meta_name, 'meta_value' => $meta_value, 'product_id' => $product_id);
			if(Metas::insert( $data ))
			{
				return true;
			}
		}

		return false;
	}

	public function getProductMeta($meta = false, $product_id = false){
		$meta_result = array();
		if($meta && $product_id)
		{
			$meta_result =  Metas::Where('meta_name', '=', $meta)->Where('product_id', '=', $product_id)->first() ;
		}

		return $meta_result;
	}

	public function getCompositeProductMeta($meta = false, $product_id = false)
    {
        $meta_result = array();
        if($meta && $product_id)
        {
            $meta_result =  Metas::Where('meta_name', '=', $meta)->Where('product_id', '=', $product_id)->get() ;
        }

        return $meta_result;
    }


	public function updateProductMeta($meta_name = false, $meta_value = false, $product_id = false )
	{

		if($meta_name && $meta_value  && $product_id)
		{

			$data = array( 'meta_value' => $meta_value);
			return Metas::where('meta_name', $meta_name)
				->where('product_id', $product_id)
				->update( $data );


		}

		return false;
	}

	public function getProductIdByName($name)
	{
		return Products::select('id')->where('products.name',$name)->first();
	}

	public function getProductAttributes($id)
	{
		return Attributes::where('product_id', $id)->first();
	}

	public function ifProductAttributesExist($id)
	{
		$count =  Attributes::Where('product_id', '=', $id)->get()->count();
		if($count>0)
		{
			return true;
		}else{
			return false;
		}
	}


	public function addProductAttributes($data){
		if(!empty($data)) {

			if ($this->ifProductAttributesExist($data['product_id']) == true) {

				Attributes::where('product_id', $data['product_id'])
					->update( $data );

			} else {
				Attributes::insert($data);
			}
		}
	}

	public function updateVariation($data)
	{

		$variation_id = $data['variation_id'];
		$type = 'variation';
		$title = 'Variations #'.$data['parent_id'];
		$status = $data['status']=='on'?$status='publish':'draft';
		$sale_from = '';
		$sale_to = '';
		if($data['sale_form'] !='')
		{
			$sale_from = date('Y-m-d H:i:s', strtotime($data['sale_form']));
		}

		if($data['sale_to'] !='')
		{
            $sale_to = date('Y-m-d H:i:s', strtotime($data['sale_to']));
		}




		$var_data = array(
			'name' 			=> $title,
			'product_type'	=> $type,
			'regular_price' => Functions::priceForDB($data['regular_price']),
			'sale_price'	=> Functions::priceForDB($data['sale_price']),
			'sku'			=> $data['sku'],
			'status'		=> $status,
			'sale_from'		=> $sale_from,
			'sale_to'		=> $sale_to,
			'expected_date_of_delivery' => date('Y-m-d', strtotime($data['expected_date_delivery']))
		);



		$attributes = serialize($data['attributes']);
		$attributes_data = array(
			'attributes' => $attributes
		);


		Products::where('id', $variation_id)->update( $var_data );

		if(Inventories::findBySKU($data['sku'])) {

			$inventory_data = array(
				'manage_stock' => $data['manage_stock'],
				'stock_status' => $data['stock_status'],
				'stock_qty' => $data['stock_qty'],
				'allow_back_orders' => $data['allow_backorders'],
				'back_order_limit' => $data['back_order_limit'],
				'days_for_delivery' => $data['days_for_delivery']
			);
			Inventories::where('product_sku', $data['sku'])->update($inventory_data);
		}else{
			$inventory_data = array(
				'product_sku' => $data['sku'],
				'manage_stock' => $data['manage_stock'],
				'stock_status' => $data['stock_status'],
				'stock_qty' => $data['stock_qty'],
				'allow_back_orders' => $data['allow_backorders'],
				'back_order_limit' => $data['back_order_limit'],
				'days_for_delivery' => $data['days_for_delivery']
			);
			Inventories::insert($inventory_data);
		}

		Attributes::where('product_id', $variation_id)->update( $attributes_data );

	}

	public function updateComponent($data)
	{
		if(!empty($data))
		{
		    if(is_array($data['product']))
		        $components = implode("|",$data['product']);
            else
                $components = $data['product'];

			$component_data = array(
				'title' 			=> $data['title'],
				'description'		=> $data['desc'],
				'product_id'		=> $data['parent_id'],
				'default_id'		=> $components,
                'type'              => ($data['mode']=="true")?1:0
			);

			Components::where('id', $data['component_id'])->update( $component_data );
		}
	}

	public function saveInventory($inventory_data)
	{

		if(!empty($inventory_data))
		{
			return Inventories::insert($inventory_data);
		}

		return false;

	}

	public function updateProductStockStatus($product_id, $stock_status)
	{
		$upd_data = array( 'stock_status' => $stock_status); 
		if(Products::where('id', $product_id)->update($upd_data))
		{
			echo $product_id.' updated <br>';
		}else{
			echo $product_id.' Error <br>';
		}
	}

	public function deleteVariation($id)
	{
		if(Products::where('id', '=', $id)->delete())
		{
			Attributes::where('product_id', '=', $id)->delete();
			//Inventories::where('product_id', '=', $id)->delete();

		}

		return json_encode(array('action' => 'true'));
	}

	public function deleteAllVariations($parent_id = false)
	{

	}

	public function deleteVariationByParentId($parent_id = false)
	{
		if($parent_id)
		{
			return Products::where('parent_id', '=', $parent_id)->delete();
		}else{
			return false;
		}
	}

	public function saveComponent($data)
	{
		if(!empty($data))
		{
			if(Components::insert($data))
			{
				$cid =  $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
				return array('cid' => $cid, 'all_products' => $this->getAllProductsForJs());

			}
		}

		return false;
	}

	public function deleteComponent($id = false)
	{
		if($id)
		{
			return Components::where('id', '=', $id)->delete();
		}else{
			return false;
		}
	}

	public function moveToTrash($product_id)
	{
		if($product_id)
		{
			$dataUpdate = array('status' => 'deleted');
			return Products::where('id', $product_id)->update($dataUpdate);
		}
	}

	public function bulkDelete($product_ids = false)
	{
		if($product_ids)
		{
			$dataUpdate = array('status' => 'deleted');
			foreach ($product_ids as $id)
			{
				Products::where('id', $id)->update($dataUpdate);
			}

		}

		return true; 
	}

	public function getInventoryByProductSKU($product_sku = false)
	{
		if($product_sku)
		{
			return Inventories::where('product_sku', $product_sku)->first();
		}
	}

	public function getSKUbyId($product_id = false)
	{
		if($product_id)
		{
			return Products::select('sku')->where('id', $product_id)->first();
		}
	}

	public function makeSlugById($title, $duplicates_count = 0,$id)
	{
		$duplicates_count = (int) $duplicates_count ;

		$slug = $title = str_slug($title);


		if ($duplicates_count > 0) {
			$slug = $slug.'-'.$duplicates_count;
			$rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $slug)->where('id','!=' , $id)->count();
			if ($rowCount > 0) {
				return $this->makeSlugById($title, ++$duplicates_count,$id);
			} else {
				return $slug;
			}
		} else {
			$rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $title)->where('id','!=' , $id)->count();
			if ($rowCount > 0) {
				return $this->makeSlugById($title, ++$duplicates_count, $id);
			} else {
				return $title;
			}
		}

	}

	public function makeSlug($title, $duplicates_count = 0)
	{
		$duplicates_count = (int) $duplicates_count ;

		$slug = $title = str_slug($title);


		if ($duplicates_count > 0) {
			$slug = $slug.'-'.$duplicates_count;
			$rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $slug)->count();
			if ($rowCount > 0) {
				return $this->makeSlug($title, ++$duplicates_count);
			} else {
				return $slug;
			}
		} else {
			$rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $title)->count();
			if ($rowCount > 0) {
				return $this->makeSlug($title, ++$duplicates_count);
			} else {
				return $title;
			}
		}

	}
	
	
	public function getProductsForCoupons(){
		
		return Products::get();
	}


	public function draftExist($catID)
	{
		return DB::connection($this->connection)->table('category_products_temp')->where('category_id', $catID)->first();
	}


	public function sortedExist($catID)
	{
		return DB::connection($this->connection)->table('category_products_sorted')->where('category_id', $catID)->first();
	}

	public function getProductsByCategoryID($catID)
	{

		if($this->draftExist($catID))
		{
			DB::enableQueryLog(); 

			$products = DB::connection($this->connection)
						->table('category_products_temp')
						->select(	'products.id', 
									'products.name', 
									'products.slug',
								 	'products.regular_price', 
								 	'products.featured_image_id',
								 	'products.slug',
									'category_products_temp.category_id',
									'category_products_temp.product_id',
									'category_products_temp.sort_index',
									'media.path' 
									)
						->where('category_id', $catID)
						->leftJoin('products', function($join){
							 $join->on('products.id', '=', 'category_products_temp.product_id');				
						})
						->leftJoin('media', function($joinm){
							 $joinm->on('products.featured_image_id', '=', 'media.id');				
						})
						->orderBy('sort_index', 'ASC')
						->get();  

						//dd($products); 
			$loadfrom = 'draft'; 
				
		}elseif($this->sortedExist($catID))
		{
				$products = DB::connection($this->connection)
						->table('category_products_sorted')
						->select(	'products.id', 
									'products.name', 
									'products.slug',
								 	'products.regular_price', 
								 	'products.featured_image_id',
								 	'products.slug',
									'category_products_sorted.category_id',
									'category_products_sorted.product_id',
									'category_products_sorted.sort_index',
									'media.path' 
									)
						->where('category_id', $catID)
						->leftJoin('products', function($join){
							 $join->on('products.id', '=', 'category_products_sorted.product_id');				
						})
						->leftJoin('media', function($joinm){
							 $joinm->on('products.featured_image_id', '=', 'media.id');				
						})
						->orderBy('sort_index', 'ASC')
						->get();  

						 
					$loadfrom = 'sroted'; 			
		}else{
				$query  = Products::query();
				 $query->whereHas('Categories', function ($query) use ($catID) {
						$query->where('categories.id', '=', $catID);
					}); 

					$args = []; 
					$query->select([ 'products.id', 'sku', 'name', 'regular_price', 'featured_image_id' ])  
							->with('media_featured_image') 
							->where('products.product_type', '<>', 'variation');   
				    $query->orderBy('products.id', 'DESC'); 
				    //$query->limit(20); 

					$products = $query->get(); 
					$loadfrom = 'main'; 			
		}
 		 
		 
		return $arrayName = array('products' => $products, 'loadfrom' => $loadfrom); 
	}


	public function productQuickUpdate($input)
	{
		$id = $input['pid']; 
		$name = $input['pname'];
		$slug = $this->makeSlugById($name, 0 , $input['pid']);  
		$date =  date('Y-m-d H:i:s', strtotime($input['pdate']));  
		$visible =  $input['pvisible'];  
		$status =  $input['pstatus'];  
		$stock_status =  $input['pstockstatus']; 
		$regular_price 			= Functions::priceForDB($input['pprice']);
		$sale_price 			= Functions::priceForDB($input['psale']);
		$updated_at				= date('Y-m-d H:i:s');
		 
		 $tags = explode(',', $input['ptags']) ; 
		 $tags_data =  array();
		 foreach ($tags as $tag) {
		 	 if($tagDB = Tags::select('id')->where('name', trim($tag))->first())
		 	 {
				$tags_data[] =  $tagDB->id; 		 	 	
		 	 }else{
		 	 	$tslug = Functions::makeSlug($tag,  0 ,'tags' ,'','');
		 	 	$data = array(
						'name'	=> trim($tag),
						'slug'	=> $tslug
					 );
				Tags::insert( $data );
				
				$lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
		 		$tags_data[] =  $lastInsertedId;  	
		 	 }  
		 }

		 array_unique($tags_data); 

		 if(isset($input['pcategories']))
         {
             $categories = $input['pcategories'];
         }else{
             $categories = [];
         }



		$dataUpdate = array( 
			'name' 	    		=> $name,
			'slug'        		=> $slug,
			'sale_price'		=> $sale_price,
			'regular_price'		=> $regular_price, 
			'status'        	=> $status,
			'visibility'     	=> $visible,
			'updated_at'  		=> $updated_at,
			'published_at'		=> $date,  
			'stock_status'		=> $stock_status

		);



		DB::connection($this->connection)->beginTransaction();

		try {
            if(!empty($categories))
            {
                Products::find($id)->categories()->sync($categories);
            }

            if(!empty($tags_data))
            {
                Products::find($id)->tags()->sync($tags_data);
            }


            $up_sells 		= '';
            $cross_sells	= '';
            if(!empty($input['pupsells']))
            {
                $up_sells 	= implode('|', $input['pupsells'] );
            }
            if(!empty($input['pcrosssells'])) {
                $cross_sells = implode('|', $input['pcrosssells']);
            }

            $link_data 		= array('up_sells' => $up_sells, 'cross_sells' => $cross_sells);

            if($this->ifLinkedProductsExist($id))
            {
                $this->updateLinkedProducts($link_data, $id);
            }else{
                $link_data['product_id'] = $id;
                $this->insertLinkedProducts($link_data);
            }


			Products::where('id', $id)->update($dataUpdate);
			 
			DB::connection($this->connection)->commit();

			echo json_encode($arrayName = array('success' => true, 'msg' => 'Product updated.' )); 
		}
		catch (\Exception $e) {


			DB::connection($this->connection)->rollback(); 
			echo json_encode($arrayName = array('success' => false, 'msg' => 'Product updated failed.' )); 

		}
	}


	public function checkSKU($input)
    {
        $id = $input['id'];
        $sku = $input['sku'];
        return Products::where('sku', $sku)->where('id', '<>', $id)->first();
    }

}
