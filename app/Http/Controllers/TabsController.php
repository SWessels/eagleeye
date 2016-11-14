<?php

namespace App\Http\Controllers;


use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Tabs;
use Session;
use App\Functions\Functions;

class TabsController extends BaseController
{
    private $tabs;
    private $products;
    public function __construct()
    {
        parent::__construct();
        // check if user has employees capability
        if(User::userCan('pages') === false)
        {
            abort(403, 'Unauthorized action.');
        }

        $this->tabs     = new Tabs();
        $this->products = new Products();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all tabs
        $tabs = $this->tabs->getAllTabs();
         
        return view('tabs.tabs' , ['data' => $tabs]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $products = $this->products->getAllProducts();

        return view('tabs.newtab', ['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        //
        $this->validate($request, ['title' => 'required']);
        $input = $request->all();


        if($this->tabs->AddNewTab($input))
        {
            Session::flash('flash_message', 'Tab Successfully Created!');
            return redirect()->action('TabsController@index');
        }else{

            Session::flash('error_message', 'Error saving tab!');
            return redirect()->action('TabsController@create');
        }

    }
 

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        
        $id_exists = Functions::checkIdinDB($id ,'tabs');
        if($id_exists > 0) {
            $tab = $this->tabs->getTabById($id);
            $products = $this->products->getAllProducts();
            return view('tabs.edittab', ['tab' => $tab, 'products' => $products]);
        }else{
            abort(404, 'Not Found.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();

        $this->tabs->UpdateTabById($input ,$id);
        Session::flash('flash_message', 'Tab Successfully Updated!');
        return redirect()->action('TabsController@edit', ['id' =>  $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $tab = Tabs::find($id);
        if($tab->forceDelete())
        {
            Session::flash('flash_message', 'Tab Successfully Deleted!');
            return redirect()->action('TabsController@index');
        }else{
            Session::flash('error_message', 'Error Deleting Tab.');
            return redirect()->action('TabsController@edit', ['id' =>  $id]);
        }
    }


    public function addCustomTab(Request $request)
    {
        $input = $request->input(); 
        if($tab_id = $this->tabs->addCustomTab($input))
        {
            return json_encode(array('action' => 'true', 'tab_id' => $tab_id));
            exit;
        }else{
            return json_encode(array('action' => 'false'));
            exit;
        }
    }

    public function updateCustomTabs(Request $request)
    {
        $input = $request->input();
         
        if($this->tabs->updateCustomTabById($input))
        {
            return json_encode(array('action' => 'true'));
            exit;
        }else{
            return json_encode(array('action' => 'false'));
            exit;
        }
    }

    public function updateDetailsTab(Request $request)
    {
        $input = $request->input(); 

        if($this->tabs->updateDetailsTab($input))
        {
            return json_encode(array('action' => 'true'));
            exit;
        }else{
            return json_encode(array('action' => 'false'));
            exit;
        }
    }

    public function deleteCustomTab(Request $request)
    {  
        if($this->tabs->deleteCustomTab($request->input('id')))
        {
            return json_encode(array('action' => 'true'));
            exit;
        }else{
            return json_encode(array('action' => 'false'));
            exit;
        }
    }
    
    
   
}
