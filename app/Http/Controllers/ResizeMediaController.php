<?php
namespace App\Http\Controllers;
use App\Functions\Functions;

use App\RevenueReport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Products;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use Session;
use Config;
use DB;
use Intervention\Image\ImageManagerStatic as Image;



class ResizeMediaController extends Controller
{
    public function __construct()
    {
        $this->connection = Session::get('connection');
        echo '<h2 style="display: none;">'.$this->connection."</h2>";
        ini_set('memory_limit', '1024M');
    }


    public function resizeFolderImages()
    {
        //dd(Products::where('product_type', '<>', 'variation')->with('media')->with('media_featured_image')->limit(10)->get());
        //get featured images ids
        $products_ids = DB::connection(Session::get('connection'))->table('products')
            ->where('featured_image_id', '<>', 0)
            ->whereNotNull('featured_image_id')
            ->orderBy('featured_image_id', 'ASC')
            ->pluck('id');//

        //get Products images ids
        /*$ids = DB::connection(Session::get('connection'))->table('products')
            ->leftJoin('media_products', 'products.id', '=', 'media_products.product_id')
            ->whereNotNull('media_products.media_id')
            ->where('products.product_type', '<>', 'variation')
            ->orderBy('media_products.media_id', 'ASC')
            ->select('media_products.media_id')
            ->get();
        $media_ids = array();
        foreach($ids as $value)
        {
            array_push($media_ids,$value->media_id);
        }

        //Now merge both
        $merger = array_merge($featured_ids,$media_ids);*/
        rsort($products_ids);
        $estimatedtime = sizeof($products_ids)*1000/(1000*60);

        //$maxid = DB::connection(Session::get('connection'))->table('products')->max('featured_image_id');
        $maxid = max($products_ids);
        return view('pages.resizemedia',["ids"=>$products_ids,"maxid"=>$maxid,"estimatedtime"=>$estimatedtime]);//
    }

    public function resizeOneImage($id)
    {
        //$path = DB::connection(Session::get('connection'))->table('media')->where('id', $id)->value('path');//->pluck('id')
        $data_array[] = Products::where('id', '=', $id)->with('media')->with('media_featured_image')->first();
        //dd($data_array);
        $path =  $data_array[0]['relations']['media_featured_image']['original']['path'];
        $this->create_tumbnails($id, $path);
        //echo sizeof($data_array[0]['relations']['media']);exit;
        if(sizeof($data_array[0]['relations']['media'])>0)
        {
            foreach($data_array[0]['relations']['media'] as $mediaimages)
            {
                $path =  $mediaimages['original']['path'];
                $this->create_tumbnails($id, $path);
            }
        }

    }
    public function create_tumbnails($id, $path)
    {
        $filename = basename($path);
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename_without_extension = basename($path, "." . $file_ext);
        $clean_filename = Functions::clean_imageTitle($filename_without_extension);
        $new_filename = $clean_filename . "." . $file_ext;
        $path_parts = pathinfo($path);
        //echo "<pre>";print_r($path_parts);
        $path;
        $directory = $path_parts['dirname'];
        if(file_exists($directory)){
            //
            echo "<br/>Directory ".$directory." exists !. ID=".$id.".";
        }
        else
        {
            mkdir($directory, 0777, true);
            echo "<br/>Directory ".$directory." not exists ! created now. ID=".$id.".";
        }
        //echo $path;exit;
        if(file_exists($path)){
            $thumbnails = Config::get('domain-thumbnails');
            $connection = $this->connection;
            foreach ($thumbnails[$connection] as $connection_thumbnail) {
                $width = $connection_thumbnail['width'];
                $height = $connection_thumbnail['height'];
                $thumbnailPath =  $directory."/".$clean_filename.'-'.$width.'x'.$height.".".$file_ext;

                if(!file_exists($thumbnailPath)){
                    $image_resize = Functions::ResizeImages($width, $height, $path, $clean_filename, $file_ext, $directory."/");
                    echo "<br/>".$width."X".$height." Thumbnail Created. ID=".$id.".";
                }
                else{
                    echo "<br/>".$width."X".$height." Thumbnail already exists. ID=".$id.".";
                }
            }
        }
        else{
            echo "<br/>Main path of file --".$path."--not exists. Dowloading !. ID=".$id.".";
            $pathcontent = explode('/', $path);//remove thememusthave from path
            $content = @file_get_contents("https://themusthaves-1-themusthaves.netdna-ssl.com/wp-content/".$pathcontent[0]."/".$pathcontent[2]."/".$pathcontent[3]."/".$pathcontent[4]);
            if ($content === false) {
                /* failure */
                echo "image not found.";
            } else {
                /* success */
                //Store in the filesystem.
                $fp = fopen($path, "w");
                fwrite($fp, $content);
                fclose($fp);
                echo " Downloaded. Now resizing.";
                $thumbnails = Config::get('domain-thumbnails');
                $connection = $this->connection;
                foreach ($thumbnails[$connection] as $connection_thumbnail) {
                    $width = $connection_thumbnail['width'];
                    $height = $connection_thumbnail['height'];
                    $thumbnailPath =  $directory."/".$clean_filename.'-'.$width.'x'.$height.".".$file_ext;

                    if(!file_exists($thumbnailPath)){
                        $image_resize = Functions::ResizeImages($width, $height, $path, $clean_filename, $file_ext, $directory."/");
                        echo "<br/>".$width."X".$height." Thumbnail Created. ID=".$id.".";
                    }
                    else{
                        echo "<br/>".$width."X".$height." Thumbnail already exists. ID=".$id.".";
                    }
                }
            }
        }
        //echo json_encode(array('message'=>$message, 'r'=>$_GET['r'], 'w'=>$_GET['w']));
    }

    public function resizeFolderImages2(){

        ini_set('max_execution_time', 0);
        set_time_limit(0);
        /* $dirname = "uploads/themusthaves/2016/08/";
         $images = glob($dirname."*.jpg");
             foreach($images as $image) {
                 echo '<img src="'.$image.'" /><br />';
             }for($year = 2015; $year <= 2016; $year++){
                    for($mon = 1; $mon<= 12; $mon++){
                        $month = sprintf("%02d", $mon);
                        $dirname = 'uploads/'.$this->connection.'/'.$year.'/'.$month.'/';
                        if (is_dir($dirname)) {
                        $images = glob($dirname."*.jpg");
                        foreach($images as $image) {
                        echo $image;
                        echo "<br>";
                        // echo '<img src="'.$image.'" /><br />';
                        }
                     }
                 }
         }*/
        $paths = DB::connection(Session::get('connection'))->table('media')->pluck('path');

        foreach($paths as $path){

            $filename = basename($path);
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
            $filename_without_extension = basename($path, "." . $file_ext);
            $clean_filename = Functions::clean_imageTitle($filename_without_extension);
            $new_filename = $clean_filename . "." . $file_ext;
            $path_parts = pathinfo($path);
            $path;
            $directory = $path_parts['dirname'];

            if(file_exists($directory)){
                if(file_exists($path)){
                    $thumbnails = Config::get('domain-thumbnails');
                    $connection = $this->connection;
                    foreach ($thumbnails[$connection] as $connection_thumbnail) {
                        $width = $connection_thumbnail['width'];
                        $height = $connection_thumbnail['height'];
                        $thumbnailPath =  $directory."/".$clean_filename.'-'.$width.'x'.$height.".".$file_ext;
                        
                        if(!file_exists($thumbnailPath)){
                            $image_resize = Functions::ResizeImages($width, $height, $path, $clean_filename, $file_ext, $directory."/");
                        }
                    }
                }
                else{
                    echo "Main path of file --".$path."--not exists !"; }
            }
            else{

               echo "Directory ".$directory." not exists !";
            }
        }
        echo "Image resize from DB table is done!";
    }
}