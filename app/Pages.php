<?php

namespace App;

use DB;
use Auth;
use App\functions\Functions;
use App\Posts;
class Pages extends BaseModel
{
    //

    public $timestamps = false;
    public $table = "posts";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public  function seo_details()
    {
        return $this->hasone('App\SeoData', 'page_id' ,'id');
    }

    public function getPages(){

        return Posts::where('type' , '=' , 'page')
            ->orderBy('id', 'desc')->get();
    }

    public function getallPages(){

        return Posts::where('type' , '=' , 'page')
            ->orderBy('id', 'desc')
            ->paginate(60);
    }

    public function getallPagesSearch($args){

       // DB::connection()->enableQueryLog();
        $query = Posts::where('type' , '=' , 'page')->where(function ($query) use ($args){
                foreach($args as $arg){
                    $query->Where(
                        'posts.'.$arg['column'] ,
                        $arg['operator'] ,
                        $arg['value']);}
                    })->orderBy('id', 'desc');
        return $query->paginate(60);
        //$query = DB::getQueryLog($query);
    }

    public function getPagesCounts()
    {
        $counts = array('all' => 0, 'published' => 0, 'draft' => 0, 'deleted' => 0);

        $all = Posts::where('type' , '=' , 'page')->count();
        $counts['all'] = $all ;

        $query = Posts::query('');
        $query->where('posts.status', '=', 'publish')->where('type' , '=' , 'page');
        $counts['published'] = $query->count();

        $query = Posts::query('');
        $query->where('posts.status', '=', 'draft')->where('type' , '=' , 'page');
        $counts['draft'] = $query->count();

        $query = Posts::query('');
        $query->where('posts.status', '=', 'deleted')->where('type' , '=' , 'page');
        $counts['deleted'] = $query->count();

        return $counts;

    }

    public function getauthorName($id){

        $name = DB::table('users')->where('id', $id)->pluck('username');
        return $name[0];
    }

    public function DeletePage($input){
        
        if(isset($input['remove'])  && $input['remove'] == 'rm'){
            foreach($input['del'] as $key => $del){
                Posts::where('id', '=',$del)->where('type' , '=' , 'page')->update(['status' => 'deleted']);

            }
        }
    }


    public function getPageSlugForJs($array){
        $slug = $array['title'];
        $type = 'page';
        $id = $array['page_id'];
        $slug    = Functions::makeSlug($slug, $duplicates_count = 0,$this->table, $id ,$type);
        return $slug;

    }
    public function updatePageSlugForJs($array){
        $slug = $array['title'];
        $type = 'page';
        $id = $array['page_id'];
        $slug    = Functions::makeSlug($slug, $duplicates_count = 0,$this->table, $id ,$type);
        return $slug;

    }

    public function makeSlug($title, $duplicates_count = 0 , $id, $type)
    {
        $duplicates_count = (int) $duplicates_count ;
        $slug = $title = str_slug($title);
        if($id == ''){
        if ($duplicates_count > 0) {
            $slug = $slug.'-'.$duplicates_count;
            $rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $slug)->where('type' , '=' , $type)->count();
            if ($rowCount > 0) {
                return $this->makeSlug($title, ++$duplicates_count, $id, $type);
            } else {
                return $slug;
            }
        } else {
            $rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $title)->where('type' , '=' , $type)->count();
            if ($rowCount > 0) {
                return $this->makeSlug($title, ++$duplicates_count, $id, $type);
            } else {
                return $title;
            }
        }
    }
        else{
            if ($duplicates_count > 0) {
                $slug = $slug.'-'.$duplicates_count;
                $rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $slug)->where('id','!=' , $id)
                    ->where('type' , '=' , $type)->count();
                if ($rowCount > 0) {
                    return $this->makeSlug($title, ++$duplicates_count,$id, $type);
                } else {
                    return $slug;
                }
            } else {
                $rowCount = DB::connection($this->connection)->table($this->getTable())->where('slug', $title)->where('id','!=' , $id)
                    ->where('type' , '=' , $type)->count();
                if ($rowCount > 0) {
                    return $this->makeSlug($title, ++$duplicates_count, $id, $type);
                } else {
                    return $title;
                }
            }

        }
    }
    public function AddnewPage($input){

        $slug =	trim($input['slug']);
        $id =	trim($input['page_id']);
        $type = 'page';
        $slug =	Functions::makeSlug($input['slug'],$duplicates_count = 0 ,$this->table, $id, $type);
        $name = trim($input['name']);
        $desc = trim($input['page-editor']);
        if(isset($input['action']) && $input['action']  == 'draft'){

            $status = 'draft';
        }
        else{
            $status =  trim($input['status']);
        }
        $visible =  trim($input['visible']);
        $published_at = $input['yy'].'-'.$input['mm'].'-'.$input['dd'].' '.$input['hr'].':'.$input['min'].':00';

        $data = array(
            'title' 	    => $name,
            'slug' 			=> $slug,
            'author'        => Auth::id(),
            'description'   => $desc,
            'type'          => 'page',
            'status'        => $status,
            'visibilty'     => $visible,
            'published_at'  => $published_at

        );
        Posts::insert($data);
        $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
        return  $lastInsertedId;

    }
    public function getPageById($id){
        return Posts::with(['seo_details' => function($query) {
                        $query->where('seo_data.type', 'page');}])
                        ->where('id', $id)
                        ->where('type', 'page')->first();

    }
    public function UpdatePageById($input , $id){

        $type = 'page';
        $slug =	Functions::makeSlug($input['slug'], $duplicates_count = 0,$this->table, $id ,$type);
        $name = trim($input['name']);
        $desc = trim($input['page-editor']);
        if(isset($input['action']) && $input['action']  == 'draft'){

            $status = 'draft';
        }
        else if(isset($input['action']) && $input['action']  == 'Move to Trash'){
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
            'type'          => 'page',
            'status'        =>$status,
            'visibilty'     =>$visible,
            'published_at'  =>$published_at

        );
        Posts::where('id', $id)->where('type' , '=' , $type)
            ->update($dataUpdate);
    }
}
?>