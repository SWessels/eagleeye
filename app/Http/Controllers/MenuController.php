<?php

namespace App\Http\Controllers;

use App\User;
use App\Menu;
use App\MenuDetail;
use App\Pages;
use App\Posts;
use App\Categories;
use App\PostCategories;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Html\HtmlServiceProvider;
use Illuminate\Html\FormFacade;
use Session;
use DB;




class MenuController extends BaseController
{
    private $menu;

    public function __construct()
    {
        parent::__construct();
        $this->menu = new Menu();
        $this->menuDetail = new MenuDetail();
        $this->post = new Posts();
        $this->page = new Pages();
        $this->categories = new Categories();
        $this->postcategory = new PostCategories;
    }

    public function index(){
        
        $postcategories = $this->postcategory->getPostCategories();
        $pages = $this->menu->getAllPages();
        $categories = $this->categories->getCategories();
        $all_menu = $this->menu->getAllMenu();
        $menu_id =  $all_menu[0]['id'];
        $selected_main = $this->menu->getMenu($menu_id);
        $menu_detail = $this->menu->getMenuDetail($menu_id);
        $menu_detail = (array)$menu_detail;
        $menu_detail = $this->buildTree($menu_detail,$pid = 0);
        $data = array('postscat' => $postcategories, 'pages'=> $pages, 'categories'=> $categories, 'all_menu' => $all_menu,'menu_detail' => $menu_detail,
            'menu_id'=> $menu_id, 'selected_menu'=> $selected_main);

        return view('menu.menu_detail'  ,['data'=> $data]);
    }

    public function create(){
        return view('menu.new_menu');
    }
    public function showDetail(request $request){
        $input = $request->all();
        $postcategories = $this->postcategory->getPostCategories();
        $pages = $this->menu->getAllPages();
        $categories = $this->categories->getCategories();
        $all_menu = $this->menu->getAllMenu();
        $menu_id = $input['mainmenu'];
        $selected_main = $this->menu->getMenu($menu_id);
        $menu_detail = $this->menu->getMenuDetail($menu_id);
        $menu_detail = (array)$menu_detail;
        $menu_detail = $this->buildTree($menu_detail,$pid = 0);
       // dd($menu_detail);
        $data = array('postscat' => $postcategories, 'pages'=> $pages, 'categories'=> $categories, 'all_menu' => $all_menu,'menu_detail' => $menu_detail,
            'menu_id'=> $menu_id, 'selected_menu'=> $selected_main);
        
        return view('menu.menu_detail', ['data'=> $data]);
        
    }
    public  function buildTree( $ar, $pid = 0 ) {
    $op = array();
    foreach( $ar as $item ) {
        if( $item->parent_id == $pid ) {
            $op[$item->id] = array(
                'id' => $item->id,
                'title' => $item->title,
                'menu_id' => $item->menu_id,
                'sub_menu_id' => $item->sub_menu_id,
                'type' => $item->type,
                'url' => $item->url,
                'parent_id' => $item->parent_id
            );
            // using recursion
            $children = $this->buildTree( $ar, $item->id );
            if( $children ) {
                $op[$item->id]['children'] = $children;
            }
        }
        }
        return $op;
}
    public function store(request $request ){

        $input = $request->all();
        $menu_id = $this->menu->add_menu($input);
        $all_menu = $this->menu->getAllMenu();
        $postcategories = $this->postcategory->getPostCategories();
        $pages = $this->menu->getAllPages();
        $categories = $this->categories->getCategories();
        $data = array('menu_id' => $menu_id, 'all_menu' => $all_menu,'postscat' => $postcategories, 'pages'=> $pages, 'categories'=> $categories);
        
        return view('menu.menu_detail' ,['data' => $data]);

   }

    public function saveMenu(request $request){
        $input = $request->all(); 
       $savemenu = $this->menu->saveMenu($input);
        
    }
    public function searchPage(request $request){
        $input = $request->all();
        $keyword = $input['keyword'];
        return $search_page = $this->menu->search_page($keyword);
    }
    
    public function searchPostCats(request $request){
        $input = $request->all();
        $keyword = $input['keyword'];
        return $search_post = $this->menu->search_postCats($keyword);
    }
    
    public function searchCategory(request $request){
        $input = $request->all();
        $keyword = $input['keyword'];
        return $search_category = $this->menu->search_category($keyword);
    }
    
    public function get_max_customId(request $request){
        $input = $request->all();
        $menu_id = $input['menu_id'];
        return $custom_id = $this->menu->get_customId($menu_id);
    }
    public function menuDelete(request $request){
        $input = $request->all();
        $menu_id = $input['menu_id'];
        return $menu_del = $this->menu->menu_delete($menu_id);
    }
    public function edit_pageTitle(request $request){
        $input = $request->all();
        return $page_title = $this->menu->edit_page_title($input);
    }
    public function edit_postTitle(request $request){
        $input = $request->all();
        return $post_title = $this->menu->edit_post_title($input);
    }
    public function edit_catTitle(request $request){
        $input = $request->all();
        return $cat_title = $this->menu->edit_cat_title($input);
    }

    public function edit_customTitle(request $request){
        $input = $request->all();
        return $custom_title = $this->menu->edit_custom_title($input);
    }

    public function add_pageToDB(request $request){
        $input = $request->all();
        return $return_id = $this->menu->add_page_to_db($input);

    }
}