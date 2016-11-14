<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Klarna\XMLRPC\Klarna;
use Klarna\XMLRPC\Country;
use Klarna\XMLRPC\Language;
use Klarna\XMLRPC\Currency;
use Klarna\XMLRPC\Flags;
use Klarna\XMLRPC\Address;
use Klarna\XMLRPC\PClass;


class KlarnaAPI extends Controller
{
    //connect to process payment
    public function connect(){
        $k = new Klarna();

        $k->config(
            1999,              // Merchant ID
            'nkIi9yPFtmDNvm8', // Shared secret
            Country::NL,    // Purchase country
            Language::NL,   // Purchase language
            Currency::EUR, // Purchase currency
            Klarna::LIVE    // Server
        );

        $k->addArticle(
            4,                 // Quantity
            'VP-222382643',        // Article number
            'Test Product Item', // Article name/title
            299.99,            // Price
            25,                // 25% VAT
            0,                 // Discount
            Flags::INC_VAT     // Price is including VAT.
        );

        $k->addArticle(1, '', 'Shipping fee', 14.5, 25, 0, Flags::INC_VAT | Flags::IS_SHIPMENT);
        $k->addArticle(1, '', 'Handling fee', 11.5, 25, 0, Flags::INC_VAT | Flags::IS_HANDLING);

        $addr = new Address(
            'irfanbaree@gmail.com', // Email address
            '',                           // Telephone number, only one phone number is needed
            '0647499884',                 // Cell phone number
            'Sebastiaan',              // First name (given name)
            'Wessels',                   // Last name (family name)
            '',                           // No care of, C/O
            'Staalstraat',                // Street address
            '8301XW',                      // Zip code
            'Emmeloord',                   // City
            Country::NL,                  // Country
            '134',                         // House number (AT/DE/NL only)
            ''                          // House extension (NL only)
        );

        $k->setAddress(Flags::IS_BILLING, $addr);
        $k->setAddress(Flags::IS_SHIPPING, $addr);

        try {
            $result = $k->reserveAmount(
                '10071970',   // PNO (Date of birth for AT/DE/NL)
                Flags::MALE,           // Flags::MALE, Flags::FEMALE (AT/DE/NL only)
                -1,             // Automatically calculate and reserve the cart total amount
                Flags::NO_FLAG,
                PClass::INVOICE
            );

            $rno = $result[0];
            $status = $result[1];

            // $status is Flags::PENDING or Flags::ACCEPTED.

            echo "OK: reservation {$rno} - order status {$status}\n";
        } catch (\Exception $e) {
            echo "{$e->getMessage()} (#{$e->getCode()})\n";
        }


    }

    //status check against reservation number
    public function checkStatus(Request $request){
        $k = new Klarna();

        $k->config(
            1999,              // Merchant ID
            'nkIi9yPFtmDNvm8', // Shared secret
            Country::NL,    // Purchase country
            Language::NL,   // Purchase language
            Currency::EUR, // Purchase currency
            Klarna::LIVE    // Server
        );
        try{
            $result = $k->checkOrderStatus( $request['rid'] );
            if ( $result == Flags::ACCEPTED ) {
               echo 'payment completed';
            } elseif ( $result == Flags::DENIED ) {
               echo 'payment cancelled';
            } else {
              echo 'payment pending';
            }

        } catch (\Exception $e) {
            echo "{$e->getMessage()} (#{$e->getCode()})\n";
        }

    }

    public function activateInvoice(Request $request){
        $k = new Klarna();

        $k->config(
            1999,              // Merchant ID
            'nkIi9yPFtmDNvm8', // Shared secret
            Country::NL,    // Purchase country
            Language::NL,   // Purchase language
            Currency::EUR, // Purchase currency
            Klarna::LIVE    // Server
        );
        try{
            $result = $k->activate($request['rid']);
        } catch (\Exception $e) {
            echo "{$e->getMessage()} (#{$e->getCode()})\n";
        }

        echo 'Klarna invoice generated at https://online.klarna.com/invoices/'.$result[1].'.pdf';
        $k->emailInvoice($result[1]);
        dd($result);
    }

    public function refund(Request $request){
        $k = new Klarna();

        $k->config(
            1999,              // Merchant ID
            'nkIi9yPFtmDNvm8', // Shared secret
            Country::NL,    // Purchase country
            Language::NL,   // Purchase language
            Currency::EUR, // Purchase currency
            Klarna::LIVE    // Server
        );

        try {
            $k->returnAmount(
                $request['rid'],           // Invoice number
                $request['amount'],            // Amount given as a discount.
                25,               // 25% VAT
                Flags::INC_VAT,   // Amount including VAT.
                'refund' // Description
            );

            echo "OK\n";return true;
        } catch (\Exception $e) {
            echo "{$e->getMessage()} (#{$e->getCode()})\n";
        }


        echo "amount not refunded";
        return false;
    }
}
