<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Session;
use App\Media;
class Sliders extends Model
{
    public $table = "sliders";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    public function media(){
        return $this->hasone('App\Media', 'id' ,'media_id');
       // return $this->belongsToMany('App\Media', 'media_sliders', 'slider_id' ,'media_id');
    }
    public function products()
    {
        return $this->hasManyThrough(
           'App\Products', 'App\Media' ,
           'media_id',  'media_id', 'featured_image_id'
        );
    }

    public function getAllSliders(){
        $mobSliders =   Sliders::with('media')->where('slider_type' , '=' , 'mobile')->orderBy('id', 'asc')->get();
        $desSliders =  Sliders::with('media')->where('slider_type' , '=' , 'desktop')->orderBy('id', 'asc')->get();
        $mobeHomeSliders =  Sliders::with('media')->where('slider_type' , '=' , 'mobile_homepage')->orderBy('id', 'asc')->get();
        $desHomeSliders =  Sliders::with('media')->where('slider_type' , '=' , 'desktop_homepage')->orderBy('id', 'asc')->get();
        $productSliders =  DB::connection($this->connection)->select('select m.path, a.media_id ,p.name from sliders a
                                               left join products p on a.media_id = p.id
                                               left join media m on p.featured_image_id = m.id
                                               where a.slider_type = "product" order by a.id asc ');
        $musthave_deal_widgetSliders =  DB::connection($this->connection)->select('select m.path, a.media_id ,p.name from sliders a
                                               left join products p on a.media_id = p.id
                                               left join media m on p.featured_image_id = m.id
                                               where a.slider_type = "musthave_deal_widget" order by a.id asc ');


        $arr_products_item = array();
        foreach($productSliders as $prd) {
            array_push($arr_products_item, [$prd->media_id, $prd->path, $prd->name]);
        }

        $data = array('mobSliders'  => $mobSliders,
                    'desSliders'  => $desSliders,
                    'mob_homeSliders'  => $mobeHomeSliders,
                    'des_homeSliders'  => $desHomeSliders,
                    'productSliders'  => $productSliders,
                    'musthave_deal_widgetSliders'  => $musthave_deal_widgetSliders
                    );
        return $data;
    }
   

    public function saveMobSliders($input){


        $count = Sliders::where('slider_type' , '=' , 'mobile')->count();
        if($count > 1){
            Sliders::where('slider_type' , '=' , 'mobile')->delete();
        }
         $slider_type = 'mobile';
        foreach($input['mob'] as $mob){

            $url =  $mob['url'];
            $imgid =  $mob['imageId'];
            $data = array(
                'media_id' 	    => $imgid,
                'link_url' 			=> $url,
                'slider_type'        => $slider_type,
            );
            DB::connection($this->connection)->table('sliders')->insert($data);

        }   
    }
    public function saveMobHomeSliders($input){

        $count = Sliders::where('slider_type' , '=' , 'mobile_homepage')->count();
        if($count > 1){
            Sliders::where('slider_type' , '=' , 'mobile_homepage')->delete();
        }
        $slider_type = 'mobile_homepage';
        foreach($input['mobHome'] as $mob){

            $url =  $mob['url'];
            $imgid =  $mob['imageId'];
            $data = array(
                'media_id' 	    => $imgid,
                'link_url' 			=> $url,
                'slider_type'        => $slider_type,
            );
            DB::connection($this->connection)->table('sliders')->insert($data);

        }
    }

    public function saveDesSliders($input){


        $count = Sliders::where('slider_type' , '=' , 'desktop')->count();
        if($count > 1){
            Sliders::where('slider_type' , '=' , 'desktop')->delete();
        }
        $slider_type = 'desktop';
        foreach($input['des'] as $mob){

            $url =  $mob['url'];
            $imgid =  $mob['imageId'];
            $data = array(
                'media_id' 	    => $imgid,
                'link_url' 			=> $url,
                'slider_type'        => $slider_type,
            );
            DB::connection($this->connection)->table('sliders')->insert($data);

        }
    }
    public function saveDesHomeSliders($input){


        $count = Sliders::where('slider_type' , '=' , 'desktop_homepage')->count();
        if($count > 1){
            Sliders::where('slider_type' , '=' , 'desktop_homepage')->delete();
        }
        $slider_type = 'desktop_homepage';
        foreach($input['desHome'] as $mob){

            $url =  $mob['url'];
            $imgid =  $mob['imageId'];
            $data = array(
                'media_id' 	    => $imgid,
                'link_url' 			=> $url,
                'slider_type'        => $slider_type,
            );
            DB::connection($this->connection)->table('sliders')->insert($data);

        }


    }

    public function saveProductSliders($input){
        $count = Sliders::where('slider_type' , '=' , 'product')->count();
        if($count > 1){
            Sliders::where('slider_type' , '=' , 'product')->delete();
        }

          $slider_type = 'product';
        foreach($input['product'] as $prod){

            $url =  '';
            $imgid =  $prod['id'];
            $data = array(
                'media_id' 	    => $imgid,
                'link_url' 			=> $url,
                'slider_type'        => $slider_type,
            );
            DB::connection($this->connection)->table('sliders')->insert($data);

        }

    }
    public function saveMusthave_deal_widget($input)
    {
        $count = Sliders::where('slider_type', '=', 'musthave_deal_widget')->count();
        if ($count > 1) {
            Sliders::where('slider_type', '=', 'musthave_deal_widget')->delete();
        }

        $slider_type = 'musthave_deal_widget';
        foreach ($input['product'] as $prod) {

            $url = '';
            $imgid = $prod['id'];
            $data = array(
                'media_id' => $imgid,
                'link_url' => $url,
                'slider_type' => $slider_type,
            );
            DB::connection($this->connection)->table('sliders')->insert($data);

        }
    }

        public function get_AllProducts($input){

        $keyword =  "%".str_replace('+','',$input['q'])."%";
        $products = DB::connection($this->connection)->table('products')->where('name' , 'like', $keyword)->where('parent_id' , '=', '0')->select('name as text', 'id')->get();
        return json_encode($products);

    }
    public function get_FeaturedImageById($input){

        $product_id =$input['product_id'];
        $prod =   Products::with('media_featured_image')->where('id' , '=', $product_id )->first();
        $path =   $prod['media_featured_image']['path'];
        return $path;

    }


}
