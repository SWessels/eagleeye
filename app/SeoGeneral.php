<?php

namespace App;

use App\Functions\Functions;
use DB;



class SeoGeneral extends BaseModel
{

    public $timestamps = false;
    public $table = "seo_general";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

 //////////////////////////Media relation with posts/////////////////

    public function saveSeo($input){
        
        
        foreach($input['seo'] as $item){

            $seo_title = trim($item['seoTitle']);
            $seo_desc = trim($item['seoDesc']);
            $url = trim($item['url']);
            $type = trim($item['type']);
            if(isset($item['isIndex'])){
                $index = trim($item['isIndex']);
                if($index == 'index' ){ $is_index = 1;}else if($index == 'non-index')  { $is_index = 0;}}
            else{
                $is_index = 1;
            }
            if(isset($item['isfollow'])){
                $follow = trim($item['isfollow']);
                if($follow == 'follow'){ $is_follow = 1;}else if($follow == 'no-follow')  { $is_follow = 0;}
            }
            else{
                $is_follow = 1;
            }
            $dataSeo = array(
                'title'			 => $seo_title,
                'description' 	 => $seo_desc,
                'is_index'       => $is_index,
                'is_follow'		 => $is_follow,
                'template_url'	 => $url,
                'type'           => $type
            );
            SeoGeneral::saveSeoGeneralDetails($dataSeo);
        }

    }
    
    
    public static function saveSeoGeneralDetails($input)
    {
        $type = $input['type'];
        $count = SeoGeneral::where('type' , '=' , $type)->count();

        if($count >= 1){
            SeoGeneral::where('type' , '=' , $type)->delete();
        }
        return SeoGeneral::insert($input);
    }

    public function getSeoDetails(){

        $product_tag =  SeoGeneral::where('type' , 'product-tag')->first();
        $post_tag =  SeoGeneral::where('type' , 'post-tag')->first();
        $image =  SeoGeneral::where('type' , 'media')->first();
        $attr =  SeoGeneral::where('type' , 'attribute')->first();
        $search =  SeoGeneral::where('type' , 'search-result')->first();
        $_404 =  SeoGeneral::where('type' , '404')->first();
        
        return $data = ['product-tag' => $product_tag,
                        'post-tag'    => $post_tag,
                        'media'    =>  $image,
                        'attribute'    => $attr,
                        'search_result'    => $search,
                        '_404'    =>  $_404];
    }
    
}


?>