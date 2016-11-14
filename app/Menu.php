<?php

namespace App;

use App\Functions\Functions;
use App\Input;
use App\Pages;
use App\Posts;
use App\Categories;
use App\PostCategories;
use DB;
use Auth;
class Menu extends BaseModel
{

    public $timestamps = false;
    public $table = "menu";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getAllPages(){

        $all_pages =   Pages::where('type' , '=' , 'page')->get();
        if(count($all_pages ) > 0){
        foreach( $all_pages  as $page)
        {

            $pages[] = [
                'id' 		=> $page->id,
                'title' 	=> $page->title,
                'slug'      => $page->slug];
        }
        return $pages;
       }

    }
   public function getMenu($menu_id){
     return $selected_menu = Menu::where('id', $menu_id)->first();
       
   }


    public function add_menu($input){

        $title = $input['name'];
        Menu::insert(['title' => $title]);
        return $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
    }
    public function getAllMenu(){
       return $all_menu =   Menu::get();
        
    }

    public function saveMenu($input){

        $menu_id = $input['menu_id'];
        $menu_type = $input['is_sticky'];
        $menu_is_primary = $input['is_primary'];
        $menu_array = $input['menu_array'];

        //echo "<pre>"; print_r($menu_array); echo "</pre>";
        DB::connection($this->connection)->beginTransaction();

        try {
            Menu::where('is_primary', 'yes')->update(['is_primary' => 'no']);
            Menu::where('id', $menu_id)->update(['menu_type' => $menu_type, 'is_primary' => $menu_is_primary]);
            DB::connection($this->connection)->table('menu_details')->where('menu_id', '=',$menu_id)->delete();
            foreach($menu_array as $key => $main_val){
                $this->saveAllmenu($main_val , $parent_id = 0 ,$menu_id );

            }
            DB::connection($this->connection)->commit();
        }

        catch (\Exception $e) {


            DB::connection($this->connection)->rollback();
           // echo $e->getMessage();
            echo $e->getCode();
            return $e->getCode();

       }
    }

    public function  saveAllmenu($main_val, $parent_id ,$menu_id){

        $title = $main_val['title'];
        $type =  $main_val['type'];
        $sub_menu_id = $main_val['id'];


        if(isset($main_val['url'])){
            $url = $main_val['url'];
        }
        else{ $url = '';}


        $data_insert = array('menu_id' => $menu_id,'sub_menu_id' => $sub_menu_id
        , 'parent_id' => $parent_id , 'type' => $type, 'title'=> $title, 'url' => $url);
        DB::connection($this->connection)->table('menu_details')->insert($data_insert);
         $lastInsertedMenuId = DB::connection($this->connection)->getPdo()->lastInsertId();
         if (array_key_exists('children', $main_val)) {
              foreach($main_val['children'] as $val){
            $this->saveAllmenu($val, $lastInsertedMenuId ,$menu_id);}

         }
   }
    public function search_page($keyword){
        $keyword = "%".str_replace('+','',$keyword)."%";

        return $pages = Pages::where('title', 'like',  $keyword )->where('type', '=' , 'page')->get();
    }

    public function search_postCats($keyword){
        $keyword = "%".str_replace('+','',$keyword)."%";

        return $posts = PostCategories::where('name', 'like',  $keyword )->where('status', '=' , 'publish')->get();
    }

    public function search_category($keyword){
        $keyword = "%".str_replace('+','',$keyword)."%";

        return $categories = Categories::where('name', 'like',  $keyword )->get();
    }
    public function getMenuDetail($menu_id){
        // echo $menu_id;

        return $all_menu_detail = DB::connection($this->connection)->table('menu_details')->where('menu_id' , $menu_id)->orderBy('id', 'asc')->get();

    }

    public function displayMenuTree($m_detail){
       // print_r($m_detail);
       echo  "<li class='dd-item dd3-item'  data-id='".$m_detail['sub_menu_id']."' data-type ='{$m_detail['type']}' data-title ='{$m_detail['title']}'  data-url ='{$m_detail['url']}'>
              <div class='dd-handle dd3-handle'  style='height: 41px'>Drag</div>
              <div class='portlet box default'  style='width:500px' >
              <div class='portlet-title'>
              <div class='caption' style='padding-left:29px'>
               </i>";
               if($m_detail['type'] == 'page'){
                   echo "<div class='new_pageTitle'>{$m_detail['title']}</div>";
               }
        if($m_detail['type'] == 'post'){
            echo "<div class='new_postTitle'>{$m_detail['title']}</div>";
        }
        if($m_detail['type'] == 'category'){
            echo "<div class='new_categoryTitle'>{$m_detail['title']}</div>";
        }
        if($m_detail['type'] == 'custom'){
            echo "<div class='new_customTitle'>{$m_detail['title']}</div>";
        }


              echo  "</div><div class='tools'>{$m_detail['type']}
              <a href='javascript:;' class='expand' data-original-title='' title=''> </a>
               </div>
               </div>
               <div class='portlet-body  collapse'>";
        if($m_detail['type'] == 'page'){

            echo "<div class=\"row\">Navigation Label<input data-menu_id =\"{$m_detail['menu_id']}\" value=\"{$m_detail['title']}\" type=\"text\" class=\"form-control edit_pageTitle\"  name=\"edit_page\" id=\"{$m_detail['sub_menu_id']}\">
                   </div>
                   <div class=\"row\">
                  <a data-menu_id =\"{$m_detail['menu_id']}\" id=\"{$m_detail['sub_menu_id']}\" class=\"remove_menu\" href=\"#\">Remove</a> | <a id=\"{$m_detail['sub_menu_id']}\" href=\"#\"  class=\"collapse_cancel\"> Cancel</a> </div>";
         }

        else if($m_detail['type'] == 'post'){
            echo " <div class=\"row\">Navigation Label<input data-menu_id =\"{$m_detail['menu_id']}\" value=\"{$m_detail['title']}\" type=\"text\" class=\"form-control edit_postTitle\"  name=\"edit_page\" id=\"{$m_detail['sub_menu_id']}\">
                   </div>
                    <div class=\"row\">
                    <a data-menu_id =\"{$m_detail['menu_id']}\" id=\"{$m_detail['sub_menu_id']}\" class=\"remove_menu\" href=\"#\">Remove</a> | <a id=\"{$m_detail['sub_menu_id']}\" href=\"#\"  class=\"collapse_cancel\"> Cancel</a> </div>
                    ";
        }
        else if($m_detail['type'] == 'category'){
            echo " <div class=\"row\">Navigation Label<input data-menu_id =\"{$m_detail['menu_id']}\" value=\"{$m_detail['title']}\" type=\"text\" class=\"form-control edit_catTitle\"  name=\"edit_page\" id=\"{$m_detail['sub_menu_id']}\">
                   </div>
                    <div class=\"row\">
                  <a data-menu_id =\"{$m_detail['menu_id']}\" id=\"{$m_detail['sub_menu_id']}\" class=\"remove_menu\" href=\"#\">Remove</a> | <a id=\"{$m_detail['sub_menu_id']}\" href=\"#\"  class=\"collapse_cancel\"> Cancel</a> </div>
                   ";
        }
        else if($m_detail['type'] == 'custom'){
           echo "<div class=\"row\">URL<input name=\"edit_URL\" class=\"form-control edit_customURL\" value=\"{$m_detail['url']}\" type=\"text\" ></div>
                 <div class=\"row\">Navigation Label<input data-menu_id =\"{$m_detail['menu_id']}\" value=\"{$m_detail['title']}\" type=\"text\" class=\"form-control edit_customTitle\"  name=\"edit_page\" id=\"{$m_detail['sub_menu_id']}\">
                 </div>
                 <div class=\"row\">
                 <a data-menu_id =\"{$m_detail['menu_id']}\" id=\"{$m_detail['sub_menu_id']}\" class=\"remove_menu\" href=\"#\">Remove</a> | <a id=\"{$m_detail['sub_menu_id']}\" href=\"#\"  class=\"collapse_cancel\"> Cancel</a> </div>
                  ";
        }


         echo      "</div></div>";
        if (array_key_exists('children', $m_detail)) {
            echo '<ol class="dd-list">';
                foreach($m_detail['children'] as $mdetail_child){
                    $this->displayMenuTree($mdetail_child);
                }
            echo "</li></ol>";
            }
    }
    
    public function menu_delete($menu_id){

        DB::connection($this->connection)->table('menu_details')->where('menu_id', $menu_id)->delete();
        Menu::where('id', $menu_id)->delete();

       }




}