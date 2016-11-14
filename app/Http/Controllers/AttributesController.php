<?php

namespace App\Http\Controllers;
use App\User;
use App\Products;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Categories;
use App\ProductAttributes;
use App\Functions\Functions;
use Session;

class AttributesController extends BaseController
{
    private $attributes;
    private $product;
    private $category;
    private $att;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        parent::__construct();
        // check if user has products capability
        if(User::userCan('products') === false)
        {
            abort(403, 'Unauthorized action.');
        }
        $this->product 		= new Products;
        $this->category 	= new Categories;
        $this->att 	        = new ProductAttributes;
    }
    public function index()
    {
        $atts = $this->att->fGetAllAttributes();

       return view('products.attributes.attributes', ['atts' => $atts]);
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            'name'       => 'required',
            'slug'   => 'required',
        ]);
        $input = $request->all();
        $t =  $this->att->AddnewAtt($input);
        Session::flash('addtag', 'Attribute Successfully Added!');

        return redirect('attributes');



    }
    public function edit($id){



        $attbyid = $this->att->getattributesbyId($id);

        return view('products.attributes.editattributes' , ['attbyid' => $attbyid]);
 }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $tupdate =  $this->att->attributeUpdatebyId($id, $input);
        $attbyid = $this->att->getattributesbyId($id);

        Session::flash('flash_message', 'Attribute Successfully Updated!');
        return view('products.attributes.editattributes', ['attbyid' => $attbyid]);

    }
    public function AttTerms($id){
       


    }

}
