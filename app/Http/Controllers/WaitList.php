<?php

namespace App\Http\Controllers;

use App\Functions\Functions;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Metas;
use App\Inventories;
use App\Customer;
use App\Products;
use Config;
use Session;
use Mail;
use DB;

class WaitList extends BaseController
{
    protected $domainName;
    protected $connection;
    /**
     * Show the profile for the given user.
     *
     * @param  int $id
     * @return Response
     */
    private $product;
    private $inventories;
    private $customer;
    private $meta;

    public function __construct(Request $request)
    {
        parent::__construct();


        $this->product = new Products();
        $this->inventories = new Inventories();
        $this->customer = new Customer();
        $this->meta = new Metas();
        $this->connection = Session::get('connection');
        $this->domainName = Functions::getDomainName($this->connection);

    }


    /**
     * @param null $sku
     * @param null $newQty
     * @param null $returnType
     * @return array
     */
    public function processWaitList($sku = null, $newQty = null, $returnType = null)
    {


        $return  = ['message' => '', 'action' => false];

        if (!is_null($sku) && !is_null($newQty)) {

            $product_DB = $this->product->getProductForWaitList($sku);

            //dd($product_DB);
            if (!is_null($product_DB)) {

                $waitListItem = Metas::where('meta_name', 'woocommerce_waitlist')->where('product_id', $product_DB->id)->first();

                if (!is_null($waitListItem)) {
                    //echo $waitListItem->meta_value.'<br>';
                    $customers_DB = @unserialize($waitListItem->meta_value);
                    $customersDone = array();

                    foreach ($customers_DB as $customer) {
                        $customers[$customer] = $customer;
                    }

                    $customersCount = count($customers_DB);
                    //dd($customers);

                        $product_sku = $product_DB->sku;
                        $product_id = $product_DB->id;
                        $checkQtyDB = $this->inventories->getInventoryByProductSKU($product_sku);
                        echo $newQty;
                        echo $checkQtyDB->stock_qty;
                        echo !is_null($checkQtyDB) ;
                        if (!is_null($checkQtyDB) && $checkQtyDB->stock_qty == 0 && $newQty > 0) {

                            $emailsCount = ceil($newQty * 1.5);


                            if ($newQty < $customersCount) // qty is equal or greater then awaiting users, send email to all
                            {
                                $processCustomers  = array_slice($customers, 0, $emailsCount);
                            }else{
                                $processCustomers = $customers;
                            }


                                foreach ($processCustomers as $customer) {
                                    // get customer email

                                    $customerDB = $this->customer->getCustomerById($customer);
                                    //dump($customerDB);
                                    if (!is_null($customerDB)) {
                                        // send email to users
                                        $customerEmail = $customerDB->email;

                                        $data = [];
                                        $data['images'] = array();
                                        $data['product_name']   =  $product_DB->name;
                                        $data['site_url']       =  'https://www.'.strtolower(Functions::getDomainName($this->connection));

                                        if($product_DB->product_type !='variation')
                                        {
                                            if($product_DB->media) {

                                                $i = 0 ;
                                                foreach ($product_DB->media as $media) {
                                                    if($i++ >3)
                                                        break;

                                                    $data['images'][] = featuredThumb($media->path);
                                                }
                                            }
                                            $data['product_url']    = 'https://www.'.strtolower(Functions::getDomainName($this->connection)).'/product/'.$product_DB->slug;
                                        }else{
                                            $product_variation_DB = $this->product->getProductForWaitListById($product_DB->parent_id);
                                            if($product_variation_DB) {
                                                if ($product_variation_DB->media) {

                                                    $i = 0;
                                                    foreach ($product_variation_DB->media as $media) {
                                                        if ($i++ > 3)
                                                            break;

                                                        $data['images'][] = featuredThumb($media->path);
                                                    }
                                                }
                                            }
                                            $data['product_url']    = 'https://www.'.strtolower(Functions::getDomainName($this->connection)).'/product/'.$product_variation_DB->slug;

                                        }


                                        //return view('email-templates.waitlist', ['data' => $data]);
                                        //exit;
                                        $mailSent = Mail::send('email-templates.waitlist', array('data' => $data), function ($mail) use ($customerEmail) {
                                            // use $customerEmail for too email

                                            //$mail->to('s.wessels@themusthaves.nl')
                                            $mail->to('lucky.uae1989@gmail.com')
                                                    ->from('klantenservice@themusthaves.nl', 'themusthaves.nl')
                                                    ->subject('Jouw favoriete Musthave is weer op voorraad bij '. Functions::getDomainName($this->connection));
                                        });

                                        if ($mailSent) // remove customer from waitlist
                                        {
                                            $customersDone[] = $customer;
                                        }
                                    }else{
                                        $return['message']  = 'Customer not found with id';
                                    }
                                }
                        }else{
                            $return['message']  = 'still out of stock';
                        }

                    //dump($customers);
                    $customersForDb = [];
                    foreach ($customers as $key => $customer)
                    {
                        if(!in_array($customer, $customersDone))
                        {
                            $customersForDb[] = $customer;
                        }
                    }
                    //dump($customersForDb);

                    if (empty($customersForDb)) // delete wait list record form meta table
                    {
                        $deleteMetaRecord = $this->meta->deleteMeta('woocommerce_waitlist', $product_id);
                        //dump($deleteMetaRecord);
                    } else {      // serialize and save remaining awaiting customers

                            $customersForDb = serialize($customersForDb); // save the remaining to database
                            $saveRemaning = $this->product->updateProductMeta('woocommerce_waitlist', $customersForDb, $product_id);
                            //dump($saveRemaning);
                    }
                }else{
                    $return['message']  =  'Wait List empty';
                }

            }else{
                $return['message']  = 'No product found for sku';
            }
        }else{
            $return['message']  = 'no sku or new quantity';
        }

        $return['action'] = true;

        if($returnType == null)
        {
            return $return;

        }else{
            echo json_encode($return);
            exit;
        }

    }


    public function processWaitListComposite(Request $request)
    {
        $input = $request->all() ;

        if($input['product_id']) {

            $product_id = $input['product_id'];

            $return  = ['message' => '', 'action' => false];
            $sku_db     = $this->product->getSKUbyId($product_id);
            if(!is_null($sku_db)) {

                $sku = $sku_db->sku;

                $product_DB = $this->product->getProductForWaitList($sku);

                $waitListItem = Metas::where('meta_name', 'woocommerce_waitlist')->where('product_id', $product_DB->id)->first();

                if (!is_null($waitListItem)) {
                    //echo $waitListItem->meta_value.'<br>';
                    $customers_DB = unserialize($waitListItem->meta_value);

                    $customersDone = array();

                    foreach ($customers_DB as $customer) {
                        $customers[$customer] = $customer;
                    }

                    $processCustomers = $customers_DB;

                    foreach ($processCustomers as $customer) {
                        // get customer email

                        $customerDB = $this->customer->getCustomerById($customer);
                        //dump($customerDB);
                        if (!is_null($customerDB)) {
                            // send email to users
                            $customerEmail = $customerDB->email;

                            $data = [];
                            $data['product_name'] = $product_DB->name;
                            $data['site_url'] = 'https://www.' . strtolower(Functions::getDomainName($this->connection));
                            $data['product_url'] = 'https://www.' . strtolower(Functions::getDomainName($this->connection)) . '/product/' . $product_DB->slug;
                            if ($product_DB->media) {

                                $i = 0;
                                foreach ($product_DB->media as $media) {
                                    if ($i++ > 3)
                                        break;

                                    $data['images'][] = featuredThumb($media->path);
                                }
                            }

                            //return view('email-templates.waitlist', ['data' => $data]);
                            //exit;
                            $mailSent = Mail::send('email-templates.waitlist', array('data' => $data), function ($mail) use ($customerEmail) {
                                // use $customerEmail for too email

                                //$mail->to('s.wessels@themusthaves.nl')
                                $mail->to($customerEmail)
                                    ->from('klantenservice@themusthaves.nl', 'themusthaves.nl')
                                    ->subject('Jouw favoriete Musthave is weer op voorraad bij ' . Functions::getDomainName($this->connection));
                            });

                            if ($mailSent) // remove customer from waitlist
                            {
                                $customersDone[] = $customer;
                            }
                        } else {
                            $return['message'] = 'Customer not found with id';
                        }
                    }

                    //dump($customers);
                    $customersForDb = [];
                    foreach ($customers as $key => $customer) {
                        if (!in_array($customer, $customersDone)) {
                            $customersForDb[] = $customer;
                        }
                    }
                    //dump($customersForDb);

                    if (empty($customersForDb)) // delete wait list record form meta table
                    {
                        $deleteMetaRecord = $this->meta->deleteMeta('woocommerce_waitlist', $product_id);
                        //dump($deleteMetaRecord);
                    } else {      // serialize and save remaining awaiting customers

                        $customersForDb = serialize($customersForDb); // save the remaining to database
                        $saveRemaning = $this->product->updateProductMeta('woocommerce_waitlist', $customersForDb, $product_id);
                        //dump($saveRemaning);
                    }


                } else {
                    $return['message'] = 'No product found for sku';
                }
            }else{
                $return['message']  = 'product sku not found';
            }
            }else{
                $return['message']  = 'product not found';
            }

            $return['action'] = true;

            echo json_encode($return);
            exit;

        }


    public function getWaitListDetails($product_id)
    {
        return DB::connection($this->connection)->table('composite_waitlist')->where('product_id' , $product_id)->get();
    }

}