<?php

namespace App\Http\Controllers;
use App\Functions\Functions;
use App\User;


use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator; 
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\PostCategories;
use App\PostTags;
use App\Posts;
use App\SeoData;
use App\Media;

use Session;
use DB;


class PostsController extends BaseController
{
    private $attributes;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        // check if user has products capability
        if (User::userCan('products') === false) {
            abort(403, 'Unauthorized action.');
        }
        $this->post = new Posts;
        $this->postcategory = new PostCategories;
        $this->posttags =     new  PostTags;
        $this->postmedia =     new  Media;
        $this->seo       =     new  SeoData;
    }
    public function index()
    {
        $args = array();
        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!='')
        {
            $args['keywords']['column'] 	= 'title';
            $args['keywords']['operator']	= 'like';
            $args['keywords']['value']	= "%".str_replace('+','',$_REQUEST['keywords'])."%";
        }

        if(isset($_REQUEST['status']) && $_REQUEST['status']!='')
        {
            $status = $_REQUEST['status'];
            $args['post_status']['column'] 		= 'status';
            $args['post_status']['operator']		= '=';
            $args['post_status']['value']		= $status;
        }

        if(!empty($args)) {
            $posts = $this->post->getallpostsSearch($args);
        }
        else{
        $posts = $this->post->getallposts();
        }
        $post_counts = $this->post->getPostCounts();
        $data = array('posts'   => $posts );
        return view('posts.posts' , ['data' => $data , 'postCount' => $post_counts]);

    }
    public function create()
    {
         $post_categories = $this->postcategory->getPostCategories();
         $post_tags = $this->posttags->AllPostTags();

        return view('posts.newpost', [
            'categories' 	=> $post_categories,
            'tags'			=> $post_tags
        ]);
    }
    public function edit($id)
    {
        $id_exists = Functions::checkIdInDB($id ,$this->post->table);
        if($id_exists > 0) {
            $post_categories = $this->postcategory->getSelectedPostCategories($parentId = 0, $level = 0, $id);
            $post_tags = $this->posttags->AllPostTags();
            $postsById = $this->post->getPostById($id);
           
            return view('posts.editpost', [
                'categories' => $post_categories,
                'tags' => $post_tags,
                'postByid' => $postsById

            ]);
        }
        else{
            abort(404, 'Not Found.');
        }
    }
    public function destroy($id)
    {
        if($this->post->moveToTrash($id))
        {
            Session::flash('flash_message', 'Post Successfully Move to Trash!');
            return redirect('/posts');
        }else{
            Session::flash('flash_message', 'Error: Post Not Moved to Trash!');
            return redirect('/posts');
        }
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['remove' => 'required'], ['remove.required' => 'Select atleast one action to apply!']);
        $this->validate($request, ['del' => 'required'], ['del.required' => 'Select atleast one post to remove!']);
        $input = $request->all();
        $delete_post = $this->post->DeletePost($input);
        Session::flash('flash_message', 'Post Successfully Removed!');
        return redirect('posts');
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $input = $request->all();
        $add_inserted_id = $this->post->AddnewPost($input);
        $save_seo        = $this->seo->saveSeo($input, $add_inserted_id, 'post');
        Session::flash('flash_message', 'Post Successfully Created!');
        return redirect()->action('PostsController@edit',['id' => $add_inserted_id]);
    }
    public function savePostTag(){

        $result = $this->post->saveTagbyJS($_POST);
        return $result;
    }
    
    Public function savePostSlug(Request $request){
        $input = $request->all();
        $result = $this->post->savePostSlugForJs($input);
        return $result;
    }
    
    Public function updatePostSlug(Request $request){
        $input = $request->all();
        $result = $this->post->savePostSlugForJs($input);
        return $result;
    }
    
    public function savePostSlugById(){
        
        $result = $this->post->savePostSlugByIdForJs($_POST);
        return $result;   
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();/*
        echo "<pre>"; print_r($input);exit;*/
        $updatepost = $this->post->UpdatePostById($input ,$id);
        $save_seo        = $this->seo->saveSeo($input, $id, 'post');
        Session::flash('flash_message', 'Post Successfully Updated!');
        return redirect()->action('PostsController@edit', ['id' =>  $id]);
    }

    public function savePostdraft(){

       return $result = $this->post->AddnewPost($_POST);
    }
    
    public function updatePostdraft(){
        
       return $updatepost = $this->post->UpdatePostById($_POST ,$_POST['post_id']);
    }

}