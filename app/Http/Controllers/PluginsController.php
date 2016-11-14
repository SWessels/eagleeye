<?php

namespace App\Http\Controllers;
use App\User;
use App\Products;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Functions\Functions;
use App\Http\Requests;
use App\Categories;
use App\SeoGeneral;
use App\Sitemap;
use Session;
use DB; 

class PluginsController extends BaseController
{
    private $category;
    private $product;
	private $seo;
	private $sitemap;
	protected $connection;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 public function __construct()
    {
        parent::__construct();
       // check if user has products capability
        if(User::userCan('category_drag_drop') === false)
			{
				abort(403, 'Unauthorized action.');
			}
		$this->product 		= new Products;
		$this->category 	= new Categories;
		$this->seo_general 	        = new SeoGeneral;
		$this->sitemap 	        = new Sitemap;
		$this->connection = Session::get('connection');
   
	}

	public function categoryProductsDragAndDrop($catID = false)
	{ 
		$all_categories  = $this->category->getCategoriesDB();

		if($catID)
		{  
			$products  = $this->product->getProductsByCategoryID($catID); 

        	return view('plugins.categoriesdragdrop', ['catid' => $catID, 'categories' => $all_categories, 'products' => $products['products'], 'loadfrom' => $products['loadfrom'], "list" => true ]); 

		}else{ 

        	return view('plugins.categoriesdragdrop', ['catid' => '',  'categories' => $all_categories, "list" => false ]);	
		} 
	}

	public function saveDraftSorted(Request $request)
	{ 
		$input =  $request->all();   
		if($input['cat'])
		{
			 //echo "<pre>"; print_r($menu_array); echo "</pre>";
        DB::connection($this->connection)->beginTransaction();

        if($input['status'] == 'publish')
        {
        	$table = 'category_products_sorted'; 
        }
        else
        {
			$table = 'category_products_temp'; 
        }

        try {
 

	            $sortedProducts = json_decode($input['main_sorted_data']) ; 

				$i = 1; 
				if(!empty($sortedProducts))
				{
	 				// delete all records of the category 
	        		if($input['status'] == 'publish')
	        		{
						DB::connection($this->connection)->table($table)->where('category_id', $input['cat'])->delete();
						DB::connection($this->connection)->table('category_products_temp')->where('category_id', $input['cat'])->delete();
	        		}else{
	        			DB::connection($this->connection)->table($table)->where('category_id', $input['cat'])->delete();
	        		} 
	           
  					// insert new main sorted data
					foreach ($sortedProducts as $product) {
						$index = $i++;   
						if(isset($product->pid))
						{
							 $pid =  $product->pid; 
							 $data_insert = array('category_id' => $input['cat'],'product_id' => $pid, 'sort_index' => $index);
							 
					        DB::connection($this->connection)->table($table)->insert($data_insert);
				    	} 
					}
				} 


				if($input['status']  == 'draft')
				{ 
					// insert left reserve area products
					$leftProducts = json_decode($input['left_reserve']) ;  
					 	
					if(!empty($leftProducts))
					{

						foreach ($leftProducts as $leftProduct) {
							 
							 
							if(isset($leftProduct->pid))
							{
								 $data_insert = array('category_id' => $input['cat'],'product_id' => $leftProduct->pid, 'sort_index' => -1);
								 
						        DB::connection($this->connection)->table('category_products_temp')->insert($data_insert);
					    	}	
						}
					}

					// insert right reserve area products
					$rightProducts = json_decode($input['right_reserve']) ; 
					 
					if(!empty($rightProducts))
					{

						foreach ($rightProducts as $rightProduct) {
							 
							 
							if(isset($rightProduct->pid))
							{
								 $data_insert = array('category_id' => $input['cat'],'product_id' => $rightProduct->pid, 'sort_index' => -2);
								 
						        DB::connection($this->connection)->table('category_products_temp')->insert($data_insert);
					    	}	
						}	
					}	 
				}

	            DB::connection($this->connection)->commit();
				return json_encode(array('response' => true)); 
        	}

        catch (\Exception $e) { 
	            DB::connection($this->connection)->rollback(); 
	            return json_encode(array('response' => false, 'error' =>  $e->getMessage())); 

       		}	
			
		}
	}


	public function seoGeneralSettings(){

		return view('seo.seo_general');
	}
	public function saveSeoGeneralSettings(Request $request){
		$input =  $request->all();
		$saveSeo = $this->seo_general->saveSeo($input);
		$getSeo = $this->seo_general->getSeoDetails();
		Session::flash('flash_message', 'Seo Details Successfully Created!');
		return view('seo.edit_seo_general', [ 'data' => $getSeo]);
	}

	public function show_sitemap_index(){
		
		$sitemap = $this->sitemap->get_sitemap();
		return view('seo.sitemap', [ 'data' => $sitemap]);
	}
	
	public function sitemap_index_details($sitemap){

		$xml=simplexml_load_file('xml/'.$sitemap);

		return view('seo.sitemap_details', [ 'data' => $xml, 'sitemap' => $sitemap]);
		
		
	}


}
