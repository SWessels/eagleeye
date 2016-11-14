<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Banners;
use App\Categories;
use App\Pages;
class BannersController extends BaseController
{


    protected $connection;

    public function __construct(Request $request)
    {
        parent::__construct();
        // check if user has products capability
        if (User::userCan('top_banners') === false) {
            abort(403, 'Unauthorized action.');
        }

        $this->banner = new Banners;
        $this->categories = new Categories;
        $this->page = new Pages;
        $this->connection = Session::get('connection');
    }

    public function showMobileBanners(){
        

        $data = $this->banner->getAllBanners();
        $prd_cat =  $this->categories->getCategoriesDB();
        $pages=  $this->page->getPages();
         return view('banners.mobile_banners',  ['categories' => $prd_cat,'pages' => $pages ,'data' => $data  , 'type'=>'mobile-product-category']);
       
    }
    public function showDesktopBanners(){


        $data = $this->banner->getAllBanners();
        $prd_cat =  $this->categories->getCategoriesDB();
        $pages=  $this->page->getPages();
        return view('banners.desktop_banners',  ['categories' => $prd_cat,'pages' => $pages ,'data' => $data  , 'type'=>'desktop-product-category']);

    }
    public function addMobileCategoryBanners(Request $request){


         $input = $request->all();
        $result = $this->banner->saveMobCatBanners($input);
        $pages=  $this->page->getPages();
        $data = $this->banner->getAllBanners();
        $prd_cat =  $this->categories->getCategoriesDB();
        Session::flash('flash_message', 'Banners saved successfully!');
        return view('banners.mobile_banners',  ['categories' => $prd_cat,'pages' => $pages ,'data' => $data  , 'type'=>'mobile-product-category']);

    }
    public function addDesktopCategoryBanners(Request $request){


        $input = $request->all();
        $result = $this->banner->saveDesCatBanners($input);
        $pages=  $this->page->getPages();
        $data = $this->banner->getAllBanners();
        $prd_cat =  $this->categories->getCategoriesDB();
        Session::flash('flash_message', 'Banners saved successfully!');
        return view('banners.desktop_banners',  ['categories' => $prd_cat,'pages' => $pages ,'data' => $data  , 'type'=>'desktop-product-category']);

    }
    

    public function addMobilePageBanners (Request $request)
    {

        $input = $request->all();
        $result = $this->banner->saveMobPageBanners($input);
        $data = $this->banner->getAllBanners();
        $pages=  $this->page->getPages();
        $prd_cat = $this->categories->getCategoriesDB();
        Session::flash('flash_message', 'Banners saved successfully!');
        return view('banners.mobile_banners', ['categories' => $prd_cat,'pages' => $pages , 'data' => $data, 'type' => 'mobile-pages']);
    }
    public function addDesktopPageBanners (Request $request)
    {

        $input = $request->all();
        $result = $this->banner->saveDesPageBanners($input);
        $data = $this->banner->getAllBanners();
        $pages=  $this->page->getPages();
        $prd_cat = $this->categories->getCategoriesDB();
        Session::flash('flash_message', 'Banners saved successfully!');
        return view('banners.desktop_banners', ['categories' => $prd_cat,'pages' => $pages , 'data' => $data, 'type' => 'desktop-pages']);
    }
    
}
