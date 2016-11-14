<?php

namespace App\Http\Controllers;
use App\Coupons;
use App\Customer;
use App\Functions\Functions;
use App\Categories;
use App\Inventories;
use App\PostCategories;
use App\PostTags;
use App\Attributes;
use App\Tabs;
use App\Sitemap;
use App\Tags;
use Illuminate\Http\Request;

use App\Http\Requests;
use DB;
use Auth;
use App\Products;
use App\Posts;
use App\Pages;
use App\SeoData;
use Mockery\CountValidator\Exception;
use Session;
use Config;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\PasswordHash;
use App\Orders;
use App\OrderBilling;
use App\OrderShipping;
use App\OrderPayment;
use App\OrderItems;
use App\Refunds;
use App\RefundItems;
use App\OrderCoupons;

define('THUMBNAIL_IMAGE_MAX_WIDTH', 100);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 100);
define('MASTER_CONNECTION', 'eagleeye');

class Import extends BaseController
{

    private $products;
    protected $connection;
    protected $hasher;

    public function __construct()
    {
        parent::__construct();
        $this->products = new products();
        $this->connection = Session::get('connection');

        //$this->hasher = new PasswordHash(8, true);
        //$password = 'admin';
        //$hash = $this->hasher->HashPassword($password);

        //$hash = '$P$BG2LjRF9ofzdTOOWuzdnxwB6HeRc5/1';



        //echo $check = $this->hasher->CheckPassword($correct, $hash);

        //var_dump($this->hasher->CheckPassword($password, $hash));
        //exit;
        /*if ($check)
            echo  "Check correct: '" . $check . "' (should be '1')\n";
        else
            echo "wrong";*/



       // exit;


        echo '<h2 style="display: none;">'.$this->connection."</h2>";
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');
    }

    public function index()
    {
        return view('pages.import');
    }

    public function importCategories($file)
    {

        $file = fopen("csv/".$this->connection."/".$file.".csv","r") or die(print_r(error_get_last(),true));
        $main = array();
        while(! feof($file))
        {
            $main[] = fgetcsv($file);
        }


        $main = array_filter($main);
        /*echo '<pre>';
        print_r($main);*/

        $parent_cat = array();
        foreach($main as $key => $item)
        {
            if(empty($item))
            {continue; }
            if($item[3]==''){
                $parent_cat[] = $main[$key];
                unset($main[$key]);
            }
        }

        // dd($parent_cat);

        foreach($parent_cat as $pkey => $pitem) {
            foreach ($main as $key => $item) {
                if ($item[3] == $pitem[0]){
                    $child_cat[] = $main[$key];
                    unset($main[$key]);
                }
            }
        }

        $gchild_cat = array();

        foreach($child_cat as $pkey => $pitem) {
            foreach ($main as $key => $item) {
                if ($item[3] == $pitem[0]){
                    $gchild_cat[] = $main[$key];
                    unset($main[$key]);
                }
            }
        }


        //print_r($main);
        //dd($parent_cat);
        //print_r($child_cat);
        //print_r($gchild_cat);


        fclose($file);



        foreach($parent_cat as $key => $data)
        {
            DB::connection($this->connection)->table('categories')->insert(
                [
                    'id'        => $data[0],
                    'name'      => $data[1],
                    'slug'      => $data[2],
                    'status'    => 'publish',
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                    'parent_category_id' => $data[3]

                ]
            );

        }



        foreach($child_cat as $key => $data)
        {

            DB::connection($this->connection)->table('categories')->insert(
                [
                    'id'        => $data[0],
                    'name'      => $data[1],
                    'slug'      => $data[2],
                    'status'    => 'publish',
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                    'parent_category_id' => $data[3]

                ]
            );
        }


        if(!empty($gchild_cat)) {

            foreach ($gchild_cat as $key => $data) {

                DB::connection($this->connection)->table('categories')->insert(
                    [
                        'id' => $data[0],
                        'name' => $data[1],
                        'slug' => $data[2],
                        'status' => 'publish',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'parent_category_id' => $data[3]

                    ]
                );
            }
        }


        //dd($parent_insert);


    }

    public function importTags($file)
    {
        $file = fopen("csv/".$this->connection."/". $file . ".csv", "r");
        $main = array();
        while (!feof($file)) {
            $main[] = fgetcsv($file);
        }


        $main = array_filter($main);
        foreach($main as $key => $data)
        {

            DB::connection($this->connection)->table('tags')->insert(
                [
                    'id'        => $data[0],
                    'name'      => $data[1],
                    'slug'      => $data[2]

                ]
            );
        }

        fclose($file);
    }

    public function importPosts($file){
        $file = fopen("csv/".$this->connection."/" . $file . ".csv", "r");
        $main = array();
        while (!feof($file)) {
            $main[] = fgetcsv($file);
        }

        $main = array_filter($main);

        $main_array = array_slice($main,1);
        //echo "<pre>"; print_r($main_array); echo "</pre>";exit;
        foreach($main_array as $key => $data) {
            $catexp = explode('|', $data[5]);
            $tagexp = explode('|', $data[6]);
            foreach ($catexp as $cat) {
                $countCategory = DB::connection($this->connection)->table('postcategories')->where('name', '=', $cat)->count();
                if ($countCategory == 0) {
                    $slug = str_slug($cat);
                    DB::connection($this->connection)->table('postcategories')->insert([ 'name' => $cat,'slug'=>  $slug,  'status' => 'publish' , 'parent_category_id' => '0'] );
                }
            }

            foreach ($tagexp as $tag) {
                $countCategory = DB::connection($this->connection)->table('posttags')->where('name', '=', $tag)->count();
                if ($countCategory == 0) {
                    $slug = str_slug($tag);
                    DB::connection($this->connection)->table('posttags')->insert([ 'name' => $tag,'slug'=>  $slug] );
                }
            }
        }

        foreach($main_array as $key => $data2)
        {

            DB::connection($this->connection)->table('posts')->insert([

                                'title'          => $data2[1],
                                'slug'           => $data2[9],
                                'author'          => Auth::id(),
                                'description'      => $data2[2],
                                'status'         => $data2[7],
                                'visibilty'      => 'visible',
                                'meta_description'=>$data2[3],
                                'published_at'      => date("Y-m-d H:i:s",$data2[3]),
                                'type'           => $data2[4],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')]);
           $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();

            $catexp2 = explode( '|' , $data2[5]);
            $tagexp2 = explode( '|' , $data2[6]);
            $tagexp2 = array_filter($tagexp2);

            foreach($catexp2 as $category){
                 $category = trim($category);

                $categoryRow = DB::connection($this->connection)->table('postcategories')->where('name','=', $category)->first();
                $categoryId = $categoryRow->id;
                DB::connection($this->connection)->table('posts_postcategories')->insert(['posts_id' => $lastInsertedId, 'postcategory_id' => $categoryId ]);
            }

            foreach($tagexp2 as $tag){
                if(!empty($tagexp2)){
                $tag = trim($tag);
                   if($tag != ''){
                   $tagRow = DB::connection($this->connection)->table('posttags')->where('name','=', $tag)->first();
                   $tagId = $tagRow->id;
                    DB::connection($this->connection)->table('posts_posttags')->insert([ 'posts_id' => $lastInsertedId,'posts_posttag_id'   => $tagId ]);
                }
                }
            }


        }

    }

    public function getTagId($tagname){
        return $tags = DB::connection($this->connection)->table('tags')
            ->where('name', $tagname)
            ->get();
    }

    public function getCategoryId($category)
    {
        return $tags = DB::connection($this->connection)->table('categories')
            ->where('name', $category)
            ->get();
    }

    public function getsimpleProducts($file)
    {


        $file = fopen("csv/".$this->connection."/".$file.".csv","r") or die(print_r(error_get_last(),true)) ;
        $main = array();
        while(! feof($file))
        {
            $main[] = fgetcsv($file);
        }

        $main = array_filter($main);
        $data = array();

        $all_arr = array();

            foreach ($main as $key => $value) {
                $i = 0;
                foreach ($value as $k => $v) {

                    //echo $k . ' ----- ' . $v . ' <br>';
                    $dk = strtolower(preg_replace("/[^a-z0-9_-]+/i", "", str_replace(' ', '-', $main[0][$i])));

                    $data[$dk] = $v;
                    $i++;
                }


                $all_arr[] = $data;

            }


        unset($all_arr[0]);

        fclose($file);
        return $all_arr;

    }

    public function getProductTags($file){


        $all_arr = $this->getsimpleProducts($file);
        //echo "<pre>";
        //print_r($all_arr);

        $i = 0 ;
        foreach($all_arr as $key => $product)
        {
            /*if($i==0) continue;
            $i++;*/
            foreach($product as $column => $value)
            {

                if($column == 'tag' && $value!='')
                {
                    echo $product['product-id'];
                    echo '<br>';
                    echo $value;
                    echo '<br>';
                    $ptags  = explode('|', $value);
                    foreach($ptags as $stag)
                    {


                        $t = $this->getTagId($stag);
                        if(empty($t))
                        { continue; }

                        $tagId = $t[0]->id;
                        echo 'ID: '.$tagId;
                        echo "<br>";

                        DB::connection($this->connection)->table('product_tag')->insert([
                            [
                                'product_id' => $product['product-id'],
                                'tag_id' => $tagId

                            ]
                        ]);

                    }
                    echo '<br>';
                }
            }
        }

    }

    public function getProductCategories($file){


        $all_arr = $this->getsimpleProducts($file);

        $i = 0 ;
        foreach($all_arr as $key => $product)
        {
            /*if($i==0) continue;
            $i++;*/
            foreach($product as $column => $value)
            {

                if($column == 'category' && $value!='')
                {
                    echo $product['product-id'];
                    echo '<br>';
                    echo $value;
                    echo '<br>';
                    $pcats  = explode('|', str_replace('>', '|', $value));
                    $pcats = array_unique($pcats);

                    foreach($pcats as $scat)
                    {
                        echo $scat;
                        $t = $this->getCategoryId($scat);
                        //print_r($t);
                        if(empty($t))
                        { continue; }
                        //print_r($t);
                        //exit;
                        $catId = $t[0]->id;
                        echo 'ID: '.$catId;
                        echo "<br>";
                        // exit;

                        DB::connection($this->connection)->table('category_product')->insert([
                            [
                                'product_id' => $product['product-id'],
                                'category_id' => $catId

                            ]
                        ]);

                    }
                    echo '<br>';
                }

            }
        }

    }

    public  function putSimpleProducts( $file = '' , $products = '')
    {
        if($file!='')
        {
            $products = $this->getsimpleProducts($file);
        }elseif($products!=''){
            $products = $products;
        }else{
            echo 'no simple products available to insert';
        }

        /*foreach($products as $product) {
            DB::connection($this->connection)->table('products')->where('id', $product['product-id'])->update([ 'description' => $product['excerpt'] ]);
        }
        exit;*/

        foreach($products as $product) {
            //dump($product);

            echo 'insert: simple product: '. $product['product-id']. ' '. $product['product-name'];
            echo '<br>';
            echo '<br>';

            $sale_price = $product['sale-price'];
            if($sale_price == '' || $sale_price == 0)
            {
                $sale_price = 'NULL';
            }
            if($this->ifExist($product['product-id']) == true)
            {
                continue;
            }
            //dump($product['product-gallery']);
            $path = explode('uploads/', $product['featured-image']);

            $featured_image = end($path);
            $feature_image_id = $this->saveFeatureImage($product['featured-image']);
            //$feature_image_id = 1;
            echo 'sku is '.$product['product-sku'];
            $sku = preg_replace('/\D/', '', $product['product-sku']);
            echo 'new sku is ' . $sku;
            if($product['quantity'] == 0)
            {
                $stock_status = 'out';
            }else{
                $stock_status = 'in';
            }

            $product_status = strtolower($product['product-status']);
            if($product_status == 'trash')
            {
                $product_status = 'deleted';
            }
            
                DB::connection($this->connection)->table('products')->insert([
                    [
                        'id' => $product['product-id'],
                        'name' => $product['product-name'],
                        'sale_price' => $sale_price,
                        'regular_price' => $product['price'],
                        'sku' => $sku,
                        'slug' => $product['slug'],
                        'description' => $product['excerpt'],
                        'status' => $product_status,
                        'visibility' => 'visible',
                        'featured_image' => $featured_image,
                        'is_featured' => strtolower($product['featured']),
                        'sale_from' => $product['sale-price-dates-from'],
                        'sale_to' => $product['sale-price-dates-to'],
                        'product_type' => 'simple',
                        //'user_id' => Auth::user()->id,
                        'user_id' => 1,
                        'created_at' => date('Y-m-d H:i:s', strtotime(str_replace('/','-',$product['product-published']))),
                        'published_at' => date('Y-m-d H:i:s', strtotime(str_replace('/','-',$product['product-published']))),
                        'updated_at' => date('Y-m-d H:i:s', strtotime(str_replace('/','-',$product['product-modified']))),
                        'featured_image_id' => $feature_image_id,
                        'stock_status' => $stock_status
                    ]
                ]);





            $product_id = DB::connection($this->connection)->getPdo()->lastInsertId();
            ////////////////////////SEO Section/////////////////////
            $product_id = DB::connection($this->connection)->getPdo()->lastInsertId();
            $page_id     = $product_id;
            $seo_title   = $product['wordpress-seo---seo-title'];
            $seo_desc    = $product['wordpress-seo---meta-description'];
            $can_url = url('/')."/product/".$product['slug']."/";

            $dataSeo = array(
                'page_id'       => $page_id,
                'title'			=> $seo_title,
                'description' 	=> $seo_desc,
                'is_index'        	=> '1',
                'is_follow'		=> '1',
                'canonical_url'		=> $can_url,
                'redirect'   	=> '',
                'type'          => 'product');
            SeoData::saveSeoDetails($dataSeo);
            ///////////////////////product gallery////////////////

            $this->addProductGalleryImages($product_id , $product['product-gallery']);
            //////////////////////////////////////////////////////
            $data = array(
                'name' 	        => '',
                'description'   => '',
                'parent_id'     => $product_id,
                'type'          => 'details'
            );
            DB::connection($this->connection)->table('tabs')->insert($data);
            //Tabs::saveTab($data);

            echo 'insert: Inventory: '. $product['product-id']. ' '. $product['product-name'];
            echo '<br>';
            echo '<br>';
            if($product['manage-stock']=='Yes') {
                $manage_stock = 'yes';
            }else{
                $manage_stock = 'no';
            }


            if($this->ifStockExist($sku) == false) {

                DB::connection(MASTER_CONNECTION)->table('inventories')->insert([
                    [
                        'manage_stock' => $manage_stock,
                        'stock_status' => $stock_status,
                        'stock_qty' => $product['quantity'],
                        'allow_back_orders' => $product['allow-backorders'],
                        'sold_individually' => $product['sold-individually'],
                        'product_sku' => $sku
                    ]
                ]);

                if ($product['up-sells'] != '' || $product['cross-sells'] != '') {
                    echo 'insert: Linked Products: ' . $product['product-id'] . ' ' . $product['product-name'];
                    echo '<br>';
                    echo '<br>';
                    DB::connection($this->connection)->table('linked_products')->insert([
                        [
                            'up_sells' => $product['up-sells'],
                            'cross_sells' => $product['cross-sells'],
                            'product_id' => $product_id
                        ]
                    ]);
                }
            }

        }


    }

    public function importVariableProducts($file){
        $data = $this->getsimpleProducts($file);

        $parent_products        = array();
        $simple_products        = array();
        $composite_products     = array();
        $bto_products           = array();

        //dd($data);

        foreach($data as $product)
        {

            if($product['type'] == 'Variabel' )
            {
                $parent_products[] = $product;
            }

            if($product['type'] == 'Simple Product' )
            {
                $simple_products[] = $product;
            }

            if($product['type'] == 'composite' ||  $product['type'] == 'bto')
            {
                $composite_products[] = $product;
            }

            if($product['type'] == 'bto')
            {
                $bto_products[] = $product;
            }



        }


        //dd($bto_products);

        // save composite products
        if(!empty($composite_products))
        {
            foreach($composite_products as $comp_product)

                $this->saveProductToDb($comp_product, 'composite', 0);
        }

        if(!empty($bto_products))
        {
            foreach($bto_products as $comp_product)

                $this->saveProductToDb($comp_product, 'composite', 0);
        }


        // save simple products
        if(!empty($simple_products))
        {
            $this->putSimpleProducts('', $simple_products);
        }


        // save variable products and variations
        foreach($parent_products as $parent_product)
        {
            //dump($parent_product);
            $att_as_variation = unserialize($parent_product['use-as-variation']);
            //dd($att_as_variation);

            if($att_as_variation) {

                foreach ($att_as_variation as $key => $att_var) {
                    if ($att_var == 1) {
                        $att_as_variation_new[$key] = 'yes';
                    } else {
                        $att_as_variation_new[$key] = 'no';
                    }

                }
            } 

            // save variable product main entry
            $this->saveProductToDb($parent_product, 'variable', 0);
            $variations = array();

            foreach($data as $p)
            {

                //dump($p);
                if($p['product_parent'] == $parent_product['product-id'])
                {
                    $var_atts = array();
                    // dump($p);
                    $variations[$parent_product['product-id']][] = $p;
                    // save product variation
                    $this->saveProductToDb($p, 'variation', $parent_product['product-id']);

                    if($p['attribute-pa-maat'] != '')
                    {
                        $var_atts[1] = $p['attribute-pa-maat'];
                    }
                    if($p['attribute-pa-schoenmaat'] != '')
                    {
                        $var_atts[2] =  $p['attribute-pa-schoenmaat'];
                    }

                    if($p['attribute-pa-maat-combinatie'] != '')
                    {
                        $var_atts[3] = $p['attribute-pa-maat-combinatie'];
                    }

                    if($p['attribute-pa-kleur'] != '')
                    {
                        $var_atts[4] = $p['attribute-pa-kleur'];
                    }

                    if(!empty($var_atts))
                    {
                        //echo 'variation attributes';
                        //echo $p['product-id'];
                        //dump($var_atts);
                        echo 'variation attributes end';
                        DB::connection($this->connection)->table('attributes')->insert([
                            [
                                'attributes' => serialize($var_atts),
                                'product_id' => $p['product-id']
                            ]
                        ]);

                    }

                }
            }

            //dump($variations);
            // save attributes
            $atts = array();
            foreach($variations as $parentid => $variation)
            {

                $size = array();
                if ($variation[0]['attribute-pa-maat'] != '') {
                    //$var_atts[] = 'Size';
                    for($i = 0 ; $i <= count($variation) ; $i++)
                    {
                        if(isset($variation[$i]))
                        {
                            $atts[1][] = $variation[$i]['attribute-pa-maat'];
                            $size[] = $variation[$i]['attribute-pa-maat'];

                        }

                    }

                }

                if ($variation[0]['attribute-pa-schoenmaat'] != '') {
                    //$var_atts[] = 'Shoe Size';
                    for($i = 0 ; $i <= count($variation) ; $i++)
                    {
                        if(isset($variation[$i]))
                        {
                            $atts[2][] = $variation[$i]['attribute-pa-schoenmaat'];
                        }

                    }

                }

                if ($variation[0]['attribute-pa-maat-combinatie'] != '') {
                    //$var_atts[] = 'Shoe Combination';
                    for($i = 0 ; $i <= count($variation) ; $i++)
                    {
                        if(isset($variation[$i]))
                        {
                            $atts[3][] = $variation[$i]['attribute-pa-maat-combinatie'];
                        }

                    }

                }
                if ($variation[0]['attribute-pa-kleur'] != '') {
                    //$var_atts[] = 'Color';
                    for($i = 0 ; $i <= count($variation) ; $i++)
                    {
                        if(isset($variation[$i]))
                        {
                            $atts[4][] = $variation[$i]['attribute-pa-kleur'];
                        }

                    }

                }


            }






            if($parent_product['product-id'] == '58529')
            {

                //dump($atts);
            }
            echo 'ahmad ali';
            dump($atts);
            if(!empty($atts))
            {
                DB::connection($this->connection)->table('attributes')->insert([
                    [
                        'attributes' => serialize($atts),
                        'product_id' => $parent_product['product-id']
                    ]
                ]);
            }




        }



    }

    public function saveProductToDb($product, $type, $parentId){
        //dump($product);
        //dump($product['product-gallery']);       //dd($this->ifExist($product['product-id']));
        if($this->ifExist($product['product-id']) == true)
        {
            return ;
        }

        if($type=='variable')
        {
            echo 'insert: Variable product: '. $product['product-id']. ' '. $product['product-name'];
        }elseif($type=='variation'){
            echo 'insert: Variation: '. $product['product-id']. ' '. $product['product-name'];
        }elseif($type=='composite'){
            echo 'insert: Composite product: '. $product['product-id']. ' '. $product['product-name'];
        }
        echo '<br>';
        echo '<br>';

        $path = explode('uploads/', $product['featured-image']);

        $featured_image = end($path);
        $feature_image_id = $this->saveFeatureImage($product['featured-image']);
        //$feature_image_id = 1;
        $sale_price = $product['sale-price'];
        if($sale_price == '' || $sale_price == 0)
        {
            $sale_price = 'NULL';
        }

        if(isset($product['exp-date-del']) && $product['exp-date-del'] != '' && $product['exp-date-del']!='Array')
        {
            $expected_date_delivery = date('Y-m-d', strtotime($product['exp-date-del']));
        }else{
            $expected_date_delivery = '';
        }

        echo 'data is: '.$product['product-published'].' new is:'.date('Y-m-d H:i:s', strtotime(str_replace('/','-',$product['product-published'])));
        echo 'sku is '.$product['product-sku'];
        $sku = preg_replace('/\D/', '', $product['product-sku']);
        echo 'new sku is ' . $sku;
        echo $type;

        if($product['quantity'] == 0)
        {
            $stock_status = 'out';
        }else{
            $stock_status = 'in';
        }

        $product_status = strtolower($product['product-status']);
        if($product_status == 'trash')
        {
            $product_status = 'deleted';
        }

        if($type == 'bto')
        {
            echo "its bto<br>";
            $type = 'composite';
        }

        DB::connection($this->connection)->table('products')->insert([
            [
                'id' => $product['product-id'],
                'parent_id' => $parentId,
                'name' => $product['product-name'],
                'sale_price' => $sale_price,
                'regular_price' => $product['price'],
                'sku' => $sku,
                'slug' => $product['slug'],
                'seo_title' => $product['wordpress-seo---seo-title'],
                'meta_description' => $product['wordpress-seo---meta-description'],
                'description' => $product['excerpt'],
                'featured_image' => $featured_image,
                'status' => $product_status ,
                'visibility' => 'visible',
                'is_featured' => strtolower($product['featured']),
                'sale_from' => $product['sale-price-dates-from'],
                'sale_to' => $product['sale-price-dates-to'],
                'product_type' => $type,
                //'user_id' => Auth::user()->id,
                'user_id' => 1,
                'published_at' => date('Y-m-d H:i:s', strtotime(str_replace('/','-',$product['product-published']))),
                'created_at' => date('Y-m-d H:i:s', strtotime(str_replace('/','-',$product['product-published']))),
                'updated_at' => date('Y-m-d H:i:s', strtotime(str_replace('/','-',$product['product-modified']))),
                'expected_date_of_delivery' => $expected_date_delivery,
                'featured_image_id' => $feature_image_id,
                'stock_status' => $stock_status
            ]
        ]);
        ///////////////////for product gallery//////////////
        $product_id = DB::connection($this->connection)->getPdo()->lastInsertId();
        $page_id     = $product_id;
        $seo_title   = $product['wordpress-seo---seo-title'];
        $seo_desc    = $product['wordpress-seo---meta-description'];
        $can_url = url('/')."/product/".$product['slug']."/";

        $dataSeo = array(
            'page_id'       => $page_id,
            'title'			=> $seo_title,
            'description' 	=> $seo_desc,
            'is_index'        	=> '1',
            'is_follow'		=> '1',
            'canonical_url'		=> $can_url,
            'redirect'   	=> '',
            'type'          => 'product');
        SeoData::saveSeoDetails($dataSeo);

        $this->addProductGalleryImages($product_id , $product['product-gallery']);
        ///////////////////////////////////////

        $data = array(
            'name' 	        => '',
            'description'   => '',
            'parent_id'     => $product['product-id'],
            'type'          => 'details'
        );
        DB::connection($this->connection)->table('tabs')->insert($data);
        //Tabs::saveTab($data);


        //insert product meta
        if(!empty(unserialize($product['woocommerce-waitlist'])))
        {
            DB::connection($this->connection)->table('metas')->insert([
                [
                    'meta_name'      => 'woocommerce_waitlist',
                    'meta_value'    => $product['woocommerce-waitlist'],
                    'product_id'   => $product['product-id']
                ]
            ]);
        }


        $color_swatches = unserialize($product['ce-colors-swatches-prod']);
        $new_color_swatches = array();
        if(!empty($color_swatches))
        {
            echo 'color swatches';
            dump($color_swatches);
            echo 'end ';
            foreach ($color_swatches as $color_swatch)
            {
                $pid = $this->products->getProductIdByName($color_swatch);
                if($pid)
                {
                    $new_color_swatches[] = $pid->id ;
                }

            }

            DB::connection($this->connection)->table('metas')->insert([
                [
                    'meta_name'      => 'ce_colors_swatches_prod',
                    'meta_value'    => serialize($new_color_swatches),
                    'product_id'   => $product['product-id']
                ]
            ]);
        }

        if(!empty(unserialize($product['ce-colors-entry'])))
        {
            DB::connection($this->connection)->table('metas')->insert([
                [
                    'meta_name'      => 'ce_colors_entry',
                    'meta_value'    => $product['ce-colors-entry'],
                    'product_id'   => $product['product-id']
                ]
            ]);
        }


        if($product['manage-stock']=='Yes') {
            $manage_stock = 'yes';
        }else{
            $manage_stock = 'no';
        }

        if($this->ifStockExist($sku) == false) {

            echo 'insert: Variable product inventory: ' . $product['product-id'] . ' ' . $product['product-name'];
            echo '<br>';
            echo '<br>';
            echo '<br>';
            DB::connection(MASTER_CONNECTION)->table('inventories')->insert([
                [
                    'manage_stock' => $manage_stock,
                    'stock_status' => $stock_status,
                    'stock_qty' => $product['quantity'],
                    'allow_back_orders' => $product['allow-backorders'],
                    'sold_individually' => $product['sold-individually'],
                    'product_sku' => $sku
                ]
            ]);
        }



        if($product['up-sells']!='' || $product['cross-sells']!='')
        {
            echo 'insert: Variable product linked products: '. $product['product-id']. ' '. $product['product-name'];
            echo '<br>';
            echo '<br>';
            DB::connection($this->connection)->table('linked_products')->insert([
                [
                    'up_sells' => $product['up-sells'],
                    'cross_sells' => $product['cross-sells'],
                    'product_id' => $product['product-id']
                ]
            ]);
        }


        $composite_data = unserialize(base64_decode($product['composite-product-data']));


        if(($type=='composite' ||  $type=='bto') && !empty($composite_data))
        {

            foreach($composite_data as $composite) {

                echo 'insert: Composite Products Data: ' . $product['product-id'] . ' ' . $product['product-name'];
                echo '<br>';
                echo '<br>';
                DB::connection($this->connection)->table('components')->insert([
                    [
                        'title'         => $composite['title'],
                        'description'   => $composite['description'],
                        'position'      => $composite['position'],
                        'product_id'    => $product['product-id'],
                        'default_id'    => $composite['default_id']
                    ]
                ]);
            }
        }

    }
    // import all product images
    public function importImages($file){
        $data = $this->getsimpleProducts($file);

        $i = 0 ;
        foreach($data as $product)
        {

            $gallery = explode('|', $product['product-gallery']);
            foreach($gallery as $image)
            {

                $path = explode('uploads/', $image);
                $image = end($path);

                if($image == '')
                { continue; }
                $type           = pathinfo($image, PATHINFO_EXTENSION);
                //$size           = filesize($image);
                $size           = 0 ;
                $alt_text       = $product['product-name'];
                $description    = $product['product-name'];
                $parent_id      = $product['product-id'];
                $media_type     = 'product';

                DB::connection($this->connection)->table('media')->insert([
                    [
                        'path'          => $image,
                        'type'          => $type,
                        'size'          => $size,
                        'alt_text'      => $alt_text,
                        'description'   => $description,
                        'parent_id'     => $parent_id,
                        'media_type'    => $media_type

                    ]
                ]);
            }

        }
    }

    public function ifExist($id)
    {
        $count = DB::connection($this->connection)->table('products')->where('products.id', '=', $id)->count();

        if( $count > 0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function ifStockExist($sku = false)
    {
        if($sku)
        {
            $count = DB::connection(MASTER_CONNECTION)->table('inventories')->where('product_sku', '=', $sku)->count();
            if( $count > 0)
            {
                return true;
            }else{
                return false;
            }
        }

        return false;
    }

    public function saveFeatureImage($imageinfo){
        if(!empty($imageinfo)){
            echo "--featured image path--".$imageinfo;
            echo $checkRemoteFile = $this->checkRemoteURL($imageinfo);
            if($checkRemoteFile == 200 ) {
                $path = explode('uploads/', $imageinfo);
                $featured_image_path_with_directory = end($path);

                $explode_directory = explode('/', $featured_image_path_with_directory);
                echo "<br>";
                $year_folder = $explode_directory[0];
                echo "<br>";
                $month_folder = $explode_directory[1];
                echo "<br>";
                $path_parts = pathinfo($featured_image_path_with_directory);
                $directory = $path_parts['dirname'];

                $filename_with_extension = basename($featured_image_path_with_directory);
                echo "<br>";
                $file_ext = pathinfo($filename_with_extension, PATHINFO_EXTENSION);
                echo "<br>";
                $filename_without_extension = basename($featured_image_path_with_directory, "." . $file_ext);
                $clean_filename =  Functions::clean_imageTitle($filename_without_extension);
                $new_filename = $clean_filename . "." . $file_ext;
                $destinationDirectory = "uploads/" . $this->connection . "/" . $directory;
                $destinationPath = "uploads/" . $this->connection . "/" . $directory . "/";
                $insertPath = $destinationPath . $new_filename;


                if (!is_dir($destinationDirectory)) {
                    mkdir($destinationDirectory, 0777, true);
                }
                //////////////copy file if not exists////////////////////
                if (!file_exists($insertPath)) {
                    copy($imageinfo, $insertPath);
                }
                list($width, $height) = getimagesize($insertPath);
                $dimension = $width . " x " . $height;
                $file_size_bytes = filesize($insertPath);
                $file_size = $this->formatSizeUnits($file_size_bytes);
                if (file_exists($insertPath)) {
                    if ($file_ext != 'png') {
                        $thumbnails = Config::get('domain-thumbnails');
                        $connection = $this->connection;

                        foreach ($thumbnails[$connection] as $connection_thumbnail) {
                            $width = $connection_thumbnail['width'];
                            $height = $connection_thumbnail['height'];

                            $image_resize = Functions::ResizeImages($width, $height, $insertPath, $clean_filename, $file_ext, $destinationPath);
                        }


                    }
                }
                $data = array('path' => $insertPath, 'alt_text' => $clean_filename, 'alt_text' => $clean_filename, 'uploaded_by' => Auth::id(),
                    'image_dimension' => $dimension, 'size' => $file_size, 'uploaded_on' => date('Y-m-d'), 'type' => $file_ext);
                DB::connection($this->connection)->table('media')->insert($data);
                return $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
            }
      }



    }

    public function addProductGalleryImages($product_id , $product_gallery){
        //TRUNCATE TABLE media; TRUNCATE TABLE products;TRUNCATE TABLE media_products
      if(!empty($product_gallery) ){
          $product_gallery = trim($product_gallery);
        $gallery_img_exp = explode('|',$product_gallery );
          $gallery_img_exp = array_filter($gallery_img_exp);
        foreach($gallery_img_exp as $image_domain_path){
            //////////////copy file if not exists////////////////////
           // $url = 'http://myfashionmusthaves.com/wp-content/uploads/2014/11/Blazer-met-slangen-print.jpg';
            echo $checkRemoteFile = $this->checkRemoteURL($image_domain_path);
            if($checkRemoteFile == 200 ){

            echo '----image domain path---'.$image_domain_path;

            $upload_path_ext = explode('uploads', $image_domain_path);
            $get_upload_path = end($upload_path_ext);
            $get_upload_path = trim($get_upload_path);
            $path_parts = pathinfo($get_upload_path);
            $directory = $path_parts['dirname'];
            $directory_full_path = 'uploads/'.$this->connection.$directory;
            $filename = basename($get_upload_path);
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
            $filename_without_extension = basename($get_upload_path, "." . $file_ext);
            $clean_filename =  Functions::clean_imageTitle($filename_without_extension);
            $new_filename = $clean_filename.".".$file_ext;
            $existedupload = $clean_filename."-thumbnail.".$file_ext;
            $get_clean_upload_path = $directory_full_path."/".$new_filename;


            ////////////make directory if not exists/////////////////
           $file_destination_path = $get_clean_upload_path;
            if (!is_dir($directory_full_path)) {
                mkdir($directory_full_path, 0777, true);
            }
             if (!file_exists($file_destination_path)) {
                 copy($image_domain_path, $file_destination_path);
             }

            list($width, $height) = getimagesize($file_destination_path);


            $dimension = $width." x ".$height;
            $source_aspect_ratio = $width / $height;

            $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
            if ($width <= THUMBNAIL_IMAGE_MAX_WIDTH && $height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
                $thumbnail_image_width = $width;
                $thumbnail_image_height = $height;
            } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
                $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
                $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
            } else {
                $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
                $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
            }
            
            if(file_exists($directory_full_path . "/" .$existedupload)){
                unlink($directory_full_path . "/" .$existedupload);
            }
                $thumbnails = Config::get('domain-thumbnails');
                $connection = $this->connection;

                foreach ($thumbnails[$connection] as $connection_thumbnail) {
                    $width = $connection_thumbnail['width'];
                    $height = $connection_thumbnail['height'];

                    $image_resize = Functions::ResizeImagesThumbnail($width, $height, $file_destination_path, $clean_filename, $file_ext, $directory_full_path."/");


                }


            $file_size_bytes = filesize($file_destination_path);
            $file_size =  $this->formatSizeUnits($file_size_bytes);


            $dimension = $width." x ".$height;

            $data_insert = array('path' => $file_destination_path,'title' => $clean_filename, 'alt_text' => $clean_filename, 'uploaded_by' =>  Auth::id(),
                'image_dimension' => $dimension , 'size' => $file_size , 'uploaded_on' => date('Y-m-d'),'type' => $file_ext);

            DB::connection($this->connection)->table('media')->insert($data_insert);
            $lastInsertedMediaId = DB::connection($this->connection)->getPdo()->lastInsertId();

            DB::connection($this->connection)->table('media_products')->insert(['media_id' => $lastInsertedMediaId, 'product_id'=> $product_id]);
             }

         }

        }


    }

    public function  formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = round(number_format($bytes / 1073741824, 2)) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = round(number_format($bytes / 1048576, 2)) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = round(number_format($bytes / 1024, 2)) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function checkRemoteURL($url){
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // $retcode >= 400 -> not found, $retcode = 200, found.
    curl_close($ch);
    return $retcode;
    }

    public function setSKU($connection)
    {

        $file = fopen("csv/".$connection."/products.csv","r");
        $main = array();
        while(! feof($file))
        {
            $main[] = fgetcsv($file);
        }


        $main = array_filter($main);

        $data = array();

        $all_arr = array();
        foreach($main as $key => $value)
        {
            $i = 0 ;
            foreach($value as $k => $v)
            {

                $dk = strtolower(preg_replace("/[^a-z0-9_-]+/i", "", str_replace(' ', '-', $main[0][$i])));

                $data[$dk] = $v;
                $i++;
            }

            $all_arr[] = $data;

        }


        //dd($all_arr);
        unset($all_arr[0]);

        $products = $all_arr;



        foreach ($products as $product)
        {

            echo 'insert: Inventory: '. $product['product-id']. ' '. $product['product-name'];
            echo '<br>';
            echo '<br>';
            if($product['manage-stock']=='Yes') {
                $manage_stock = 'yes';
            }else{
                $manage_stock = 'no';
            }

            if($product['quantity'] == 0)
            {
                $stock_status = 'out';
            }else{
                $stock_status = 'in';
            }

            $found = Inventories::findBySKU($product['product-sku']) ;

            if($product['product-sku']!='' && $product['product-sku']!=0 && $found===false) {

                DB::connection('eagleeye')->table('inventories')->insert([
                    [
                        'manage_stock' => $manage_stock,
                        'stock_status' => $stock_status,
                        'stock_qty' => $product['quantity'],
                        'allow_back_orders' => $product['allow-backorders'],
                        'sold_individually' => $product['sold-individually'],
                        'product_sku' => $product['product-sku'],
                    ]
                ]);
            }
        }

    }

    public function impoertProductSeoDetailDb(){
        ini_set('max_execution_time', 0);

        $products = Products::get();

        foreach($products as $product){

            $page_id     = $product->id;
            $seo_title   = $product->seo_title;
            $seo_desc    = $product->meta_description;
            $can_url = url('/')."/product/".$product->slug."/";

            $dataSeo = array(
                'page_id'       => $page_id,
                'title'			=> $seo_title,
                'description' 	=> $seo_desc,
                'is_index'        	=> '1',
                'is_follow'		=> '1',
                'canonical_url'		=> $can_url,
                'redirect'   	=> '',
                'type'          => 'product');
            SeoData::saveSeoDetails($dataSeo);


        }
        echo "Done!";

    }

/*    public function create_post_xml()
    {

        $file = 'post_xml.xml';
        $getPosts = Posts::get();
        $post_array = array();
        foreach($getPosts as $post){
            array_push($post_array , [ 'id' => $post->id, 'slug' => $post->slug,  'updated_at' => $post->updated_at ]);
        }

        $post_chunks =   array_chunk($post_array, 1000);
        $sitemp_count = 0;
        $type = 'post';
        $count_db = DB::connection($this->connection)->table('sitemap_index')->where('type' , '=' , $type)->count();
        if($count_db >= 1){
            DB::connection($this->connection)->table('sitemap_index')->where('type' , '=' , $type)->delete();
        }
        foreach($post_chunks as $posts){

                $xml = '<?xml version="1.0" encoding="UTF-8"?>';
                $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach($posts as $post){

                $xml .= ' <url>';
                $xml .= '   <loc>'.url('/').'/post/'.$post['slug'].'/</loc>';
                $xml .= '   <lastmod>'.$post['updated_at'].'</lastmod>';
                $xml .= '   <changefreq>weekly</changefreq>';
                $xml .= '   <priority>0.6</priority>';
                $xml .= ' </url>';
            }
            $xml .= '</urlset></xml>';
            if($sitemp_count == 0){
                $sm = '';
                $sitemp_count++;
            }
            else{
                $sm = $sitemp_count;
                $sitemp_count++;
            }
            $file = 'post-sitemap'.$sm.'.xml';

            $fp = fopen('xml/'.$file,"wb");
            fwrite($fp,$xml);
            fclose($fp);
            $sitemap_index = url('/').$file;

            DB::connection($this->connection)->table('sitemap_index')->insert([
                [
                    'sitemap' => $sitemap_index,
                    'last_modified' =>  date('Y-m-d H:i:s'),
                    'type'          =>  $type
                ]
            ]);

        }
    }

    public function create_product_xml(){

        $getProducts = Products::get();
        $product_array = array();
        foreach($getProducts as $product){
            array_push($product_array , [ 'id' => $product->id, 'slug' => $product->slug,  'updated_at' => $product->updated_at ]);
        }

        $product_chunks =   array_chunk($product_array, 1000);
        $sitemp_count = 0;
        $type = 'product';
        $count_db = DB::connection($this->connection)->table('sitemap_index')->where('type' , '=' , $type)->count();

        if($count_db >= 1){
            DB::connection($this->connection)->table('sitemap_index')->where('type' , '=' , $type)->delete();
        }

        foreach($product_chunks as $products){

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach($products as $product){

                $xml .= ' <url>';
                $xml .= '   <loc>'.url('/').'/product/'.$product['slug'].'/</loc>';
                $xml .= '   <lastmod>'.$product['updated_at'].'</lastmod>';
                $xml .= '   <changefreq>weekly</changefreq>';
                $xml .= '   <priority>0.6</priority>';
                $xml .= ' </url>';
            }
            $xml .= '</urlset></xml>';
            if($sitemp_count == 0){
                $sm = '';
                $sitemp_count++;
            }
            else{
                $sm = $sitemp_count;
                $sitemp_count++;
            }
            $file = 'product-sitemap'.$sm.'.xml';

            $fp = fopen('xml/'.$file,"wb");
            fwrite($fp,$xml);
            fclose($fp);
            $sitemap_index = url('/').$file;

            DB::connection($this->connection)->table('sitemap_index')->insert([
                [
                    'sitemap' => $sitemap_index,
                    'last_modified' =>  date('Y-m-d H:i:s'),
                    'type'          =>  $type
                ]
            ]);

        }
    }*/

    public function create_all_xml(){

        $save_pproduct_xml = $this->create_xml($type = 'product');
        $save_post_xml = $this->create_xml($type = 'post');
        $save_pages_xml = $this->create_xml($type = 'page');
        $save_product_category_xml = $this->create_xml($type = 'product_cat');
        $save_post_category_xml = $this->create_xml($type = 'post_cat');
        $save_product_tag_xml = $this->create_xml($type = 'product_tag');
        $save_post_tag_xml = $this->create_xml($type = 'post_tag');
        $save_post_tag_xml = $this->create_xml($type = 'attributes');


        $getitems = Sitemap::get();
        $sitemap_array = array();
        foreach($getitems as $item){
            array_push($sitemap_array , [ 'id' => $item->id, 'sitemap' => $item->sitemap,  'last_modified' => $item->last_modified ]);
        }
        $item_chunks =   array_chunk($sitemap_array, 1000);

        foreach($item_chunks as $items){

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach($items as $item){

                $xml .= ' <sitemap>';
                $xml .= '   <loc>'.$item['sitemap'].'/</loc>';
                $xml .= '   <lastmod>'.$item['last_modified'].'</lastmod>';
                $xml .= ' </sitemap>';
            }
            $xml .= '</sitemapindex>';

            $file = 'sitemap_index.xml';

            $fp = fopen('xml/'.$file,"wb");
            fwrite($fp,$xml);
            fclose($fp);

        }
        echo "No Errors Found,It's Done !!";
    }

    public function create_xml($type){
        if($type == 'page'){
            $getitems = Posts::where('type' , '=' , 'page')->get();
        }
        else if($type == 'post'){
            $getitems = Posts::where('type' , '=' , 'post')->get();
        }
        else if($type == 'product'){
            $getitems = Products::where('product_type' , '!=' , 'variation')->get();
        }
        else if($type == 'product_cat'){
            $getitems = Categories::get();
        }
        else if($type == 'post_cat'){
            $getitems = PostCategories::get();
        }
        else if($type == 'product_tag'){
            $getitems = Tags::get();
        }
        else if($type == 'post_tag'){
            $getitems = PostTags::get();
        }
        else if($type == 'attributes'){
            $getitems = Attributes::get();
        }


        $items_array = array();
        foreach($getitems as $item){
            array_push($items_array , [ 'id' => $item->id, 'slug' => $item->slug,  'updated_at' => $item->updated_at ]);
        }
        $item_chunks =   array_chunk($items_array, 1000);
        $sitemp_count = 0;
        $count_db = DB::connection($this->connection)->table('sitemap_index')->where('type' , '=' , $type)->count();

        if($count_db >= 1){
            DB::connection($this->connection)->table('sitemap_index')->where('type' , '=' , $type)->delete();
        }
        $thumbnails = Config::get('domain-thumbnails');
        $connection = $this->connection;
        foreach($item_chunks as $items){

            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach($items as $item){

                $xml .= ' <url>';
                $xml .= '   <loc>'.url('/').'/'.$type.'/'.$item['slug'].'/</loc>';
                $xml .= '   <lastmod>'.$item['updated_at'].'</lastmod>';
                $xml .= '   <changefreq>weekly</changefreq>';
                $xml .= '   <priority>0.6</priority>';
                $xml .= ' </url>';
            }
            $xml .= '</urlset>';
            if($sitemp_count == 0){
                $sm = '';
                $sitemp_count++;
            }
            else{
                $sm = $sitemp_count;
                $sitemp_count++;
            }
            $file = $type.'-sitemap'.$sm.'.xml';

            $fp = fopen('xml/'.$file,"wb");
            fwrite($fp,$xml);
            fclose($fp);
            $sitemap_index = url('/').'/'.$file;

            DB::connection($this->connection)->table('sitemap_index')->insert([
                [
                    'sitemap' => $sitemap_index,
                    'last_modified' =>  date('Y-m-d H:i:s'),
                    'type'          =>  $type
                ]
            ]);

        }



    }



    public function encryptPasswords()
    {
        $customers = Customer::select('id', 'password')->get();

        if($customers)
        {
            foreach ($customers as $customer)
            {
                echo $customer->password;
                echo '<br>';
                echo $new_pass = bcrypt($customer->password);

                exit;
            }
        }
    }


    public function importOrders()
    {
        $import_history = DB::connection($this->connection)->table('import_history')->where('type', 'orders')->first();
        if($import_history)
        {
            $offset = $import_history->last_offset;
        }else{
            $offset = 0;
        }

        echo 'From :'. $offset;

        //$offset = 0 ;


        $orders = DB::connection('livetmh')
                        ->table('wp_posts')
                        ->where('post_type', 'shop_order')
                        ->orderby('ID', 'ASC')
                        ->offset($offset)
                        ->limit(2000)
                        ->get();

        $i = $offset ;

        if($orders) {

            foreach ($orders as $order) {
               /* echo 'order ID: ' . $order->ID;
                echo '<br>';*/


                /*DB::beginTransaction();
                try {*/

                    $order_date = $order->post_date;
                    $order_db = [
                        'id' => $order->ID,
                        'created_at' => $order->post_date,
                        'updated_at' => $order->post_modified,
                        'amount' => $this->getOrderMeta('_order_total', $order->ID),
                        'total_tax' => $this->getOrderMeta('_order_tax', $order->ID),
                        'discount' => $this->getOrderMeta('_order_discount', $order->ID) + $this->getOrderMeta('_cart_discount', $order->ID),
                        'customer_id' => $this->getOrderMeta('_customer_user ', $order->ID),
                        'shipping_cost' => 0,
                        'status' => str_replace('wc-', '', $order->post_status),
                        'client_details' => $this->getOrderMeta('_customer_ip_address', $order->ID) . '|' . $this->getOrderMeta('_customer_user_agent', $order->ID),
                    ];

                    Orders::firstOrCreate($order_db);

                    $billing_data = [
                        'first_name' => $this->getOrderMeta('_billing_first_name', $order->ID),
                        'last_name' => $this->getOrderMeta('_billing_last_name', $order->ID),
                        'address_1' => $this->getOrderMeta('_billing_address_1', $order->ID),
                        'address_2' => $this->getOrderMeta('_billing_address_2', $order->ID),
                        'city' => $this->getOrderMeta('_billing_city', $order->ID),
                        'email' => $this->getOrderMeta('_billing_email', $order->ID),
                        'phone' => $this->getOrderMeta('_billing_phone', $order->ID),
                        'state' => $this->getOrderMeta('_billing_state', $order->ID),
                        'order_id' => $order->ID,
                        'postcode' => $this->getOrderMeta('_billing_postcode', $order->ID),
                        'country' => $this->getOrderMeta('_billing_country', $order->ID),
                        'created_at' => $order_date
                    ];

                    $shipping_data = [
                        'first_name' => $this->getOrderMeta('_shipping_first_name', $order->ID),
                        'last_name' => $this->getOrderMeta('_shipping_last_name', $order->ID),
                        'address_1' => $this->getOrderMeta('_shipping_address_1', $order->ID),
                        'address_2' => $this->getOrderMeta('_shipping_address_2', $order->ID),
                        'city' => $this->getOrderMeta('_shipping_city', $order->ID),
                        'email' => $this->getOrderMeta('_shipping_email', $order->ID),
                        'phone' => $this->getOrderMeta('_shipping_phone', $order->ID),
                        'state' => $this->getOrderMeta('_shipping_state', $order->ID),
                        'order_id' => $order->ID,
                        'postcode' => $this->getOrderMeta('_shipping_postcode', $order->ID),
                        'country' => $this->getOrderMeta('_shipping_country', $order->ID),
                        'created_at' => $order_date
                    ];

                    OrderBilling::firstOrCreate($billing_data);
                    OrderShipping::firstOrCreate($shipping_data);


                    OrderPayment::firstOrCreate([
                        'payment_method_id' => $this->getOrderMeta('_payment_method', $order->ID),
                        'title' => $this->getOrderMeta('_payment_method_title', $order->ID),
                        'payment_status' => str_replace('wc-', '', $order->post_status),
                        'txn_id' => $this->getOrderMeta('_transaction_id', $order->ID),
                        'order_key' => $this->getOrderMeta('_order_key', $order->ID),
                        'klarna_pno' => $this->getOrderMeta('_klarna_pno', $order->ID),
                        'klarna_invoice_url' => $this->getOrderMeta('_klarna_invoice_url', $order->ID),
                        'inv_no' => $this->getOrderMeta('_klarna_invoice_number', $order->ID),
                        'res_no' => $this->getOrderMeta('_klarna_order_reservation', $order->ID),
                        'completed_at' => $this->getOrderMeta('_completed_date', $order->ID),
                        'order_id' => $order->ID,
                    ]);


                    $order_items = $this->getOrderItems($order->ID);

                    if (!empty($order_items)) {
                        /*echo '<br>';
                        echo 'order Items main';
                        echo '<br>';
                        dump($order_items);*/
                        foreach ($order_items as $order_item) {
                            //$order_item_meta = $this->getOrderItemsMeta($order_item->order_item_id);
                            /* echo '<br>';
                             echo 'order Item meta main';
                             echo '<br>';*/

                            $variation_id  = $this->getOrderItemsMeta('_variation_id', $order_item->order_item_id);
                            if($variation_id == 0)
                            {
                                $order_product_id = $this->getOrderItemsMeta('_product_id', $order_item->order_item_id);
                            }else{
                                $order_product_id = $variation_id;
                            }

                            $item_total = $this->getOrderItemsMeta('_line_total', $order_item->order_item_id);
                            $item_qty = $this->getOrderItemsMeta('_qty', $order_item->order_item_id);
                            if($item_qty !=0)
                            {
                                $item_unit_price =  $item_total / $item_qty;
                            }else{
                                $item_unit_price = '';
                            }



                            OrderItems::firstOrCreate([
                                'order_id' => $order_item->order_id,
                                'product_id' => $order_product_id,
                                'unit_price' => $item_unit_price ,
                                'unit_tax' => $this->getOrderItemsMeta('_line_tax', $order_item->order_item_id),
                                'qty' => $item_qty,
                                'total' => $item_total,
                                'total_tax' => $this->getOrderItemsMeta('_line_subtotal_tax', $order_item->order_item_id),
                            ]);

                        }
                    }


                    $refunds = DB::connection('livetmh')
                        ->table('wp_posts')
                        ->where('post_type', 'shop_order_refund')
                        ->where('post_parent', $order->ID)
                        ->get();

                    //dd($refunds);

                    if ($refunds) {

                        foreach ($refunds as $refund) {
                            $refund_item = $this->getRefundItem($refund->ID);



                            if ($refund_item) {
                                $variation_id  = $this->getOrderItemsMeta('_variation_id', $order_item->order_item_id);
                                if($variation_id == 0)
                                {
                                    $refund_product_id = $this->getOrderItemsMeta('_product_id', $order_item->order_item_id);
                                }else{
                                    $refund_product_id = $variation_id;
                                }
                                $product_id = $refund_product_id;
                                $unit_price = $this->getOrderMeta('_refund_amount', $refund->ID);
                                $unit_tax = $this->getOrderItemsMeta('_line_tax', $refund_item->order_item_id);
                                $qty = $this->getOrderItemsMeta('_qty', $refund_item->order_item_id);
                                $total_tax = $this->getOrderItemsMeta('_line_tax', $refund_item->order_item_id);
                            } else {
                                $product_id = '';
                                $unit_price = '';
                                $unit_tax = '';
                                $qty = '';
                                $total_tax = '';
                            }



                            RefundItems::firstOrCreate([
                                'order_id' => $order->ID,
                                'refund_id' => $refund->ID,
                                'product_id' => $product_id,
                                'unit_price' => $unit_price,
                                'unit_tax' => $unit_tax,
                                'qty' => $qty,
                                'total' => $this->getOrderMeta('_refund_amount', $refund->ID),
                                'total_tax' => $total_tax
                            ]);


                            Refunds::firstOrCreate([
                                'id' => $refund->ID,
                                'order_id' => $refund->post_parent,
                                'created_at' => $refund->post_date,
                                'reason' => $refund->post_excerpt,
                                'amount' => $this->getOrderMeta('_refund_amount', $refund->ID),
                            ]);
                        }


                    }

                    $coupons = DB::connection('livetmh')
                        ->table('wp_woocommerce_order_items')
                        ->where('order_item_type', 'coupon')
                        ->where('order_id', $order->ID)
                        ->get();

                    if ($coupons) {
                        foreach ($coupons as $coupon) {
                            //$coupon_id = Coupons::select('id')->where('code', $coupon->order_item_name)->first();
                            $coupon_id = DB::connection('livetmh')
                                ->table('wp_posts')
                                ->where('post_type', 'shop_coupon')
                                ->where('post_title', $coupon->order_item_name)
                                ->first();
                            if ($coupon_id) {
                                OrderCoupons::firstOrCreate([
                                    'order_id' => $order->ID,
                                    'coupon_id' => $coupon_id->ID
                                ]);
                            }

                        }

                    }


                    $i++;


                    $import_history = DB::connection($this->connection)->table('import_history')->where('type', 'orders')->first();
                    if ($import_history) {

                        $import_history = array(
                            'last_offset' => $i,
                            'status' => 'success',
                        );
                        DB::connection($this->connection)->table('import_history')->where('type', 'orders')->update($import_history);
                    } else {

                        DB::connection($this->connection)->table('import_history')->insert(['last_offset' => $i,
                            'status' => 'success',
                            'type' => 'orders'
                        ]);

                    }
               /* }
                catch (\Exception $e) {
                    echo 'failed : ' . $order->ID;
                    DB::rollback();
                    echo $e->getMessage();
                    echo $e->getCode();

                    // save failed orders
                }*/


            }

            echo '<br>\n';
            echo 'To :'. $i;
            echo '<br>\n';
            //dd($orders);
        }
    }

    public function getOrderItemsMeta($field  = null, $id)
    {
        if($field) {

            $order_items_meta = DB::connection('livetmh')
                ->table('wp_woocommerce_order_itemmeta')
                ->where('order_item_id', $id)
                ->where('meta_key', $field)
                ->first();
            if ($order_items_meta) {
                return $order_items_meta->meta_value;
            } else {
                $order_items_meta = DB::connection('livetmh')
                    ->table('wp_woocommerce_order_itemmeta_290615')
                    ->where('order_item_id', $id)
                    ->where('meta_key', $field)
                    ->first();
                if ($order_items_meta) {
                    return $order_items_meta->meta_value ;
                }
            }
        }

        return '';
    }


    public function getOrderMeta($field = null, $id)
    {
        if($field) {

            $order_meta = DB::connection('livetmh')
                ->table('wp_postmeta')
                ->where('post_id', $id)
                ->where('meta_key', $field)
                ->first();
            if ($order_meta) {
                return $order_meta->meta_value;
            } else {
                $order_meta = DB::connection('livetmh')
                    ->table('wp_postmeta_bkp_290615')
                    ->where('post_id', $id)
                    ->where('meta_key', $field)
                    ->first();
                if ($order_meta) {
                    return $order_meta->meta_value;
                }
            }
        }

        return '';
    }

    public function getOrderItems($orderid)
    {
        $order_items = DB::connection('livetmh')
            ->table('wp_woocommerce_order_items')
            ->where('order_id', $orderid)
            ->where('order_item_type', 'line_item')
            ->get();
        if($order_items) // check if order items exit in main table
        {
            return $order_items;
        }else{ // otherwise check in backup table
            $order_items = DB::connection('livetmh')
                ->table('wp_woocommerce_order_items_290615')
                ->where('order_id', $orderid)
                ->get();

            if($order_items) // if order items found in backup table
            {
              return $order_items;
            }
        }

        return array();

    }

    public function getRefundItem($refundId)
    {
        $order_items = DB::connection('livetmh')
            ->table('wp_woocommerce_order_items')
            ->where('order_id', $refundId)
            ->where('order_item_type', 'line_item')
            ->first();
        if($order_items) // check if order items exit in main table
        {
            return $order_items;
        }else{ // otherwise check in backup table
            $order_items = DB::connection('livetmh')
                ->table('wp_woocommerce_order_items_290615')
                ->where('order_id', $refundId)
                ->first();

            if($order_items) // if order items found in backup table
            {
                return $order_items;
            }
        }

        return array();

    }


    public function importCoupons()
    {
        $import_history = DB::connection($this->connection)->table('import_history')->where('type', 'coupons')->first();
        if ($import_history) {
            $offset = $import_history->last_offset;
        } else {
            $offset = 0;
        }

        echo 'From :'. $offset;


        $coupons = DB::connection('livetmh')
            ->table('wp_posts')
            ->where('post_type', 'shop_coupon')
            ->orderby('ID', 'ASC')
            ->offset($offset)
            ->limit(500)
            ->get();

        $i = $offset;

        if ($coupons) {

            foreach ($coupons as $coupon) {

                $coupon_flag = Coupons::find($coupon->ID);
                if(count($coupon_flag) == 0) {
                    if ($this->getOrderMeta('individual_use', $coupon->ID) == 'no') {
                        $is_individual = 0;
                    } else {
                        $is_individual = 1;
                    }
                    if ($this->getOrderMeta('enable_free_shipping', $coupon->ID) == 'false' || $this->getOrderMeta('enable_free_shipping', $coupon->ID) == 'no') {
                        $is_shipping = 0;
                    } else {
                        $is_shipping = 1;
                    }

                    if ($this->getOrderMeta('exclude_sale_items', $coupon->ID) == 'no') {
                        $is_exclude = 0;
                    } else {
                        $is_exclude = 1;
                    }
                    $usage_limit_per_user = $this->getOrderMeta('usage_limit_per_user', $coupon->ID);
                    if ( $usage_limit_per_user == NULL) {
                        $usage_limit_per_user= 0;
                    }

                    $limit_usage_to_x_items = $this->getOrderMeta('limit_usage_to_x_items', $coupon->ID);
                    if ($limit_usage_to_x_items == NULL) {
                        $limit_usage_to_x_items = 0;
                    }

                    $usage_limit = $this->getOrderMeta('usage_limit', $coupon->ID) ;
                    if ( $usage_limit == NULL) {
                        $usage_limit = 0;
                    }

                    if ($this->getOrderMeta('discount_type', $coupon->ID) == 'percent_product') {
                        $type  = 'product%';

                    } else if ($this->getOrderMeta('discount_type', $coupon->ID) == 'fixed_cart') {
                        $type  = 'cart';
                    } else if ($this->getOrderMeta('discount_type', $coupon->ID) == 'percent') {
                        $type = 'cart%';
                    }

                    $products_array = $this->getOrderMeta('product_ids', $coupon->ID);
                    $products = serialize($products_array);

                    $exc_products_array = $this->getOrderMeta('exclude_product_ids', $coupon->ID);
                    $exc_products = serialize($exc_products_array);

                    $categories_array = $this->getOrderMeta('product_category_ids', $coupon->ID) ;
                    $categories = serialize($categories_array);

                    $exc_categories_array = $this->getOrderMeta('exclude_product_category_ids', $coupon->ID);
                    $exc_categories = serialize($exc_categories_array);

                    $data_insert = array(
                        'id' => $coupon->ID,
                        'code' => $coupon->post_title,
                        'description' => $coupon->post_excerpt,
                        'type' => $type,
                        'status' => $coupon->post_status,
                        'published_at' => $coupon->post_date,
                        'amount' => $this->getOrderMeta('coupon_amount', $coupon->ID),
                        'is_free_shipping' => $is_shipping,
                        'expiry_date' => $this->getOrderMeta('expiry_date', $coupon->ID),
                        'max_spend' => $this->getOrderMeta('maximum_amount' ,$coupon->ID),
                        'min_spend' => $this->getOrderMeta('minimum_amount' ,$coupon->ID),
                        'is_individual' => $is_individual,
                        'show_on_cart' => '1',
                        'exclude_sale_items' => $is_exclude,
                        'products' => $products,
                        'exclude_products' => $exc_products,
                        'categories' => $categories,
                        'exclude_categories' => $exc_categories,
                        'usage_limit_coupon' => $limit_usage_to_x_items,
                        'created_at' => date('Y-m-d H:i:s'),
                        'usage_limit_user' => $usage_limit_per_user,
                        'usage_count' => $usage_limit
                    );

                    Coupons::firstOrCreate($data_insert);

                    $i++;


                    $import_history = DB::connection($this->connection)->table('import_history')->where('type', 'coupons')->first();
                    if ($import_history) {

                        $import_history = array(
                            'last_offset' => $i,
                            'status' => 'success',
                        );
                        DB::connection($this->connection)->table('import_history')->where('type', 'coupons')->update($import_history);
                    } else {

                        DB::connection($this->connection)->table('import_history')->insert(['last_offset' => $i,
                            'status' => 'success',
                            'type' => 'coupons'
                        ]);

                    }

                }
            }

            echo '<br>\n';
            echo 'To :'. $i;
            echo '<br>\n';
        }
    }

    public function wssn()
    {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'wssn-api-key:  7ki9TOSxS6Av3sg2uivEKbkipzbuTkqP',
            'wssn-client-id: c23'
        ));
        curl_setopt($ch, CURLOPT_URL,"https://api.wssn.nl/v2/stocks/e/99169004225");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $server_output = curl_exec ($ch);
        echo 'Curl error: ' . curl_error($ch);
        curl_close ($ch);
        dd($server_output);
        if ($server_output == "OK") {

        } else {
            echo 'asdfadfasdf';
        }

    }

}
