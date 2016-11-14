<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use App\Functions\Functions;
use App\Pages;
use Session;
use App\SeoData;
use DB;

class PagesController extends BaseController
{
    public function __construct()
    {

        $this->middleware('auth');
        parent::__construct();
        // check if user has employees capability
        if(User::userCan('pages') === false)
        {
            abort(403, 'Unauthorized action.');
        }
        $this->page = new Pages();
        $this->seo	= new SeoData;
    }

    public function dashBoard(){
        return view('dashboard');
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
            $pages = $this->page->getallPagesSearch($args);
        }
        else{
            $pages = $this->page->getallPages();
        }
        $page_counts = $this->page->getPagesCounts();

        $data = array('pages'   => $pages );

        return view('pages.pages' , ['data' => $data , 'pageCount' => $page_counts]);

    }

    public function create()
    {
        return view('pages.newpage');
    }

    public function delete(Request $request)
    {
        $this->validate($request, ['remove' => 'required'], ['remove.required' => 'Select atleast one action to apply!']);
        $this->validate($request, ['del' => 'required'], ['del.required' => 'Select atleast one page to remove!']);
        $input = $request->all();
        $delete_page = $this->page->DeletePage($input);
        Session::flash('flash_message', 'Page Successfully Removed!');
        return redirect('pages');
    }

    Public function getPageSlug(){

        $result = $this->page->getPageSlugForJs($_POST);
        return $result;
    }
    public function updatePageSlug(){

        $result = $this->page->updatePageSlugForJs($_POST);
        return $result;  
    }
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $input = $request->all();
        $add_inserted_id = $this->page->AddnewPage($input);
        $pageById  = $this->page->getPageById($add_inserted_id);
        $save_seo  = $this->seo->saveSeo($input, $add_inserted_id , 'page');
        Session::flash('flash_message', 'Page Successfully Created!');
        return redirect()->action('PagesController@edit', ['id' =>  $add_inserted_id]);
    }

    public function edit($id)
    {
        $id_exists = Functions::checkIdInDB($id ,$this->page->table);
        if($id_exists > 0) {
            $pageById = $this->page->getPageById($id);
            return view('pages.editpage', ['pageByid' => $pageById]);
        }else{
            abort(404, 'Not Found.');
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $updatepost = $this->page->UpdatePageById($input ,$id);
        $save_seo  = $this->seo->saveSeo($input, $id , 'page');
        Session::flash('flash_message', 'Page Successfully Updated!');
        return redirect()->action('PagesController@edit', ['id' =>  $id]);
    }
   
}