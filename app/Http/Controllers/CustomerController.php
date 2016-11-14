<?php

namespace App\Http\Controllers;
use App\Functions\Functions;
use App\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Customer;
use Session;
use DB;


class CustomerController extends BaseController
{

    private $customer;

    public function __construct()
    {
        parent::__construct();
        $this->customer = new Customer; 
    }

    public function index(){

        $args = array();
        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!='')
        {

            $args['keywords']['column'] 	= 'username';
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
            $customers = $this->customer->getallcustomersSearch($args);
        }
        else{

            $customers = $this->customer->getallcustomers();
        }
        $customer_counts = $this->customer->getCustomerCounts();
        $data = array('customers'   => $customers );

        return view('customers.customerlist' , ['data' => $data  , 'customerCount' => $customer_counts ]);
    }


    public function delete(Request $request)
    {
        $input = $request->all();
        $delete_customer = $this->customer->DeleteCustomer($input);
        Session::flash('flash_message', 'Customer Status Successfully Changed!');

        return redirect('customers');
    }

    public function create()
    {
          return view('customers.newcustomer');
    }

    public function store(Request $request)
    {

   //  echo "<pre>"; print_r($request->all()); echo"</pre>";
    //     exit;
        $this->validate($request, ['first_name' => 'required',
                                    'last_name' => 'required',
            'username'   => 'required|alpha_num|unique:customers',
             'email'      => 'required|email|unique:customers',
            'password'   => 'required',
            'status'       => 'required',
            //'billing_first_name'       => 'required',
            // 'billing_last_name'       => 'required',
           // 'billing_address1'       => 'required',
            //'billing_city'       => 'required',
            // 'billing_state'       => 'required',
           // 'billing_post_code'       => 'required|alpha_num',
           // 'billing_country'       => 'required',
           /// 'billing_phone'       => 'required|numeric',
           // 'billing_email'       => 'required|email',
           // 'shipping_first_name'       => 'required',
           // 'shipping_last_name'       => 'required',
           // 'shipping_address1'       => 'required',
           // 'shipping_city'       => 'required',
           // 'shipping_state'       => 'required',
           // 'shipping_post_code'       => 'required|numeric',
           // 'shipping_country'       => 'required',
            //'shipping_phone'       => 'required|numeric',
            //'shipping_email'       => 'required|email',


        ]);
        $input = $request->all();


        $customer_inserted_id = $this->customer->AddNewCustomer($input);
        

        Session::flash('flash_message', 'Customer Successfully Created!');
        return redirect()->action('CustomerController@edit',['id' => $customer_inserted_id]);
    }

    public function edit($id)
    {
        $id_exists = Functions::checkIdInDB($id ,$this->customer->table);
        if($id_exists > 0) {

            $customerById = $this->customer->getCustomerById($id);
           //dd($customerById);
            return view('customers.editcustomer', [
                'customerById' => $customerById

            ]);
        }
        else{
            abort(404, 'Not Found.');
        }
    }
    public function update(Request $request, $id)
    {

        $input = $request->all();

        $updatepost = $this->customer->UpdateCustomerById($input ,$id);
        Session::flash('flash_message', 'Customer Successfully Updated!');
        return redirect()->action('CustomerController@edit', ['id' =>  $id]);
    }

    public function getCustomers()
    {
        return $this->customer->getallcustomers(); 
    }

}