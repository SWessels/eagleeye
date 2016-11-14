<?php

namespace App;
use DB;

use Illuminate\Database\Eloquent\Model;

class Customer extends BaseModel
{
    public $table = "customers";
    protected $fillable = [
        'id','first_name','last_name','username','email','password','status','postcode'
    ];
    //add routine for customer billing section

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    ////////////Has one relation with billing///////////

    public function customerbilling()
    {
        return $this->hasOne('App\CustomerBilling' , 'customer_id' ,'id');
    }
    ////////////Has one relation with Shipping///////////
    public function customershipping()
    {
        return $this->hasOne('App\CustomerShipping' , 'customer_id' , 'id');
    }

    ////////////Has many relation with Orders///////////
    public function Orders()
    {
        return $this->hasMany('App\Orders' , 'customer_id' , 'id');
    }

    /////////////customer add function//////////////////
    public function add($data)
    {
        Customer::firstOrCreate($data);
        return DB::connection($this->connection)->getPdo()->lastInsertId();
    }
    
    public function checkIfExist($field = 'id', $field_value = false)
    {
        $count  = false ;
        if($field_value)
        {
            if(Customer::Where($field, '=', $field_value)->get()->count() > 0)
                $count  = true ;
        }
        return $count;
    }

     /////////////////////get customer details by id/////////////////////
    public function getCustomerId($field = 'email', $field_value = false)
    {
            $id = false;
            if ($field_value) {
                    $user = Customer::select('id')->where($field, $field_value)->first();

                    if ($user) {
                            $id = $user->id;
                            return $id;
                    }
            }
    }
   /////////////////////////get all customers/////////////////
    public function getallcustomers()
    {
     return Customer::orderBy('id', 'desc')->paginate(60);
    }

    //////////get all customers by keyword////////////////////
    public function getallcustomersSearch($args)
    {

      //  DB::connection()->enableQueryLog();
        $query = Customer::where(function ($query) use ($args) {
            foreach ($args as $arg) {
                $query->Where(
                    $arg['column'],
                    $arg['operator'],
                    $arg['value']);
            }


        })
            ->orderBy('id', 'desc');

        return $query->paginate(60);
        //$query = DB::getQueryLog($query);

    }
   ////////////////////get customers status count//////////////////////
    public function getCustomerCounts()
    {
        $counts = array('all' => 0, 'active' => 0, 'inactive' => 0, 'deleted' => 0);

        $all = Customer::count();
        $counts['all'] = $all;

        $query = Customer::query('');
        $query->where('status', '=', 'active');
        $counts['active'] = $query->count();

        $query = Customer::query('');
        $query->where('status', '=', 'inactive');
        $counts['inactive'] = $query->count();

        $query = Customer::query('');
        $query->where('status', '=', 'deleted');
        $counts['deleted'] = $query->count();


        return $counts;

    }
    ////////////////////delete customer by ID//////////////////////
    public function DeleteCustomer($input)
    {
        if (isset($input['remove']) && $input['remove'] == 'delete') {
            foreach ($input['del'] as $key => $del) {
                Customer::where('id', '=', $del)->update(['status' => 'deleted']);

            }
        } else if (isset($input['remove']) && $input['remove'] == 'inactive') {
            foreach ($input['del'] as $key => $del) {
                Customer::where('id', '=', $del)->update(['status' => 'inactive']);

            }

        }

    }
    ////////////////////Add new customer////////////////////
    public function AddNewCustomer($input)
    {
        $username = trim($input['username']);
        $fname = trim($input['first_name']);
        $lname = trim($input['last_name']);
        $email = trim($input['email']);
        $password = trim($input['password']);
        $status = trim($input['status']);
        $dataArrayForCustomer = array(
            'username' 	    => $username,
            'email' 			=> $email,
            'password'        =>$password,
            'first_name'          => $fname,
            'last_name'        =>$lname,
            'status'     =>$status,
            'created_at'		=> date('Y-m-d H:i:s')

        );
        Customer::insert($dataArrayForCustomer);
        $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();

        return $lastInsertedId;
    }
    //////////////////get customer by id///////////////////////
    public function getCustomerById($id){

        return Customer::with('CustomerBilling')

                         ->with('CustomerShipping')
                        ->where('id', '=' , $id)->first();
         }

    public function getCustomerEmail($id){

        return Customer::select('email')->where('id', '=' , $id)->first();
    }
    /////////////////update customer by id///////////////////////
     public function UpdateCustomerById($input ,$id){

        $username = trim($input['username']);
        $status = trim($input['status']);

        /////////////Billing info///////////////////
        $billing_fname = trim($input['billing_first_name']);
        $billing_lname = trim($input['billing_last_name']);
        $bill_address1 = trim($input['billing_address1']);
        $bill_city = trim($input['billing_city']);
        $bill_pcode = trim($input['billing_post_code']);
        $bill_country = trim($input['billing_country']);
        $bill_phone = trim($input['billing_phone']);
        $bill_email = trim($input['billing_email']);

        ////////////shipping info/////////////////////////
        $shipping_fname = trim($input['shipping_first_name']);
        $shipping_lname = trim($input['shipping_last_name']);
        $ship_address1 = trim($input['shipping_address1']);
        $ship_city = trim($input['shipping_city']);
        $ship_pcode = trim($input['shipping_post_code']);
         $ship_country = trim($input['shipping_country']);


        $updateArrayForCustomer = array(
            'username' 	    => $username,
            'status'     =>$status,
            'updated_at'		=> date('Y-m-d H:i:s')

        );
        Customer::where('id', $id)
            ->update($updateArrayForCustomer);

        DB::connection($this->connection)->table('customer_billing')->where('customer_id', '=',$id)->delete();

        $dataForCustomerBilling = array(
            'customer_id'  => $id,
            'first_name' => $billing_fname,
            'last_name' => $billing_lname,
            'address_1'   =>$bill_address1,
            'city'        =>$bill_city,
            'postcode'     =>$bill_pcode,
            'country'     =>$bill_country,
            'phone'     =>$bill_phone,
            'email'     =>$bill_email,
            'created_at' => date('Y-m-d H:i:s')

        );

        DB::connection($this->connection)->table('customer_billing')->insert($dataForCustomerBilling);
        DB::connection($this->connection)->table('customer_shipping')->where('customer_id', '=',$id)->delete();

        $dataForCustomerShipping = array(
            'customer_id'  => $id,
            'first_name' => $shipping_fname,
            'last_name' => $shipping_lname,
            'address_1'   =>$ship_address1,
            'city'        =>$ship_city,
            'postcode'     =>$ship_pcode,
            'country'     =>$ship_country,
            'created_at' => date('Y-m-d H:i:s')

        );
        DB::connection($this->connection)->table('customer_shipping')->insert($dataForCustomerShipping);

    }

    public static function getCustomerInfo($fields = '*', $user_id = false)
    {
        if(is_array($fields)  && $user_id)
        {
            $str = '';
            foreach ($fields as $field)
            {
                $str .= "'".$field."',";
            }

            $str  = rtrim($str, ',');

            return Customer::select($fields)->where('id', $user_id)->first();
        }else{
            return Customer::where('id', $user_id)->first();
        }

    }



} 
