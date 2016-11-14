<?php

namespace App\Http\Controllers;
use App\User;
use App\Products;
use App\Terms;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Functions\Functions;
use App\Http\Requests;
use App\Categories;
use App\ProductAttributes;
use Session;
use DB;

class TermsController extends BaseController
{
    private $att;
    private $term;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
         parent::__construct();
        // check if user has products capability
        if (User::userCan('products') === false) {
            abort(403, 'Unauthorized action.');
        }

        
        $this->att = new ProductAttributes;
        $this->term = new Terms;

    }

    public function index()
    {
       $att_id = $_REQUEST['att_id'];
        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!='') {
            $termsbyAttid = $this->term->fsearchterms($_REQUEST['keywords'], $att_id);
            $att_name = $this->term->getAttName($att_id);

        }else{
            $termsbyAttid = $this->term->getTermsbyIdAttid($att_id);
             $att_name = $this->term->getAttName($att_id);
        }
             return view('products.terms.terms', ['terms' => $termsbyAttid , 'id' => $att_id , 'att_name' => $att_name]);

    }
    public function listterms($id)
    {

       // $termsbyAttid = $this->term->getTermsbyIdAttid($id);
        $termsbyAttid = $this->att->getTermsbyIdAttid($id);

      //  dd($termsbyAttid);
        $att_name = $this->term->getAttName($id);
        return view('products.terms.terms', ['terms' => $termsbyAttid , 'id' => $id , 'att_name' => $att_name]);
    }

    public function edit($id){
        $id_exists = Functions::checkIdInDB($id ,$this->term->table);
        if($id_exists > 0){
        $termsbyId = $this->term->getTermById($id);
         return view('products.terms.editTerm', ['termbyid' => $termsbyId]);
        }
        else{
            abort(404, 'Category Not Found.');
        }
    }
    public function store(Request $request){

        $input =$request->all();
        
        $att_id = $input['att_id'];
        $this->validate($request, ['name' => 'required','slug' => 'required' ]);
        $saveTerm =  $this->term->SaveTerm($input);
        $termsbyAttid = $this->term->getTermsbyIdAttid( $att_id );

        Session::flash('flash_message', 'Term successfully created!');

        return view('products.terms.terms', ['terms' => $termsbyAttid , 'id' => $att_id ]);
        
    }
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $att_id = $input['att_id'];
        $tupdate =  $this->term->TermUpdatebyId($id, $input);
        $termsbyAttid = $this->term->getTermsbyIdAttid( $att_id );

        Session::flash('flash_message', 'Term Successfully Updated!');

        return redirect()->action('TermsController@listterms', ['id' => $att_id]);

    }
    public function delete(Request $request)
    {
        $input = $request->all();

        $input['remove'];
         $att_id = $input['att_id'];
        if($input['remove'] == 'rm'){

            foreach($input['del'] as $key => $del){
                 Terms::where('id', '=',$del)->delete();

            }
        }
        Session::flash('flash_message', 'Terms Successfully Removed!');

        return redirect()->action('TermsController@listterms', ['id' => $att_id]);
    }
}