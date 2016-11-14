<?php

namespace App;

use App\Functions\Functions; 
use DB;
use Auth;
use App\PostTags;
use App\PostCategories;
use App\Media;
use App\SeoData;
class Posts extends BaseModel
{

    public $timestamps = false;
    public $table = "posts";


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function posttags(){
        return $this->belongsToMany('App\PostTags', 'posts_posttags', 'posts_id', 'posts_posttag_id');
    }

    public function postcategories(){
        return $this->belongsToMany('App\PostCategories', 'posts_postcategories', 'posts_id' ,'postcategory_id');
    }

    public function media(){
        return $this->belongsToMany('App\Media', 'media_posts', 'post_id' ,'media_id');
    }
    public function media_featured_image(){
        return $this->hasone('App\Media', 'id' ,'featured_image_id');
    }
    
    public function user(){
        return $this->belongsTo('App\User');
    }

    public  function seo_details()
    {
        return $this->hasone('App\SeoData', 'page_id' ,'id');
    }

    public function getallposts(){
        return Posts::with('PostTags')
            ->with('PostCategories')
            ->with('User')
            ->where('type' , '=' , 'post')
            ->orderBy('id', 'desc')
            ->paginate(60);
    }

    public function getallpostsSearch($args){
        // DB::connection()->enableQueryLog();
        $query = Posts::with('PostTags')
            ->with('PostCategories')
            ->with('User')->where('type' , '=' , 'post')
            ->where(function ($query) use ($args){
                foreach($args as $arg){
                    $query->Where(
                        'posts.'.$arg['column'] ,
                        $arg['operator'] ,
                        $arg['value']);
                }
         })->orderBy('id', 'desc');
       return $query->paginate(60);
        //$query = DB::getQueryLog($query);
    }

    public function getPostCounts(){
        $counts = array('all' => 0, 'published' => 0, 'draft' => 0, 'deleted' => 0);

        $all = Posts::where('type' , '=' , 'post')->count();
        $counts['all'] = $all ;

        $query = Posts::query('');
        $query->where('posts.status', '=', 'publish')->where('type' , '=' , 'post');
        $counts['published'] = $query->count();

        $query = Posts::query('');
        $query->where('posts.status', '=', 'draft')->where('type' , '=' , 'post');
        $counts['draft'] = $query->count();

        $query = Posts::query('');
        $query->where('posts.status', '=', 'deleted')->where('type' , '=' , 'post');
        $counts['deleted'] = $query->count();

        return $counts;
    }

    public function getauthorName($id){
        $name = User::where('id', $id)->pluck('username');
        return $name[0];
    }

    public function AddnewPost($input){

        $slug =	trim($input['slug']);
        $type = 'post';
        $id   = trim($input['post_id']);
        $slug =	Functions::makeSlug($slug, $duplicates_count = 0 ,$this->table, $id = '', $type);
        $name = trim($input['name']);
        $desc = trim($input['post-editor']);

        if(isset($input['action']) && $input['action']  == 'draft'){

            $status = 'draft';
        }
        elseif(isset($input['action']) && $input['action']  == 'publish'){
            $status =  trim($input['status']);
        }
        $status =  trim($input['status']);
        $visible =  trim($input['visible']);
        $published_at = $input['yy'].'-'.$input['mm'].'-'.$input['dd'].' '.$input['hr'].':'.$input['min'].':00';
        
        $data = array(
            'title' 	    => $name,
            'slug' 			=> $slug,
            'author'        => Auth::id(),
            'description'   => $desc,
            'type'          => 'post',
            'status'        => $status,
            'visibilty'     => $visible,
            'published_at'  => $published_at

        );
        Posts::insert($data);
        $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();

        if(isset($input['categories'])){
              Posts::find($lastInsertedId)->postcategories()->sync($input['categories']);
        }
        else{
            DB::connection($this->connection)->table('posts_postcategories')->where('posts_id', '=',$id)->delete();
        }
        if(isset($input['tags'])) {
              Posts::find($lastInsertedId)->posttags()->sync($input['tags']);
        }
        else{
            DB::connection($this->connection)->table('posts_posttags')->where('posts_id', '=',$id)->delete();
        }
        return  $lastInsertedId;
    }

    public function UpdatePostById($input , $id){

        $type = 'post';
        $slug =	trim($input['slug']);
        $slug =	Functions::makeSlug($slug , $duplicates_count = 0 ,$this->table, $id ,$type);
        $name = trim($input['name']);
        $desc = trim($input['post-editor']);
      
    if(isset($input['action']) && $input['action']  == 'draft'){

        $status = 'draft';
    }
    else if(isset($input['action']) && $input['action']  == 'trash'){
        $status = 'deleted';
    }
    else{
        $status =  trim($input['status']);
    }
        
    $visible =  trim($input['visible']);
    $published_at = $input['yy'].'-'.$input['mm'].'-'.$input['dd'].' '.$input['hr'].':'.$input['min'].':00';

    $dataUpdate = array(
        'title' 	    => $name,
        'slug' 			=> $slug,
        'author'        => Auth::id(),
        'description'   => $desc,
        'type'          => 'post',
        'status'        =>$status,
        'visibilty'     =>$visible,
        'published_at'  =>$published_at

    );
    Posts::where('id', $id)->update($dataUpdate);

        if(isset($input['categories'])){

        Posts::find($id)->postcategories()->sync($input['categories']);
        }
        else{
        DB::connection($this->connection)->table('posts_postcategories')->where('posts_id', '=',$id)->delete();
        }

        if(isset($input['tags'])) {
         Posts::find($id)->posttags()->sync($input['tags']);
         }
        else{
         DB::connection($this->connection)->table('posts_posttags')->where('posts_id', '=',$id)->delete();
        }
     }

    public function DeletePost($input){
        if(isset($input['remove'])  && $input['remove'] == 'rm'){

            foreach($input['del'] as $key => $del){
                Posts::where('id', '=',$del)->update(['status' => 'deleted']);
                DB::connection($this->connection)->table('posts_posttags')->where('posts_id', '=',$del)->delete();
                DB::connection($this->connection)->table('posts_postcategories')->where('posts_id', '=',$del)->delete();
            }

         }

    }
    public function moveToTrash($post_id)
    {
        if($post_id)
        {
            Posts::where('id', '=',$del)->update(['status' => 'deleted']);
            DB::connection($this->connection)->table('posts_posttags')->where('posts_id', '=',$del)->delete();
            DB::connection($this->connection)->table('posts_postcategories')->where('posts_id', '=',$del)->delete();
        }
    }

    public function saveTagbyJS($array){

        $tagname = $array['name'];
        $slug    = 	Functions::makeSlug($array['name'], $duplicates_count = 0 ,$this->table, $id ='' , $type='');
        $data = array('name' => $tagname,'slug' =>  $slug );
        PostTags::insert($data);
        return $lastInsertedId = DB::getPdo()->lastInsertId();

    }

    public function savePostSlugForJs($array){
        $type = 'post';
        $id = $array['post_id'];
        $slug = $array['title'];
        $table = 'posts';
        $slug    = Functions::makeSlug($slug, $duplicates_count = 0 , $table , $id, $type);
        return $slug;

     }
    public function getPostById($id){
        return posts::with('posttags')->with('media')
                                      ->with('media_featured_image')
                                      ->with(['seo_details' => function($query) {
                                              $query->where('seo_data.type', 'post');}])
                                      ->where('id', $id)
                                        ->where('id', $id)
                                      ->first();

    }
    public function getPostCategoriesById($id){
       return DB::connection($this->connection)->table('posts_postcategories')->where('posts_id', $id)->pluck('postcategory_id');
        
    }

}

?>