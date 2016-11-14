<?php

namespace App\Http\Controllers;

use App\Metas;
use App\Products;
use Illuminate\Http\Request;

use App\Http\Requests;
use Config;
use App\Functions\Functions;
use App\Inventories;
use App\Attributes;
use App\Terms;
use DB;
use Mockery\CountValidator\Exception;
use Session;

class ProductFeedsController extends BaseController
{
    // create product feeds in txt and csv format
    //protected $connection;
    protected $inventories;
    protected $attributes;
    protected $terms;
    protected $products;
    public function __construct(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
        parent::__construct();
        $this->inventories = new Inventories();
        $this->attributes = new Attributes();
        $this->terms = new Terms();
        $this->products = new  Products();
    }

    public function index()
    {
        return view('ecommerce.productfeeds');
    }

    public function feedAll($feed = 'all')
    {
        if($feed == 'all')
        {
            $feeds = Config::get('feeds');
            $domain_feeds = $feeds[$this->connection] ;

            foreach ($domain_feeds as $domain_feed) {
                switch ($domain_feed) {
                    case 'googlefeed':
                        $this->googleFeedTxt();
                        break;
                    case 'facebookfeed':
                        $this->facebookFeedTxt();
                        break;
                    case 'zanoxfeed':
                        $this->zenoxFeedTxt();
                        $this->zanoxFeedCsv();
                        break;  
                }
            }

            Session::flash('flash_message', 'All Feeds Successfully Created!');
            return redirect()->to('productfeeds');
        }else{
            switch ($feed) {
                case 'googlefeed':
                    $this->googleFeedTxt();
                    Session::flash('flash_message', 'Google Feed Successfully Created!');
                    return redirect()->to('productfeeds');
                    break;
                case 'facebookfeed':
                    $this->facebookFeedTxt();
                    Session::flash('flash_message', 'Facebook Feed Successfully Created!');
                    return redirect()->to('productfeeds');
                    break;
                case 'zanoxfeed':
                    $this->zenoxFeedTxt();
                    $this->zanoxFeedCsv();
                    Session::flash('flash_message', 'Zanox Feed Successfully Created!');
                    return redirect()->to('productfeeds');
                    break;

            }
        }
    }

    public function googleFeedTxt()
    {
        $fp = fopen('googlefeed.txt', 'wb');

        $ce_a = array('titel', 'id', 'beschrijving', 'link', 'beschikbaarheid', 'prijs', 'afbeeldingslink', 'leeftijdsgroep', 'geslacht', 'producttype', 'staat', 'ID bestaat', 'verzending', 'google productcategorie', 'custom_label_0');

        fwrite($fp, implode("\t", $ce_a) . PHP_EOL);
        $given_cats = array('Nieuw!', 'Musthaves', 'Bestsellers', 'Ibiza Musthaves', 'Sale');
        $given_subcats = array('Alle accessoires', 'Alle kleding', 'Alleschoenen', 'Bestsellers', 'Ibiza Musthaves', 'Musthaves', 'Nieuw', 'Sale');

        $products = Products::with('categories')->with('inventories')->with('children')->where('product_type', '<>', 'variation')->get();

        foreach ($products as $product) {

            $inventory = $product->inventories;
            $custom_meta_value = "";

            $meta_val = $this->products->getProductMeta('feed_special_field', $product->id);
            if($meta_val)
            {
                $custom_meta_value = $meta_val->meta_value;
            }
            $product_cats = $product->categories ;

            $parrentcat = array();
            $sub_cat = array();
            foreach ($product_cats as $category) {
                $categoryname = $category->name;

                if (!in_array($categoryname, $given_cats)) {
                    $parrentcat[] = $categoryname;
                }

                if (!in_array($categoryname, $given_subcats)) {
                    $sub_cat[] = $categoryname;
                }
            }
            $parrentcategoryy = $parrentcat;

            if(!empty($sub_cat)) {

                $subcat = $sub_cat[0];
                $parcat = $parrentcat[0];
                $google_prod_cat = "";
                if ($subcat == "Tops") {
                    $google_prod_cat = "Kleding en accessoires > Kleding > Overhemden, shirts en bovenstukjes";
                } elseif ($subcat == "Jasjes en Vesten") {
                    $google_prod_cat = "Kleding en accessoires > Kleding > Buitenkleding > Jassen en jacks";
                } elseif ($subcat == "Armbanden") {
                    $google_prod_cat = "Kleding en accessoires > Sieraden > Armbanden";
                } elseif ($subcat == "Sjaals") {
                    $google_prod_cat = "Kleding en accessoires > Kledingaccessoires > Sjaals";
                } elseif ($subcat == "Kettingen") {
                    $google_prod_cat = "Kleding en accessoires > Sieraden > Halskettingen";
                } elseif ($subcat == "Oorbellen") {
                    $google_prod_cat = "Kleding en accessoires > Sieraden > Oorbellen";
                } elseif ($subcat == "Tassen") {
                    $google_prod_cat = "Kleding en accessoires > Handtassen, portefeuilles en houder > Handtassen";
                } elseif ($subcat == "Broeken") {
                    $google_prod_cat = "Kleding en accessoires > Kleding > lange broeken";
                } elseif ($subcat == "It Shirts") {
                    $google_prod_cat = "Kleding en accessoires > Kleding > Overhemden, shirts en bovenstukjes";
                } elseif ($subcat == "Jurken en Rokken") {
                    $google_prod_cat = "Kleding en accessoires > Kleding > Rokken";
                } elseif ($subcat == "Jumpsuits") {
                    $google_prod_cat = "Kleding en accessoires > Kleding > Eendelige kledingstukken > Jumpsuits en rompertjes";
                } elseif ($subcat == "Horloges") {
                    $google_prod_cat = "Kleding en accessoires > Sieraden > Horloges > Analoge horloges";
                } elseif ($subcat == "Musthave Deals") {
                    $google_prod_cat = "Kleding en accessoires > Kleding";
                } elseif (($subcat == "Schoenen") || ($parcat == "Schoenen")) {
                    $google_prod_cat = "Kleding en accessoires > Schoenen";
                }

            }

            $inventory = $product->inventories;


            if ($categoryname != "Shoparchief" && $product->product_type !='variable' && $inventory  && $inventory->stock_status != 'out' && !empty($sub_cat)) {
                //echo $categoryname . '---  '. $inventory->stock_status . '<br>';
                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));

                $price_data = html_entity_decode(Functions::getCurrencySymbol() . " " . $product->regular_price);
                $availability = "";
                if ($inventory->stock_status == 'in')
                    $availability = "in voorraad";
                else
                    $availability = 'outofstock';
                
                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                $ce_b = array($product->name, $product->id, $desc_ce,  $site_url. "/product/" . $product->slug, $availability, $price_data, $site_url.'/uploads/'.$product->featured_image, 'Volwassenen', 'Dames', $sub_cat["0"], 'nieuw', 'False', 'NL:::2.95 EUR', $google_prod_cat, $custom_meta_value);
                //dump($ce_b);
                fwrite($fp, implode("\t", $ce_b) . PHP_EOL);
            }elseif ($categoryname != "Shoparchief" && $product->product_type =='variable' && !empty($sub_cat)) {
                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));

                $price_data = html_entity_decode(Functions::getCurrencySymbol() . " " . $product->regular_price);
                $availability = "";
                $stock_status = 'out';
                foreach ($product->children as $child) {
                    $child_inv = $child->inventories;
                    if ($child_inv) {
                        if ($child_inv->stock_status == 'in' || $child_inv->stock_qty > 0) {
                            $stock_status = 'in';
                            break;
                        }
                    }
                }
                if ($stock_status == 'in')
                    $availability = "in voorraad";
                else
                    $availability = 'outofstock';

                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                $ce_b = array($product->name, $product->id, $desc_ce,  $site_url. "/product/" . $product->slug, $availability, $price_data, $site_url.'/uploads/'.$product->featured_image, 'Volwassenen', 'Dames', $sub_cat["0"], 'nieuw', 'False', 'NL:::2.95 EUR', $google_prod_cat, $custom_meta_value);
                //dump($ce_b);
                fwrite($fp, implode("\t", $ce_b) . PHP_EOL);
            }
        }
        fclose($fp);
    }

    public function facebookFeedTxt()
    {

        $fp = fopen('facebookfeed.txt', 'wb');
        $ce_a = array('id','availability', 'condition', 'description', 'image_link', 'link', 'title', 'price',  'brand');
        fwrite($fp, implode("\t", $ce_a) . PHP_EOL);

        $products = Products::with('categories')->with('inventories')->with('children')->where('product_type', '<>', 'variation')->get();

        foreach ($products as $product) {
            $product_cats = $product->categories;
            foreach ($product_cats as $category) {
                $categoryname = $category->name;
            }
            $inventory = $product->inventories; 
            if ($product->product_type !='variable' && $inventory && $categoryname != "Shoparchief" &&  $inventory->stock_status != 'out')
            {
                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));
                $price_data = html_entity_decode(Functions::getCurrencySymbol()." ".$product->regular_price);
                $availability = "";
                if($inventory->stock_status == 'in')
                    $availability = "in voorraad";
                else
                    $availability = 'out of stock';

                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                $ce_b = array($product->id , $availability , 'New', $desc_ce, productImage('', $product->featured_image), $site_url."/product/" . $product->slug, $product->title,  $price_data, 'TheMusthaves');
                fwrite($fp, implode("\t", $ce_b). PHP_EOL);
            }elseif ($categoryname != "Shoparchief" && $product->product_type =='variable' && !empty($sub_cat)) {
                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));
                $price_data = html_entity_decode(Functions::getCurrencySymbol()." ".$product->regular_price);
                $availability = "";

                $stock_status = 'out';
                foreach ($product->children as $child) {
                    $child_inv = $child->inventories;
                    if ($child_inv) {
                        if ($child_inv->stock_status == 'in' || $child_inv->stock_qty > 0) {
                            $stock_status = 'in';
                            break;
                        }
                    }
                }
                if ($stock_status == 'in')
                    $availability = "in voorraad";
                else
                    $availability = 'out of stock';

                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                $ce_b = array($product->id , $availability , 'New', $desc_ce, productImage('', $product->featured_image), $site_url."/product/" . $product->slug, $product->title,  $price_data, 'TheMusthaves');
                fwrite($fp, implode("\t", $ce_b). PHP_EOL);
            }
        }
        fclose($fp);
    }

    public function zenoxFeedTxt()
    {
        $fp = fopen('Zanoxfeed.txt', 'wb');
        $ce_a = array('URL', 'Title', 'Description', 'Price', 'ID', 'Img_medium', 'Img_large', 'Category', 'Subcategory', 'Shipping time', 'Shipping costs', 'Stock status', 'Condition',
            'Color', 'Size', 'Gender', 'Old price');

        fwrite($fp, implode("\t", $ce_a) . PHP_EOL);

        $products = Products::with('categories')->with('inventories')->with('children')->with('attributes')->where('product_type', '<>', 'variation')->get();

        foreach ($products as $product) {


            $product_cats = $product->categories;
            $given_cats = array('Nieuw!', 'Musthaves', 'Bestsellers', 'Ibiza Musthaves', 'Sale');
            $given_subcats = array('Alle accessoires', 'Alle kleding', 'Alleschoenen', 'Bestsellers', 'Ibiza Musthaves', 'Musthaves', 'Nieuw', 'Sale');

            $parrentcat = array();
            $sub_cat = array();
            foreach ($product_cats as $category) {
                $categoryname = $category->name;
                if (!in_array($categoryname, $given_cats)) {
                    $parrentcat[] = $categoryname;
                }
                if (!in_array($categoryname, $given_subcats)) {
                    $sub_cat[] = $categoryname;
                }
            }
            $parrentcategoryy = $parrentcat;


            $inventory = $product->inventories;

            if ($product->product_type !='variable' &&  $inventory  && !in_array("Shoparchief", $sub_cat) && $inventory->stock_status != 'out')
            {

                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));


                $small_img_ce =  productImage('small', $product->featured_image);

                //$product = wc_get_product($values_id);
                $ce_color_val = Metas::where('product_id', $product->id )->where('meta_name', 'ce_colors_entry')->first();

                $ce_color_val = unserialize($ce_color_val['meta_value']);

                if (!empty($ce_color_val)) {
                    $value23 = explode(',', $ce_color_val);
                    $ce_color = implode(' / ', $value23);
                }else{
                    $ce_color = '';
                }



                $price_regular = $product->regular_price;
                $price_sale = $product->sale_price;
                if ($price_sale == "" ||  $price_sale == 0 || $price_sale == 0.0) {
                    $old_price = "";
                    $ce_price = $product->regular_price;
                } else {
                    $old_price = $price_sale;
                    $ce_price = $price_regular;
                }
                $post_name = $product->name;
                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                if (empty($post_name)) {
                    $prod_url = " ";
                } else {
                    $prod_url = $site_url . "/product/" . $product->slug;
                }

                $ce_size = '' ;

                if(!emptyArray($sub_cat))
                {
                    $sub_catt =  $sub_cat[0];
                }else{
                    $sub_catt = '';
                }

                if(!emptyArray($parrentcategoryy))
                {
                    $parrentcatt = $parrentcategoryy[0];
                }else{
                    $parrentcatt = '';
                }


                $ce_b = array($prod_url, $product->name, $desc_ce, $ce_price, $product->id, $small_img_ce,
                    productImage('medium', $product->featured_image), $parrentcatt, $sub_catt , "1 day", "2.95", "1", "New", $ce_color, $ce_size, "Female", $old_price);
                fwrite($fp, implode("\t", $ce_b) . PHP_EOL);
            }elseif ($product->product_type =='variable' && !in_array("Shoparchief", $sub_cat))
            {
                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));


                $small_img_ce =  productImage('small', $product->featured_image);

                //$product = wc_get_product($values_id);
                $ce_color_val = Metas::where('product_id', $product->id )->where('meta_name', 'ce_colors_entry')->first();
                $ce_color_val = unserialize($ce_color_val['meta_value']);

                if (!empty($ce_color_val)) {
                    $value23 = explode(',', $ce_color_val);
                    $ce_color = implode(' / ', $value23);

                }else{
                    $ce_color = '';
                }

                $variation = $product->children;

                $variation_data = '';
                foreach ($variation as $size_variations) {
                    //$child_inventories 	= $this->inventories->getInventoryByProductSKU((int)$size_variations->sku);
                    $child_inventories 	= DB::connection('eagleeye')->table('inventories')->where('product_sku', $size_variations->sku)->first();
                    $attributes = $this->attributes->getAttributesByProductId((int)$size_variations->id);

                    if($attributes && $child_inventories)
                    {
                        $term_data = $this->terms->getTermBySlug(implode(',', unserialize($attributes->attributes)));
                        //dd($term_data);
                        if($term_data)
                        {
                            $variation_data  .=  strtoupper($term_data->name) . ':' . $child_inventories->stock_qty .' / ';
                        }else{
                            $variation_data  .=  strtoupper(implode(',', unserialize($attributes->attributes))) . ':' . $child_inventories->stock_qty .' / ';
                        }
                    }

                }



                $ce_size = rtrim($variation_data, "/ ");


                $price_regular = $product->regular_price;
                $price_sale = $product->sale_price;
                if ($price_sale == "" ||  $price_sale == 0 || $price_sale == 0.0) {
                    $old_price = "";
                    $ce_price = $product->regular_price;
                } else {
                    $old_price = $price_sale;
                    $ce_price = $price_regular;
                }
                $post_name = $product->name;
                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                if (empty($post_name)) {
                    $prod_url = " ";
                } else {
                    $prod_url = $site_url . "/product/" . $product->slug;
                }



                if(!emptyArray($sub_cat))
                {
                    $sub_catt =  $sub_cat[0];
                }else{
                    $sub_catt = '';
                }

                if(!emptyArray($parrentcategoryy))
                {
                    $parrentcatt = $parrentcategoryy[0];
                }else{
                    $parrentcatt = '';
                }

                $ce_b = array($prod_url, $product->name, $desc_ce, $ce_price, $product->id, $small_img_ce,
                    productImage('medium', $product->featured_image), $parrentcatt, $sub_catt , "1 day", "2.95", "1", "New", $ce_color, $ce_size, "Female", $old_price);
                fwrite($fp, implode("\t", $ce_b) . PHP_EOL);



            }
        }
    }

    public function zanoxFeedCsv()
    {

        $fp = fopen('Zanoxfeed.csv', 'wb');
        $ce_a = array('Title', 'Description', 'Price', 'ID', 'Img_medium', 'Img_large', 'Category', 'Subcategory', 'Shipping time', 'Shipping costs', 'Stock status', 'Condition',
            'Color', 'Size', 'Gender', 'Old price','URL');
        fputcsv($fp, $ce_a);

        $products = Products::with('categories')->with('inventories')->with('children')->with('attributes')->where('product_type', '<>', 'variation')->get();

        foreach ($products as $product) {


            $product_cats = $product->categories;
            $given_cats = array('Nieuw!', 'Musthaves', 'Bestsellers', 'Ibiza Musthaves', 'Sale');
            $given_subcats = array('Alle accessoires', 'Alle kleding', 'Alleschoenen', 'Bestsellers', 'Ibiza Musthaves', 'Musthaves', 'Nieuw', 'Sale');

            $parrentcat = array();
            $sub_cat = array();
            foreach ($product_cats as $category) {
                $categoryname = $category->name;
                if (!in_array($categoryname, $given_cats)) {
                    $parrentcat[] = $categoryname;
                }
                if (!in_array($categoryname, $given_subcats)) {
                    $sub_cat[] = $categoryname;
                }
            }
            $parrentcategoryy = $parrentcat;


            $inventory = $product->inventories;

            if ($product->product_type !='variable' &&  $inventory  && !in_array("Shoparchief", $sub_cat) && $inventory->stock_status != 'out')
            {

                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));


                $small_img_ce =  productImage('small', $product->featured_image);

                //$product = wc_get_product($values_id);
                $ce_color_val = Metas::where('product_id', $product->id )->where('meta_name', 'ce_colors_entry')->first();
                $ce_color_val = unserialize($ce_color_val['meta_value']);

                if (isset($ce_color_val) && !emptyArray($ce_color_val)) {
                    $value23 = explode(',', $ce_color_val);
                    $ce_color = implode(' / ', $value23);
                }else{
                    $ce_color = '';
                }

                $price_regular = $product->regular_price;
                $price_sale = $product->sale_price;
                if ($price_sale == "" ||  $price_sale == 0 || $price_sale == 0.0) {
                    $old_price = "";
                    $ce_price = $product->regular_price;
                } else {
                    $old_price = $price_sale;
                    $ce_price = $price_regular;
                }
                $post_name = $product->name;
                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                if (empty($post_name)) {
                    $prod_url = " ";
                } else {
                    $prod_url = $site_url . "/product/" . $product->slug;
                }

                $ce_size = '' ;

                if(!emptyArray($sub_cat))
                {
                    $sub_catt =  $sub_cat[0];
                }else{
                    $sub_catt = '';
                }

                if(!emptyArray($parrentcategoryy))
                {
                    $parrentcatt = $parrentcategoryy[0];
                }else{
                    $parrentcatt = '';
                }


                $ce_b = array($product->name, $desc_ce, $ce_price, $product->id, $small_img_ce,
                    productImage('medium', $product->featured_image), $parrentcatt, $sub_catt , "1 day", "2.95", "1", "New", $ce_color, $ce_size, "Female", $old_price, $prod_url);

                fputcsv($fp,  $ce_b);
            }elseif ($product->product_type =='variable' && !in_array("Shoparchief", $sub_cat))
            {
                $desc_ce = strip_tags($product->description);
                $desc_ce = trim(preg_replace('/\s+/', ' ', $desc_ce));


                $small_img_ce =  productImage('small', $product->featured_image);

                //$product = wc_get_product($values_id);
                $ce_color_val = Metas::where('product_id', $product->id )->where('meta_name', 'ce_colors_entry')->first();
                $ce_color_val = unserialize($ce_color_val['meta_value']);

                if (isset($ce_color_val) && !emptyArray($ce_color_val)) {
                    $value23 = explode(',', $ce_color_val);
                    $ce_color = implode(' / ', $value23);
                }else{
                    $ce_color = '';
                }

                $variation = $product->children;

                $variation_data = '';
                foreach ($variation as $size_variations) {
                    //$child_inventories 	= $this->inventories->getInventoryByProductSKU((int)$size_variations->sku);
                    $child_inventories 	= DB::connection('eagleeye')->table('inventories')->where('product_sku', $size_variations->sku)->first();
                    $attributes = $this->attributes->getAttributesByProductId((int)$size_variations->id);

                    if($attributes && $child_inventories)
                    {
                        $term_data = $this->terms->getTermBySlug(implode(',', unserialize($attributes->attributes)));
                        //dd($term_data);
                        if($term_data)
                        {
                            $variation_data  .=  strtoupper($term_data->name) . ':' . $child_inventories->stock_qty .' / ';
                        }else{
                            $variation_data  .=  strtoupper(implode(',', unserialize($attributes->attributes))) . ':' . $child_inventories->stock_qty .' / ';
                        }
                    }

                }



                $ce_size = rtrim($variation_data, "/ ");


                $price_regular = $product->regular_price;
                $price_sale = $product->sale_price;
                if ($price_sale == "" ||  $price_sale == 0 || $price_sale == 0.0) {
                    $old_price = "";
                    $ce_price = $product->regular_price;
                } else {
                    $old_price = $price_sale;
                    $ce_price = $price_regular;
                }
                $post_name = $product->name;
                $domains = Config::get('domains');
                $site_url = 'http://'.mb_strtolower($domains[$this->connection]);
                if (empty($post_name)) {
                    $prod_url = " ";
                } else {
                    $prod_url = $site_url . "/product/" . $product->slug;
                }



                if(!emptyArray($sub_cat))
                {
                    $sub_catt =  $sub_cat[0];
                }else{
                    $sub_catt = '';
                }

                if(!emptyArray($parrentcategoryy))
                {
                    $parrentcatt = $parrentcategoryy[0];
                }else{
                    $parrentcatt = '';
                }

                $ce_b = array($product->name, $desc_ce, $ce_price, $product->id, $small_img_ce,
                    productImage('medium', $product->featured_image), $parrentcatt, $sub_catt , "1 day", "2.95", "1", "New", $ce_color, $ce_size, "Female", $old_price, $prod_url);
                fputcsv($fp,  $ce_b);
            }
        }
        fclose($fp);
    }
    
    public function saveSpecialField(Request $request)
    {
        $errors         = array();  	// array to hold validation errors
        $data 			= array(); 		// array to pass back data
        $product_id     = '';


// validate the variables ======================================================
        if (empty($request['product_name']))
        {
            $errors['product_name'] = 'Product Name is required.';
        }else{

            $p_db = $this->products->getProductIdByName($request['product_name']);
            if(!$p_db)
            {
                $errors['product_name'] = 'Product Name is required.';
            }else
            {
                $product_id = $p_db->id;
                $existing_meta = $this->products->getProductMeta('feed_special_field', $p_db->id);
                if(!empty($existing_meta))
                {
                    $errors['form_error'] = 'Field Already exist for '.$request['product_name'];
                }
            }

        }


        if (empty($request['specialfield']))
            $errors['specialfield'] = 'Field name is required.';

// return a response ===========================================================

        // response if there are errors
        if ( ! empty($errors)) {

            // if there are items in our errors array, return those errors
            $data['success'] = false;
            $data['errors']  = $errors;
        } else {

            // if there are no errors, return a message


            try{
                Metas::insert([
                    [
                        'meta_name'      => 'feed_special_field',
                        'meta_value'    => $request['specialfield'],
                        'product_id'   => $product_id
                    ]
                ]);

                $data['success'] = true;
                $data['message'] = 'Success!';
            }catch (Exception $e)
            {
                $data['success'] = false;
                $errors['specialfield'] = 'Error Saving Field';
                $data['errors']  = $errors;
            }

        }

        // return all our data to an AJAX call
        echo json_encode($data);
    }


    public function getSpecialFields()
    {
        $data = Metas::where('meta_name', 'feed_special_field')->with('Products')->get();
        $response = array();
        $i = 0 ;
        foreach ($data as $item)
        {
            $response[$i]['field_name'] = $item->meta_value;
            $response[$i]['product_name'] = $item->products->name;
            $response[$i]['product_id'] = $item->product_id;

            $i++;
        }

        return json_encode($response);
    }

    public function deleteSpecialField(Request $request)
    {
        $input = $request->all() ;
        if($input['fid'])
        {
             Metas::where('meta_name' , 'feed_special_field')->where('product_id', $input['fid'])->delete() ;
        }

        return json_encode(array('action' => true));
    }

}
