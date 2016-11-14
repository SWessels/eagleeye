<?php

namespace App;

use App\Functions\Functions;
use App\Input;
use App\Products;
use App\Sliders;
use Session;
use App;
use DB;
use Auth;
use Config;
use Intervention\Image\ImageManagerStatic as Image;
define('THUMBNAIL_IMAGE_MAX_WIDTH', 120);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 184);
class Media extends BaseModel
{

    public $timestamps = false;
    public $table = "media";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

 //////////////////////////Media relation with posts/////////////////

    public function posts()
    {
      return $this->belongsToMany('App\Posts' ,'media_posts' , 'post_id' , 'media_id');
    }
    public function sliders()
    {
        //return $this->belongsToMany('App\Sliders' ,'media_sliders' , 'slider_id' , 'media_id');
        return $this->belongsTo('App\Sliders');
    }



    //////////////////////////Media relation with posts/////////////////

    public function products()
    {
        return $this->belongsToMany('App\Products' ,'media_products' , 'product_id' , 'media_id');
    }
///////////////////////Product Feature Image relation///////////////
    public function product_featured_image()
    {
        return $this->belongsTo('App\Products');
    }
    ///////////////////////Post Feature Image relation///////////////
    public function post_featured_image()
    {
        return $this->belongsTo('App\Posts');
    }

    public function user(){
        return $this->belongsTo('App\User','uploaded_by','id' );
    }

 ///////////////////////Show media library///////////////////////////

    public function showMediaLibrary($input)
    {
        if(isset($input['keyword']) && $input['keyword'] != ''){
           $keyword	= "%".str_replace('+','',$input['keyword'])."%";
           return Media::where('alt_text', 'like',  $keyword)->get();

        }
        else{
            return Media::skip(0)->take(60)->get();
        }
    }
    ///////////////////////Get All Media///////////////////////////

    public function showMediaLibrary1($perpage ,$pageno)
    {
        $skip = ($pageno-1)*$perpage;
        $result =  Media::orderBy('uploaded_on', 'desc')->orderBy('id', 'desc')->skip($skip)->take($perpage)->get();
        $media_array =[];
        foreach($result as $rs) {
            $media_array[] = [

                'id' => $rs->id,
                'alt_text' => $rs->alt_text,
                'db_path'  => $rs->path,
                'path' => $this->galleryThumb($rs->path)
            ];
        }


        //dd($media_array);
        $total_count = Media::count();
        $data_array= array('error' => 0 , 'total_count'=>$total_count , 'data'=>   $media_array );

        return json_encode($data_array, JSON_UNESCAPED_SLASHES);

    }

    public function showMediaLibrarySearch($perpage ,$pageno, $search){
        if(isset($search) && $search != '') {
            $keyword = "%" . str_replace('+', '', $search) . "%";
        }
        $skip = ($pageno-1)*$perpage;
        $result =  Media::where('alt_text', 'like',  $keyword)->orderBy('uploaded_on', 'desc')->orderBy('id', 'desc')->skip($skip)->take($perpage)->get();

        $total_count = Media::where('alt_text', 'like',  $keyword)->count();
        $data_array= array('error' => 0 , 'total_count'=>$total_count , 'data'=>   $result );

        return json_encode($data_array, JSON_UNESCAPED_SLASHES);
    }

    public function getAllMedia()
    {

        return Media::with('user')->orderBy('uploaded_on', 'desc')->orderBy('id', 'desc')->paginate(60);

    }
    ///////////////////////Search Media///////////////////////////

    public function getAllSearchMedia($keyword)
    {
        $keyword	= "%".str_replace('+','',$keyword)."%";
        return  Media::where('alt_text', 'like',  $keyword)->paginate(60);



    }
    ///////////////////////Show selected media detail/////////////////

    public function showMediaDetail($input)
    {
        $id = $input['img_id'];
        return Media::with('user')->where('id', '=', $id)->first();
    }

   //////////////////Attach images in DB///////////////////////////////

    public function saveMediaImagesById($input){

        // echo "<pre>";print_r($input);
        $media_type = $input['media_type'];
        $image_type = $input['image_type'];
        $parent_id = $input['parent_id'];
        $image_id = $input['image_detail'];

        $new_featured_detail = DB::connection($this->connection)->table('media')->where('id', $image_id)->first();

        $new_featured_detail->path;
        $path_parts = pathinfo($new_featured_detail->path);
        $filedestination = $path_parts['dirname'];
        $filename = basename($new_featured_detail->path);
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename_without_extension = basename($new_featured_detail->path, "." . $file_ext);
        $newfilename_without_extension = $filename_without_extension;
        $newfilename = $newfilename_without_extension . "." . $file_ext;
        $destinationPath2 = $filedestination . "/";
        $pathForResize = $filedestination . "/" . $filename_without_extension;
        $newImagePath = $filedestination . "/" . $newfilename;

        if ($file_ext != 'png') {
            $thumbnails =Config::get('domain-thumbnails');
            $connection =  $this->connection;

            foreach($thumbnails[$connection] as $connection_thumbnail) {
                $width = $connection_thumbnail['width'];
                $height = $connection_thumbnail['height'];

                $image_resize = $this->ResizeImages($width, $height, $newImagePath, $newfilename_without_extension, $file_ext, $destinationPath2);
            }


        }
        if($media_type == 'post'){

            DB::connection($this->connection)->table('posts')->where('id', $parent_id)->update(['featured_image_id' => $image_id]);
            return   $imageDetailById =   DB::connection($this->connection)->table('media')->where('id', '=', $image_id )->first();

        }

       elseif ($media_type == 'product'){
                DB::connection($this->connection)->table('products')->where('id', $parent_id)->update(['featured_image_id' => $image_id]);
                return   $imageDetailById =   DB::connection($this->connection)->table('media')->where('id', '=', $image_id)->first();
                }


      }

    ///////////////////Remove attached images from post table/////////////////////

    public function removeMediaByProductId($input){

        $parent_id = $input['parent_id'];
        DB::connection($this->connection)->table('products')->where('id', $parent_id)->update(['featured_image_id' => '']);

     }

    ///////////////////Remove attached images from Product table/////////////////////

    public function removeMediaByPostId($input){

        $parent_id = $input['parent_id'];
        DB::connection($this->connection)->table('posts')->where('id', $parent_id)->update(['featured_image_id' => '']);

    }

    ///////////////Save uploaded image in DB/////////////////////////////
    public function saveMediaImages($input){



        foreach($input['files'] as $file) {


            $file_originalname          = $file->getClientOriginalName();
            $file_extension             = $file->getClientOriginalExtension();
            $file_is_valid              = $file->isValid();
            $file_error                 = $file->getError();
            $file_size_bytes            = $file->getSize();
            $file_size                  =  $this->formatSizeUnits($file_size_bytes);

            $file_name                  = basename($file_originalname, "." . $file_extension);
            $clean_filename             = $this->clean_imageTitle($file_name);
            $new_filename               = $clean_filename.".".$file_extension;
            $existedupload              = $clean_filename."-120x184.".$file_extension;
            $connectionPath             = $this->connection;
            $currentYearDirectoryName   = date('Y');
            $currentMonthDirectoryName  = date('m');

            $destinationPath            = "uploads/" .$connectionPath . "/" . $currentYearDirectoryName . "/" . $currentMonthDirectoryName;
            $destinationPath2           = "uploads/" .$connectionPath . "/" . $currentYearDirectoryName . "/" . $currentMonthDirectoryName . "/";
            $path                       = $destinationPath . "/" . $new_filename;

            $uploadSuccess              = $file->move($destinationPath, $new_filename);





            $thumbnail_image_width      = 120;
            $thumbnail_image_height     = 184;

            if(file_exists($destinationPath . "/" .$existedupload)){
                  unlink($destinationPath . "/" .$existedupload);
              }

            $image_resize               = $this->ResizeImages($thumbnail_image_width, $thumbnail_image_height, $path, $clean_filename, $file_extension, $destinationPath2);
            list($width, $height)       = getimagesize($path);
                $dimension              = $width." x ".$height;
               

                $data_insert = array('path' => $path,'title' => $clean_filename, 'alt_text' => $clean_filename, 'uploaded_by' =>  Auth::id(),
                    'image_dimension' => $dimension , 'size' => $file_size , 'uploaded_on' => date('Y-m-d'),'type' => $file_extension);

                DB::connection($this->connection)->table('media')->insert($data_insert);
                $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
                $data_array[]  =   Media::with('user')->where('id', '=', $lastInsertedId)->first();
        }
        return $data_array;
     }
    
    
    public function saveMediaImagesForProduct($input){

        $parent_id = $input['parent_id'];
        $image_ids = $input['image_detail'];

                $return_ids = array();
                foreach($image_ids as $img_id){

                  $existingIdCount =  DB::connection($this->connection)->table('media_products')->where('media_id','=' ,$img_id)->where('product_id','=', $parent_id)->count();
                    if($existingIdCount == 0) {
                        $new_featured_detail = DB::connection($this->connection)->table('media')->where('id', $img_id)->first();

                        $new_featured_detail->path;
                        $path_parts = pathinfo($new_featured_detail->path);
                        $filedestination = $path_parts['dirname'];
                        $filename = basename($new_featured_detail->path);
                        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
                        $filename_without_extension = basename($new_featured_detail->path, "." . $file_ext);
                        $newfilename_without_extension = $filename_without_extension;
                        $newfilename = $newfilename_without_extension . "." . $file_ext;
                        $destinationPath2 = $filedestination . "/";
                        $pathForResize = $filedestination . "/" . $filename_without_extension;
                        $newImagePath = $filedestination . "/" . $newfilename;

                        if ($file_ext != 'png') {
                            $thumbnails =Config::get('domain-thumbnails');
                            $connection =  $this->connection;

                            foreach($thumbnails[$connection] as $connection_thumbnail) {
                                $width = $connection_thumbnail['width'];
                                $height = $connection_thumbnail['height'];

                                $image_resize = $this->ResizeImages($width, $height, $newImagePath, $newfilename_without_extension, $file_ext, $destinationPath2);
                            }

                        }
                        $dataProduct = array('media_id' => $img_id, 'product_id' => $parent_id);
                        DB::connection($this->connection)->table('media_products')->insert($dataProduct);


                        $image_detail_by_id = DB::connection($this->connection)->table('media')->where('id', '=', $img_id)->first();
                        $return_ids[] = $image_detail_by_id;
                    }

                 }

        return $return_ids;
    }
    
    

    public function ResizeImages($width , $height, $path, $file_name, $file_extension,$destinationPath2)
    {
        $resize_file_name = $file_name."-".$width."x".$height.".".$file_extension;
       return $img_resize = Image::make($path)->resize($width, $height)->save($destinationPath2.$resize_file_name);
    }
    public function ResizeImagesThumbnail($width , $height, $path, $file_name, $file_extension,$destinationPath2)
    {
        $resize_file_name = $file_name."-120x184".".".$file_extension;
        return $img_resize = Image::make($path)->resize($width, $height)->save($destinationPath2.$resize_file_name);
    }

    public function removeGalleryImages($input){

        $remove_id = $input['img_id'];
        $parent_id = $input['parent_id'];
        DB::connection($this->connection)->table('media_products')->where('product_id', $parent_id)->where('media_id', $remove_id)->delete();
        return $remove_id;
        
    }

    public function saveImageTitleForJs($array){

        $id = $array['image_id'];
        $title = $array['title'];
        $alt_text = $array['alt_text'];
        Media::where('id' , $id)->update(['alt_text' => $alt_text ,'title' => $title ]);
       
       $new_alt_text = Media::where('id' , $id)->pluck('alt_text');
        $new_title = Media::where('id' , $id)->pluck('title');
        return ['new_alt_text'=> $new_alt_text, 'new_title' => $new_title];


    }

    public function  deleteImageFromDb($input){

        $image_id = $input['image_id'];
        $image =  DB::connection($this->connection)->table('media')->where('id', $image_id)->first();
        $path =   $image->path;
        $connection =  $this->connection;
        $thumbnails =Config::get('domain-thumbnails');

       foreach($thumbnails[$connection] as $connection_thumbnail){
            $width = $connection_thumbnail['width'];
            $height = $connection_thumbnail['height'];
            $del_image =   $this->deleteResizeImages($path,$width, $height);

        }
        DB::connection($this->connection)->table('media_products')->where('media_id', $image_id)->delete();
        DB::connection($this->connection)->table('media_posts')->where('media_id', $image_id)->delete();
        return  DB::connection($this->connection)->table('media')->where('id', $image_id)->delete();
      
 }
    public function deleteResizeImages($path,$width, $height){



        $filename = basename($path);
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename_without_extension = basename($path, "." . $file_ext);
        $resize_file_name = $filename_without_extension."-".$width."x".$height.".".$file_ext;
        $directory = pathinfo($path ,PATHINFO_DIRNAME);
        $resizeImagePath = $directory."/".$resize_file_name;
        //unlink($path);
        if(file_exists($resizeImagePath)){
         return   unlink($resizeImagePath);
        }
     }
    public function clean_imageTitle($string){
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        $string = preg_replace('/[^A-Za-z0-9.\-]/', '', $string); // Removes special chars.
        //$string = preg_replace('/[^a-zA-Z0-9_.]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

    }

    public function  formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = round(number_format($bytes / 1073741824, 2)) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = round(number_format($bytes / 1048576, 2)) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = round(number_format($bytes / 1024, 2)) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}

    function galleryThumb($url = false)
    {
        if (!$url) {
            return false;
        }

        $admin_thumbnails       =   Config::get('adminpanel-thumbnails');
        $listing_thumb_size     =    $admin_thumbnails[Session::get('connection')]['add-edit-gallery'];
        $img_path = $url;
        $filename = basename($img_path);
        $img_ext = pathinfo($img_path, PATHINFO_EXTENSION);
        $img_path = str_replace($img_ext, '', $img_path);
        $img_path = rtrim($img_path, '.');
        $img_path = $img_path.'-'.$listing_thumb_size.'.'.$img_ext;

        if (file_exists($img_path)) {
            return $img_path;
        }else{
            return false;
        }
    }

}


?>