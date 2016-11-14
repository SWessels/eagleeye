<?php

namespace App\Http\Controllers;
use App\User;
use App\Products;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Functions\Functions;
use App\Http\Requests;
use App\Categories;
use App\SeoData;
use Session;
use Config;
use  Mail;
use  Log;

class CategoriesController extends BaseController
{
    private $category;
    private $product; 

	 public function __construct()
    {
        parent::__construct();
        if(User::userCan('products') === false)
			{
				abort(403, 'Unauthorized action.');
			}
		$this->product 		= new Products;
		$this->category 	= new Categories;
        $this->seo	= new SeoData;
   
	}
	
	
	//////////////////////////////////////////////////////Function Index//////////////////////////////////
    public function index()
    {
        $all_categories  = $this->category->getCategories();
        return view('products.categories.categories', ['categories' => $all_categories ]);
   }

    //////////////////////////////////////////////////////Function create for adding category/////////////
    
	public function create()
    {
        
		 $all_categories  = $this->category->getCategories();
		 $data = array('category' => $all_categories);
		
        return view('products.categories.newcategory', ['data' => $data]);
    }

	 //////////////////////////////////////////////////////Function store for saving category/////////////   
   
    public function store(Request $request)
    {
        $this->validate($request, [
			'name'       => 'required',
			'slug'   => 'required',
		]);
		$input = $request->all();
			
        $inserted_category_id =  $this->category->fSavecategory($input);

        $save_seo  = $this->seo->saveSeo($input, $inserted_category_id, 'product-category');
        Session::flash('flash_message', 'Category successfully created!');
        return redirect('categories');

    }
    ///////////////////////////////////////////////////////////////////   
    public function show($id)
    {
        //
    }
    //////////////////////////////////////////////////////Function Edit for Editing category/////////////   
    public function edit($id)
    {
        $id_exists = Functions::checkIdInDB($id ,$this->category->table);
        if($id_exists > 0) {
            $all_categories = $this->category->getCategoryNotId($parentId = 0, $level = 0, $id);
            $categoryById = $this->category->getCategoryById($id);
            $data = array('category' => $categoryById, 'allcategory' => $all_categories);
            return view('products.categories.editcategory', ['data' => $data]);
        }
        else{
            abort(404, 'Not Found.');

        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'       => 'required',
            'slug'   => 'required',
        ]);
        $input = $request->all();
       
        $category_update =  $this->category->fUpdateCategory($id, $input);
        $save_seo  = $this->seo->saveSeo($input, $id, 'product-category');
        $all_categories  = $this->category->getCategoryNotId($parentId = 0, $level=0,$id);
        $ct = $this->category->getCategoryById($id);
        $data = array('category' => $ct, 'allcategory' => $all_categories);
        Session::flash('flash_message', 'Category successfully Updated!');
     
        return redirect('categories');
    }

    public function sendtryoutemail()
    {
        //get try out Products  ids
        $domains = Config::get('domains');
        foreach($domains as $domainname=>$domainvalue) {
            $ids = \DB::connection($domainname)->table('category_product')
                ->leftJoin('products', 'products.id', '=', 'category_product.product_id')
                ->leftJoin('categories', 'categories.id', '=', 'category_product.category_id')
                ->where('categories.slug', '=', 'tryouts')// 12376 in live DB and 10796 in localhost DB is id of tryout category
                ->select('category_product.product_id', 'category_product.created_at',
                    'products.slug', 'products.name', 'products.sale_price')
                ->get();

            //dd(\DB::getQueryLog());

            //dd($ids);
            foreach ($ids as $value) {
                $tot_likes = \DB::connection($domainname)->table('tryouts_feedback')->where([
                        ['like', '=', '1'],
                        ['product_id', '=', $value->product_id],
                    ])
                    ->whereBetween('created_at',[$value->created_at,date('Y-m-d H:i:s',strtotime("+10 day",strtotime($value->created_at)))])
                    ->count();
                //dd(\DB::getQueryLog());
               // echo "<pre>;";print_r(\DB::getQueryLog());
                $is_email_sent = \DB::connection($domainname)->table('tryouts_emails')->where([
                        ['is_sent', '=', 1],
                        ['product_id', '=', $value->product_id],
                    ])->count();
                if ($tot_likes > 99 && $is_email_sent==0) {
                    //echo $tot_like;exit;
                    echo 'success ';
                    $data                   =   [];
                    $data['site_url']       =   'https://store.fashionhomerun.nl';//url('');
                    $data['product_url']    =   'https://store.fashionhomerun.nl'/*url('')*/ . '/product/' . $value->slug;
                    $data['product_name']   =   $value->name;
                    $data['product_price']  =   $value->sale_price;
                    $mymail = Mail::send('email-templates.tryoutemail', $data, function ($message) {
                        $message->from('klantenservice@themusthaves.nl', 'Tryout');
                        $message->to('sanaullahAhmad@gmail.com')->cc('sanakust@yahoo.com');
                        $message->subject('Tryout Product likes reached 100');
                    });
                    // now save it, so that email is not sent again and again

                    \DB::table('tryouts_emails')->insertGetId(
                        [
                            'product_id'  => $value->product_id,
                            'likes'       => $tot_likes,
                            'is_sent'     => 1,
                            'created_at'  => date('Y-m-d H:i:s'),
                        ]
                    );

                    //dd($mymail);
                }
            }
        }
    }
    public function destroy($id)
    {
        //
    }
}
