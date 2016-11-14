<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Sliders;
class SlidersController extends BaseController
{


    protected $connection;

    public function __construct(Request $request)
    {
        parent::__construct();
        // check if user has products capability
        if (User::userCan('display') === false) {
            abort(403, 'Unauthorized action.');
        }

        $this->slider = new Sliders;
        $this->connection = Session::get('connection');
    }


    public function index()
    {
        $data = $this->slider->getAllSliders();
        return view('sliders.sliders' ,  ['data' => $data , 'type'=>'mobile']);
    }
    public function addMobileSliders(Request $request){
        
        $input = $request->all();
     //   echo "<pre>"; print_r($input);echo "</pre>";
        $result = $this->slider->saveMobSliders($input);
        $data = $this->slider->getAllSliders();
      //  dump($data);
        Session::flash('flash_message', ' Mobile silders saved successfully!');
        return view('sliders.sliders' ,  ['data' => $data , 'type'=>'mobile']);
       
    }

    public function addMobileHomeSliders(Request $request){
       
        $input = $request->all();
        $result = $this->slider->saveMobHomeSliders($input);
        $data = $this->slider->getAllSliders();
        Session::flash('flash_message', 'Mobile homepage silders saved successfully!');
        return view('sliders.sliders' ,  ['data' => $data, 'type'=>'mobile_homepage']);
        //return redirect('sliders');
    }
    
    public function addDesktopSliders(Request $request){
        
        $input = $request->all();
        $result = $this->slider->saveDesSliders($input);
        $data = $this->slider->getAllSliders();
        Session::flash('flash_message', 'Desktop silders saved successfully!');
        return view('sliders.sliders' ,  ['data' => $data, 'type'=>'desktop' ]);

    }
    public function addDesktopHomeSliders(Request $request){
        
        $input = $request->all();
        $result = $this->slider->saveDesHomeSliders($input);
        $data = $this->slider->getAllSliders();
        Session::flash('flash_message', 'Desktop homepage silders saved successfully!');
        return view('sliders.sliders' ,  ['data' => $data, 'type'=>'desktop_homepage' ]);

    }
    public function addProductSliders(Request $request){

        $input = $request->all();
        // echo "<pre>"; print_r($input);

        $result = $this->slider->saveProductSliders($input);
        $data = $this->slider->getAllSliders();
        Session::flash('flash_message', 'Product silders saved successfully!');
        return view('sliders.sliders' ,  ['data' => $data, 'type'=>'product' ]);

    }
    public function addCategorySidebarSliders(Request $request){

        $input = $request->all();
        // echo "<pre>"; print_r($input);

        $result = $this->slider->saveMusthave_deal_widget($input);
        $data = $this->slider->getAllSliders();
        Session::flash('flash_message', 'Musthave Deal Widget saved successfully!');
        return view('sliders.sliders' ,  ['data' => $data, 'type'=>'musthave_deal_widget' ]);

    }
    public function getProducts(Request $request){

        $input = $request->all();
        return  $get_products = $this->slider->get_AllProducts($input);
    }
    public function getFeaturedImageById(Request $request){

        $input = $request->all();
        return  $get_f = $this->slider->get_FeaturedImageById($input);
    }
}
