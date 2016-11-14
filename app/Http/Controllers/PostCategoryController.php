<?php

namespace App\Http\Controllers;
use App\Functions\Functions;
use App\User;
use App\Products;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Posts;
use App\PostCategories;
use Session;
use App\SeoData;
use DB;

class PostCategoryController extends BaseController
{

    private $postcategory;

    public function __construct()
    {
        parent::__construct();
        // check if user has products capability
        if (User::userCan('products') === false) {
            abort(403, 'Unauthorized action.');
        }
        $this->postcategory = new PostCategories;
        $this->seo	= new SeoData;


    }

    public function index()
    {
        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!='') {
            $all_categoriessearch = $this->postcategory->searchcat( $_REQUEST['keywords']);
            $all_categories = $this->postcategory->getPostCategories();

            return view('posts.categories.postcategorysearchlist', ['data' => $all_categoriessearch,'categories' => $all_categories  ]);
        }else {
            $all_categories = $this->postcategory->getPostCategories();
            return view('posts.categories.postcategorylist', ['categories' => $all_categories ]);
        }

    }

    public function show(){

        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!='') {
            $all_categoriessearch = $this->postcategory->searchcat( $_REQUEST['keywords']);
            $all_categories = $this->postcategory->getPostCategories();
            return view('posts.categories.postcategorysearchlist', ['data' => $all_categoriessearch,'categories' => $all_categories  ]);
        }else {
            $all_categories = $this->postcategory->getPostCategories();
            return view('posts.categories.postcategorylist', ['categories' => $all_categories ]);
        }

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required',
            'slug'   => 'required',
        ]);
        $input = $request->all();
        $postCategory_id =  $this->postcategory->fSavecategory($input);
        $save_seo  = $this->seo->saveSeo($input, $postCategory_id, 'post-category');
        Session::flash('flash_message', 'Post Category successfully created!');
        return redirect('PostCategories');

    }

    public function edit($id)
    {
        $id_exists = Functions::checkIdInDB($id ,$this->postcategory->table);
        if($id_exists > 0){
        $all_categories  = $this->postcategory->getCategoryNotId($parentId = 0, $level=0,$id);
        $postCategory = $this->postcategory->getCategoryById($id);
        $data = array('category' => $postCategory, 'allcategory' => $all_categories);
        return view('posts.categories.editpostcategory' , ['data' => $data]);
        }
        else{
            abort(404, 'Category Not Found.');
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $postCategoryupdate =  $this->postcategory->fUpdateCategory($id, $input);
        $save_seo  = $this->seo->saveSeo($input, $id, 'post-category');
        $all_categories  = $this->postcategory->getCategoryNotId($parentId = 0, $level=0,$id);
        $ct = $this->postcategory->getCategoryById($id);
        $data = array('category' => $ct, 'allcategory' => $all_categories);
        Session::flash('flash_message', 'Post Category successfully Updated!');
        return redirect('PostCategories');
    }

    public function delete(Request $request)
    {
        $input = $request->all();
        $this->postcategory->DeleteCategories($input);
        Session::flash('flash_message', 'Post Category Removed!');
        return redirect('PostCategories');
        
    }
    

}