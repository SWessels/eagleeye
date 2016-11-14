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
use App\Categories;

use App\ProductAttributes;
use Session;

class ProductAttributesController extends BaseController
{
    private $attributes;
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
        $att =  $this->att->AddnewAtt($input);
        Session::flash('flash_message', 'Attribute Successfully Added!');

        return redirect('attributes');



    }
    public function edit($id){

        $id_exists = Functions::checkIdInDB($id ,$this->att->table);
        if($id_exists > 0) {

            $attbyid = $this->att->getattributesbyId($id);

            return view('products.attributes.editattributes', ['attbyid' => $attbyid]);
        } else{
            abort(404, 'Category Not Found.');
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'       => 'required',
            'slug'   => 'required',
        ]);
        $input = $request->all();
       
        $tupdate =  $this->att->attributeUpdatebyId($id, $input);
        $attbyid = $this->att->getattributesbyId($id);

        Session::flash('flash_message2', 'Attribute Successfully Updated!');
        return view('products.attributes.editattributes', ['attbyid' => $attbyid]);

    }
    public function AttTerms($id){



    }

}