<?php

namespace App;

use App\Functions\Functions;
use App\Input;
use App\Pages;
use App\Posts;
use App\Categories;

use DB;
use Auth;
class MenuDetail extends BaseModel
{

    public $timestamps = false;
    public $table = "menu_details";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function parent_menu()
    {
        return $this->belongsTo('App\MenuDetail', 'parent_id' ,'id');
    }

    public function child_menu()
    {
        return $this->hasMany('App\MenuDetail', 'parent_id' , 'id');
    }

    public function getMenuDetail($menu_id){
        // echo $menu_id;

        return $all_menu_detail =   MenuDetail::with('child_menu')->where('menu_id' , $menu_id)->orderBy('id', 'asc')->get();

    }

}