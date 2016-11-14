<?php

namespace App\Http\Controllers;

use App\User;
use App\Media;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use Session;
use DB;
use Intervention\Image\ImageManagerStatic as Image;



class MediaController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->media = new Media();

    }

    public function index(){

        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!='')
        {
            $all_media = $this->media->getAllSearchMedia($_REQUEST['keywords']);
        }
        else{
        $all_media = $this->media->getAllMedia();
        }
        //dd($all_media);
        return view('media.media' , ['all_media' => $all_media]);
    }

    public function create(){

        return view('media.new_media');
    }

    public function showLibrary(Request $request)
    {
        $input = $request->all();
        $media = $this->media->showMediaLibrary($input );
        return json_encode($media);
      
     }
    public function showLibrary1($perpage ,$pageno)
    {
       return $media = $this->media->showMediaLibrary1($perpage ,$pageno);
        //return json_encode($media);

    }

    public function showLibrarySearch($perpage ,$pageno ,$search)
    {
        return $media = $this->media->showMediaLibrarySearch($perpage ,$pageno, $search);
        //return json_encode($media);

    }

    public function showDetail(Request $request){
        $input = $request->all();
        $media_detail = $this->media->showMediaDetail($input);
        return json_encode($media_detail);

    }
    public function  saveImagesById(Request $request){
        $input = $request->all();
        $media_save = $this->media->saveMediaImagesById($input);
        return json_encode($media_save);

        
    }
    public function  removeImageByIdForPosts(Request $request){
        $input = $request->all();
        $post_media_remove = $this->media->removeMediaByPostId($input);
        return $post_media_remove;

    }
    public function  removeImageByIdForProducts(Request $request){
        $input = $request->all();
        $product_media_remove = $this->media->removeMediaByProductId($input);
        return $product_media_remove;

    }
    public function saveImagesToDb(Request $request){
        $input = $request->all();
        $save_image = $this->media->saveMediaImages($input);
        $rootpath = dirname(dirname(dirname(dirname(__FILE__))));
        $jpegpath =   $rootpath."/public/".$save_image['0']['path'];
        //echo $jpegpath;
        //echo exec('whoami');
        //exec("jpegoptim $jpegpath");

        return $save_image ;
    }
    public function saveImagesForProductGallery(Request $request){
        $input = $request->all();
        $save_product_images = $this->media->saveMediaImagesForProduct($input);
       
        return $save_product_images ;
    }
    public function removeImagesForProductGallery(Request $request){
        $input = $request->all();
        $remove_product_gallery = $this->media->removeGalleryImages($input);

        return json_encode(['id' => $remove_product_gallery]);

    }
    Public function updateImageTitle(Request $request){
        $input = $request->all();
        $result = $this->media->saveImageTitleForJs($input);
        return $result;


    }
    Public function deleteImage(Request $request){
        $input = $request->all();
       return  $this->media->deleteImageFromDb($input);

    }
}

?>
