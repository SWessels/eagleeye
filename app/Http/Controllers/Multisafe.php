<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//require_once base_path('vendor/multisafepay/api/models/API/Autoloader.php');
require_once base_path('vendor/multisafepay/api/config/config.php');

class Multisafe extends Controller
{
    private $api_key = '';
    private $api_url = '';

    function __construct() {
        $this->api_key = API_KEY;
        $this->api_url = API_URL;
    }

    //used to fetch issuers and gateways
    public function getInformation(){
        $msp = new \MultiSafepayAPI\Client;
        $msp->setApiKey($this->api_key);
        $msp->setApiUrl($this->api_url);
        $issuers = $msp->issuers->get();

        $payment_gateways = $msp->gateways->get();

        echo json_encode(array('issuers'=>$issuers, 'gateways'=>$payment_gateways));
    }

    //
    public function connect(){


        $msp = new \MultiSafepayAPI\Client;
        $msp->setApiKey($this->api_key);
        $msp->setApiUrl($this->api_url);

        try {
            $order_id = time();

            $order = $msp->orders->post(array(
                "type" => "redirect",
                "order_id" => $order_id,
                "currency" => "EUR",
                "amount" => 20,
                "description" => "Demo Transaction",
                "var1" => "",
                "var2" => "",
                "var3" => "",
                "items" => "items list",
                "manual" => "false",
                "gateway" => "MASTERCARD",
                "days_active" => "30",
                "payment_options" => array(
                    "notification_url" => BASE_URL . "multisafe/notify",
                    "redirect_url" => BASE_URL . "multisafe/complete",
                    "cancel_url" => BASE_URL . 'multisafe/cancel',
                    "close_window" => "true"
                ),
                "customer" => array(
                    "locale" => "nl_NL",
                    "ip_address" => "127.0.0.1",
                    "forwarded_ip" => "127.0.0.1",
                    "first_name" => "Jan",
                    "last_name" => "Modaal",
                    "address1" => "Kraanspoor",
                    "address2" => "",
                    "house_number" => "39",
                    "zip_code" => "1032 SC",
                    "city" => "Amsterdam",
                    "state" => "",
                    "country" => "NL",
                    "phone" => "0208500500",
                    "email" => "test@test.nl",
                ),
                "gateway_info" => array(
                    "birthday" => "10071970",
                    "bank_account" => "",
                    "phone" => "0612345678",
                    "referrer" => "http://google.nl",
                    "user_agent" => "msp01",
                    "gender" => "male",
                    "email" => "test@test.nl",
                ),
                "shopping_cart" => array(
                    "items" => array(
                        array(
                            "name" => "Test",
                            "description" => "",
                            "unit_price" => "10",
                            "quantity" => "2",
                            "merchant_item_id" => "test123",
                            "tax_table_selector" => "BTW0",
                            "weight" => array(
                                "unit" => "KB",
                                "value" => "20",
                            )
                        )
                    )
                ),
                "checkout_options" => array(
                    "no-shipping-methods" => false,
                    "shipping_methods" => array(
                        "flat_rate_shipping" => array(
                            array(
                                "name" => "Post Nl - verzending NL",
                                "price" => "7",
                                "currency" => "",
                                "allowed_areas" => array(
                                    "NL"
                                ),
                            ), array(
                                "name" => "TNT verzending",
                                "price" => "9",
                                "excluded_areas" => array(
                                    "NL", "FR", "ES"
                                )
                            )
                        ),
                        "pickup" => array(
                            "name" => "Ophalen",
                            "price" => "0",
                        )
                    ),
                    "tax_tables" => array(
                        "default" => array(
                            "shipping_taxed" => "true",
                            "rate" => "0.21"
                        ),
                        "alternate" => array(
                            array(
                                "standalone" => "true",
                                "name" => "BTW0",
                                "rules" => array(
                                    array("rate" => "0.00")
                                ),
                            )
                        )
                    )
                ),
                "google_analytics" => array(
                    "account" => "UA-XXXXXXXXX",
                ),
                "plugin" => array(
                    "shop" => "MultiSafepay Toolkit",
                    "shop_version" => TOOLKIT_VERSION,
                    "plugin_version" => TOOLKIT_VERSION,
                    "partner" => "MultiSafepay",
                    "shop_root_url" => "http://www.demo.nl",
                ),
                "custom_info" => array(
                    "custom_1" => "value1",
                    "custom_2" => "value2",
                )
            ));
            //dd($msp->orders->getPaymentLink());
            return redirect()->away($msp->orders->getPaymentLink());
        } catch (Exception $e) {
            echo "Error " . htmlspecialchars($e->getMessage());
        }
    }

    public function notify(){
        echo 'notify';
    }

    public function cancel(){
        echo 'canceled';
    }

    public function complete(){
        echo 'completed';
    }

    public function doRefund(Request $request) {
        $msp = new \MultiSafepayAPI\Client;
        $msp->setApiKey($this->api_key);
        $msp->setApiUrl($this->api_url);
        $transactionid = $request->get('transactionid');
        //get the order status
        $order = $msp->orders->get($type = 'orders', $transactionid, $body = array(), $query_string = false);
        if ($order->status == "completed") {
            //the transaction status was competed, now we will refund the transaction
            $endpoint = 'orders/' . $transactionid . '/refunds';
            try {
                $order = $msp->orders->post(array(
                    "type" => "refund",
                    "amount" => "20",
                    "currency" => "EUR",
                    "description" => "PHP Wrapper Toolkit Refund",
                ), $endpoint);
            } catch (Exception $e) {
                echo "Error " . htmlspecialchars($e->getMessage());
            }
        }
       echo 'refund completed';
    }

    public function getDetails(Request $request) {
        //dd($request->get('transactionid'));
        $msp = new \MultiSafepayAPI\Client;
        $msp->setApiKey($this->api_key);
        $msp->setApiUrl($this->api_url);
        try {
           // $transactionid = '1437027267';
            $transactionid =$request->get('transactionid');
            //get the order
            $order = $msp->orders->get($endpoint = 'orders', $transactionid, $body = array(), $query_string = false);
        } catch (Exception $e) {
            echo "Error " . htmlspecialchars($e->getMessage());
        }
        dd($order);
    }
}
