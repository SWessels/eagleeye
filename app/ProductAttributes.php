<?php

namespace App;

use App\Functions\Functions;
use DB; 

class ProductAttributes extends BaseModel
{

    public $table = "productattributes";
    public  $timestamps = false; 

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    
    public function fGetAttributes(){ return ProductAttributes::where('type', 'default')->get(); }

    public function fGetProductsAttributes($attribute_id){

        return ProductAttributes::where('type', 'default')->get();

    }

    public function fGetProductsAttributesTerms($attribute_id){

        return Terms::where('attributes_id',$attribute_id)->orderBy('term_index', 'asc')->get();

    }

    public function getAttributeNameByID($id)
    {
        return ProductAttributes::select('name','type')->where('id',$id)->first();
    }
 

    public function fDeleteProductAttributes($products_id){
        //DB::connection()->enableQueryLog();
        $q =  Attributes::where('products_id', $products_id )->delete();

        //$query = DB::getQueryLog();
        //print_r($query);
    }

    public function addProductAttributes($attributes_id, $products_id, $attribute_values, $visible_on_product_page){

        $data = array(
            'attributes_id' 			=> $attributes_id,
            'products_id'				=> $products_id,
            'type'                      => 'default'
        );
      
        return Attributes::insert($data);

    }
    public function products(){
        return $this->hasOne('App\Products','product_id','id');
    }

    public function terms()
    {
        return $this->hasMany('App\Terms', 'attributes_id', 'id');
    }
    public  function  getattributesbyId($id){
        return ProductAttributes::where('id', $id)->first();


    }
    public  function attributeUpdatebyId($id, $arr){

        $name = trim($arr['name']);
        $slug = trim($arr['slug']);
        $slug =	Functions::makeSlug($slug, $duplicates_count = 0 ,$this->table, $id,$type= '');
        $dataupdate = array(
            'name' 			=> $name,
            'slug' 			=> $slug

        );
        $q=	ProductAttributes::where('id', $id)->update($dataupdate);

    }
    public function fGetAllAttributes(){


        return ProductAttributes::where('type', 'default')->with(['Terms' => function($query)
        {
            $query->orderBy('term_index', 'asc');

        }])->get();


    }
    


    public function AddnewAtt($array){
        $slugstr = trim($array['slug']);
        $slug =	Functions::makeSlug($slugstr, $duplicates_count = 0 ,$this->table, $id = '',$type= '');
        $name = trim($array['name']);
         $data = array(
            'name' 			=> $name,
            'slug' 			=> $slug,
            'type'          => 'default'
        );
       ProductAttributes::insert( $data );
        return redirect('attributes');

    }

    public function saveAtt($array){

        $slugstr = str_slug($array['slug'] , "-");
        $slug =	Functions::makeSlug($slugstr, $duplicates_count = 0 ,$this->table, $id = '',$type= '');
        $name = trim($array['name']);
        if(isset($array['type']))
        {
            $type = $array['type'];
        }else{
            $type = 'default';
        }


        $data = array(
            'name' 			=> $name,
            'slug' 			=> $slug,
            'type'          => $type
        );
        //dd($data);
        ProductAttributes::insert( $data );

    }

    public function checkIfExistBySlug($slug = false)
    {

        $data   = array('count' => 0, 'att_id' => '');
        if($slug)
        {
            $data_db = ProductAttributes::where('slug',$slug)->first();

            if($data_db)
            {
                $data = array('count' => count($data_db), 'att_id' => $data_db->id);
            }

        }
        return $data;

    }

    public function getCount()
    {
        return ProductAttributes::count();
    }
    
    public function getTermsbyIdAttid($id){


        return ProductAttributes::with('Terms')
            ->where('id', $id)->orderBy('id', 'desc')->Paginate(2);
    }


}

