<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;


use App\functions\Functions;


class Categories extends BaseModel
{
    // 
	public $table = 'categories';

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public  function seo_details()
	{
		return $this->hasone('App\SeoData', 'page_id' ,'id');
	}
	
	public function fCreateCategory($category_name,$parent_category_id=0){

		$slug = str_slug($category_name);
		$data = array(
			'name'					=> $category_name,
			'slug'					=> $slug,
			'parent_category_id'	=> $parent_category_id
					 );
		Categories::insert($data);
		
		$inserted_category_id = DB::connection($this->connection)->getPdo()->lastInsertId();
		
		return $inserted_category_id;
	}


	public function fSavecategory($array)
	{
		$slug = trim($array['slug']);
		$slug =  Functions::makeSlug($slug , $duplicates_count = 0 ,$this->table, $id = '',$type= '');
		$name = trim($array['name']);
		$desc = trim($array['desc']);
		$parent =trim($array['parent']);
		$status =trim($array['status']);
		$data = array(
					'name' 				=> $name,
					'slug' 				=> $slug,
				    'description'   	=> $desc,
				    'parent_category_id'  => $parent,
					'status'              =>$status,
					'created_at'		=> date('Y-m-d H:i:s')
		);

		Categories::insert( $data );
		$inserted_category_id = DB::connection($this->connection)->getPdo()->lastInsertId();

		return $inserted_category_id;

	}

	public function getCategoryById($id){
		
		return Categories::with(['seo_details' => function($query) {
			                    $query->where('seo_data.type', 'product-category');}])
			                    ->where('id', $id)->first();
		
	}

	public function getSelectedProductId($cat_id, $product_id){

		$countPostCategories =  count(DB::connection($this->connection)->table('category_product')->where('category_id', $cat_id)->where('product_id', $product_id)->get());
		if($countPostCategories > 0){
			return 'checked="checked"';

		}
		else{
			return '';
		}

	}

////////////////////////////////////GET ALL CATEGORIES WITH PARENT/////////////////////////////////////////////

	public function getCategories($parentId = 0, $level=0, $product_id = 0 )
		{
			$categories = [];
		    $c = Categories::where('parent_category_id', $parentId)
				->orderBy('id', 'desc')->get();

			foreach( $c  as $category)
			{
				//echo $category->id;
				$categories[] = [
					'category_id' 		=> $category->id,
					'category_name' 	=> $category->name,
					'description' 		=> $category->description,
					'status' 			=> $category->status,
					'category_slug' 	=> $category->slug,
					'category_level'	=> $level,
					'children' 			=> $this->getCategories($category->id, $level+=1, $product_id),
					'product_count' 	=> $this->getCountProductsOfCategories($category->id),
					'selected'			=> $this->getSelectedProductId($category->id, $product_id)
				];
				$level--;
			}


			return $categories;
		}


	public function getCategoriesDB()
	{
		return Categories::all(); 
	}	

	//////////////////////////////////////GET CATEGORIES WITHOUT THIS ID//////////////////////////////////////

		public function getCategoryNotId($parentId = 0, $level=0, $id){
			
				$categoriesNotId = [];
				$c =  Categories::where('parent_category_id', $parentId)->where('id','!=', $id)->get();
				foreach( $c  as $category)
				{
					//echo $category->id;
					$categoriesNotId[] = [
						'category_id' 		=> $category->id,
						'category_name' 	=> $category->name,
						'description' 		=> $category->description,
						'status' 			=> $category->status,
						'category_slug' 	=> $category->slug,
						'category_level'	=> $level,
						'children' 			=> $this->getCategoryNotId($category->id, $level+=1,$id),
						'product_count' 	=> $this->getCountProductsOfCategories($category->id)
					];
					$level--;
				}
				return $categoriesNotId;
			}
////////////////////////////////////////////////UPDATE FUNCTION//////////////////////////////////////////////////
	public function fUpdateCategory($id, $arr){

		$name = trim($arr['name']);
		$desc = trim($arr['desc']);
		$parent =trim($arr['parent']);
		$status =trim($arr['status']);

		$slug = trim($arr['slug']);
		$slug = Functions::makeSlug($slug ,$duplicates_count = 0 ,$this->table, $id, $type= '');

			$dataupdate = array(
								'name' 			=> $name,
								'slug' 			=> $slug,
								'description'   => $desc,
								'parent_category_id'  => $parent,
								'status'              =>$status,
								'updated_at'	=> date('Y-m-d H:i:s')
			);
			//DB::connection()->enableQueryLog();
			$q=	Categories::where('id', $id)->update($dataupdate);
			//$q = DB::getQueryLog();
			//dd($q);
	}
		public function getCountProductsOfCategories($catId)
		{
			return  count(DB::connection($this->connection)->table('category_product')->where('category_id', $catId)->get());
		}


	public function products(){
		return $this->belongsToMany('App\Products','category_product','category_id','product_id');
	}


	

}
