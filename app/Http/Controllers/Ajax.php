<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;
use DateTime;
use DB;
class Ajax extends BaseController
{
	public function __construct()
	{
		parent::__construct();
	}

	//
	public function saveProduct(){
		$product = new \App\Products;
		$product->fSaveProduct($_POST);
	}
	
	public function updateProductSlugTitle(){
		$product = new \App\Products;
		$product->fUpdateProductSlugTitle($_POST);
	}
	
	public function updateSlug(){
		$id = $_POST['id'];
		
		$slug = $_POST['new_slug'];
		$product = new \App\Products;
		
		$result = $product->fUpdateSlug($id,$slug);
		echo $result;
	}
	
	
	public function saveCategory(){
		
		$parent_category_id = !empty($_POST['parent_category_id'])?$_POST['parent_category_id']:0;
		$category_name = $_POST['category_name'];
		
		$category = new \App\Categories;
		
		$result = $category->fCreateCategory($category_name,$parent_category_id);
		echo $result;
	}
	public function savePostCategory(){

		$parent_category_id = !empty($_POST['parent_category_id'])?$_POST['parent_category_id']:0;
		$category_name = $_POST['category_name'];

		$postcategory = new \App\PostCategories;

		$result = $postcategory->CreateCategory($category_name,$parent_category_id);
		echo $result;
	}
	
	
	
	public function saveProductTag(){
		
		$tag = new \App\Tags;
		$product_id = $_POST['product_id'];
		$tag_id = isset($_POST['tag_id'])?$_POST['tag_id']:"";
		if(empty($tag_id)){
		$result = $tag->fAddTags($_POST['name']);
		$tag_id = $result;
		}
		$response = $tag->fAddProductWithTag($tag_id,$product_id);
		echo $tag_id;
	}
	public function get_attribute_terms_by_id(){
	$attribute = new  \App\ProductAttributes;
	$id = $_POST['id'];

	//$attributes_value = $attribute->fGetProductsAttributes($id);
	//print_r($attributes_value);
	//exit;
	$attributes_terms = $attribute->fGetProductsAttributesTerms($id);

	foreach($attributes_terms as $value){
		$attributs[] = array(
			'slug'  => $value->slug,
			'name' 	=> $value->name,
			'id'	=> $value->id
		);
	}

	echo json_encode($attributs);
}
	public function saveAttTerms(){
		$term = new \App\Terms;
		//print_r($_POST);
		
		
		//$att_id = $_POST['att_id'];
		$result = $term->saveTerm($_POST);
		

		$data=array('id'=> $result ,
					'name'=> $_POST['name'],
					'slug'=> $_POST['slug'],
					'desc' =>$_POST['desc'] );

		echo json_encode($data);
	}
	
	public function getTags(){
		
		print_r($_REQUEST);
		exit;
		
		$tags = array(
							0 => array('name'=>'Test1'),
							1 => array('name'=>'Test1'),
							2 => array('name'=>'Test1'),
							3 => array('name'=>'Test1'),
					 );
		echo json_encode($tags);
	}
	
	public function delete_tag_with_product(){
		$tag = new \App\Tags;
		
		$tag->fDeleteTagWithProduct($_POST['tag_id'],$_POST['product_id']);
		
	}
	
	public function product_quick_edit($id){
		echo $id;
	}
	

	
	public function save_attribute_features(){
		$attribute = new  \App\Attributes;
		
		$result = $attribute->fDeleteProductAttributes($_POST['attributes_features'][0][0]);
		foreach($_POST['attributes_features'] as $value){
			
			$product_id = $value[0];
			$attribute_id = $value[1];
			$terms = serialize($value[2]);
			$is_show =  $value[3];
			
			echo $result = $attribute->addProductAttributes($attribute_id, $product_id, $terms,$is_show);
			
		}
	}

	public function saveindexterms(){
		$term = new \App\Terms;
		//print_r($_POST);
		  $term->save_term_indexes($_POST);
	}

	public function getDaysOfMonth(Request $request){
	    $input = $request->all() ;
        $days = cal_days_in_month(CAL_GREGORIAN, $input['month'], $input['year']);
        $options = '';
        $today = date('d');


        for ($i = 1; $i <=  $days; $i++)
        {
            if($input['year'] == date('Y') && $input['month'] == date('m') && $i<$today)
            {
                $options .='<option value="'.$i.'" disabled="disabled">'.$i.'</option>';
            }else{
                $options .='<option value="'.$i.'">'.$i.'</option>';
            }

        }

        echo json_encode(array('action' => true, 'select_options' => $options));
    }

    public function getMonthsOfYear(Request $request)
    {
        $input = $request->all() ;
        $months = 12;
        $options = '';
        $this_month = date('m');
        for ($i = 1; $i <=  $months; $i++)
        {
            $dateObj   = DateTime::createFromFormat('!m', $i);
            $monthName = $dateObj->format('F'); // March

            if($input['year'] == date('Y') && $i<$this_month)
            {
                $options .='<option value="'.$i.'" disabled="disabled">'.$monthName.'</option>';
            }else{
                $options .='<option value="'.$i.'">'.$monthName.'</option>';
            }

        }

        echo json_encode(array('action' => true, 'select_options' => $options));
    }


    public function getCategoryProducts(Request $request)
    {
        $input = $request->all() ;
        if(isset($input['catid']))
        {
            $query  = Products::query();
            $category = $input['catid'];
            if($category!='' && $category !='all') {
                $query  = Products::query();
                if($category!='') {
                    $query->whereHas('Categories', function ($query) use ($category) {
                        $query->where('categories.id', '=', $category);
                    });
                }

                $query->select('id', 'name');
                $query->with('Categories')
                    ->where('products.product_type', '<>', 'variation');

                $products = $query->get();

                $options = '';
                foreach ($products as $product)
                {
                    $options .='<option value="'.$product->id.'"> '.$product->name.' </option>';
                }

                echo json_encode(array('action' => 'true', 'select_options' => $options));
                exit;
            }elseif($category == 'all'){
                $products = Products::where('products.product_type', '<>', 'variation')->select('id', 'name')->get();
                $options = '';
                foreach ($products as $product)
                {
                    $options .='<option value="'.$product->id.'"> '.$product->name.' </option>';
                }

                echo json_encode(array('action' => 'true', 'select_options' => $options));
                exit;
            }
        }
    }


    public function saveOrderValues(Request $request)
    {
        $input = $request->all() ;


        if(!empty($input))
        {
            $ch = DB::connection($this->connection)->table('action_order_values')->where( 'min_order_value', $input['minval'])->where('max_order_value', $input['maxval'] )->first();
            if($ch != null)
            {
                echo json_encode(array('action' => false, 'msg' => 'Order Values already exist'));
                exit;
            }
            if(DB::connection($this->connection)->table('action_order_values')->insert( ['min_order_value' => $input['minval'], 'max_order_value' => $input['maxval']] ))
            {
                $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
                if($input['maxval'] == '')
                {
                    $txt = money($input['minval']). ' - ∞' ;
                }else{
                    $txt = money($input['minval']). ' - '.money($input['maxval']);
                }

                echo json_encode(array('action' => true, 'option_id' => $lastInsertedId, 'option_text' => $txt));
                exit;
            }


        }
        echo json_encode(array('action' => false));
        exit;
    }
}
