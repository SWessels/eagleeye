<?php
//added for predictive stocks average table
namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Session;

class PredictiveStocks extends Model
{
    //table name declerations
    protected $table = "product_avg";
    protected $connection = "eagleeye";
    public $timestamps = false;
    protected $fillable = ['date_entry','product_id','domain_id','avg','sku','refunds'];

    public function products(){
        return $this->hasMany('App\Products', 'id', 'product_id');
    }

    public function counts(){
        $counts = array('all' => 0, 'instock' => 0, 'nealry_outofstock' => 0, 'outofstock' => 0);
        $all = Products::where('sku','<>','')
            ->where('product_type','<>','composite')
            ->count();
        $counts['all'] = $all ;

        $all = Products::where('stock_status','=','in')
            ->where('sku','<>','')
            ->where('product_type','<>','composite')
            ->count();
        $counts['instock'] = $all ;

        $all = Products::where('near_stock',true)
            ->where('sku','<>','')
            ->where('product_type','<>','composite')
            ->count();
        $counts['nealry_outofstock'] = $all ;

        $all = Products::where('stock_status','=','out')
                ->where('sku','<>','')
                ->where('product_type','<>','composite')
                ->count();
        $counts['outofstock'] = $all ;

        return $counts;
    }

    public function listing($args , $status){
        if(Session::has('pagination'))
            $pagintion =   Session::get('pagination');
        else
            $pagintion = 50;

            $product_ids = DB::table('category_product')
            ->select(DB::raw('product_id'))->where('category_id','2872')->get();
        $prod=null;
        foreach($product_ids as $product)
            $prod[]=$product->product_id.',';

        if($args != null){
            if($status != null){
                if($status == 'near') {
                    $data = Products::where('sku', '<>', '')
                        ->where('product_type', '<>', 'composite')
                        ->where('near_stock', true)
                        ->whereNotIn('id', $prod)
                        ->where(function ($query) use ($args) {
                            $query->where('sku', 'LIKE', '%' . $args . '%')->orWhere('name', 'LIKE', '%' . $args . '%');
                        })
                        ->with('attributes')
                        ->orderBy('updated_at')
                        ->orderBy('sku')
                        ->paginate($pagintion);
                }
                else{
                    $data = Products::where('sku', '<>', '')
                        ->where('product_type', '<>', 'composite')
                        ->where('stock_status', $status)
                        ->whereNotIn('id', $prod)
                        ->where(function ($query) use ($args) {
                            $query->where('sku', 'LIKE', '%' . $args . '%')->orWhere('name', 'LIKE', '%' . $args . '%');
                        })
                        ->with('attributes')
                        ->orderBy('updated_at')
                        ->orderBy('sku')
                        ->paginate($pagintion);
                }
            }
            else {
                $data = Products::where('sku', '<>', '')
                    ->where('product_type', '<>', 'composite')
                    ->whereNotIn('id', $prod)
                    ->where(function ($query) use ($args) {
                        $query->where('sku', 'LIKE', '%' . $args . '%')->orWhere('name', 'LIKE', '%' . $args . '%');
                    })
                    ->with('attributes')
                    ->orderBy('updated_at')
                    ->orderBy('sku')
                    ->paginate($pagintion);
            }
        }
        else{
            if($status != null) {
                if($status == 'near') {
                    $data = Products::where('sku', '<>', '')
                        ->where('product_type', '<>', 'composite')
                        ->where('near_stock', true)
                        ->whereNotIn('id', $prod)
                        ->with('attributes')
                        ->orderBy('updated_at')
                        ->orderBy('sku')
                        ->paginate($pagintion);
                }
                else{
                    $data = Products::where('sku', '<>', '')
                        ->where('product_type', '<>', 'composite')
                        ->where('stock_status', $status)
                        ->whereNotIn('id', $prod)
                        ->with('attributes')
                        ->orderBy('updated_at')
                        ->orderBy('sku')
                        ->paginate($pagintion);
                }
            }
            else {
                $data = Products::where('sku', '<>', '')
                    ->where('product_type', '<>', 'composite')
                    ->whereNotIn('id', $prod)
                    ->with('attributes')
                    ->orderBy('updated_at')
                    ->orderBy('sku')
                    ->paginate($pagintion);
            }
        }



        return $data;
    }

    public function average($sku){
        //return PredictiveStocks::select(DB::raw('SELECT sum(avg)  WHERE date_entry >= DATE_SUB(NOW(), INTERVAL 14 DAY)'));
        return DB::connection($this->connection)
            ->select("SELECT sum(avg) as avg FROM product_avg  WHERE sku = '".$sku."' AND date_entry >= DATE_SUB(NOW(), INTERVAL 14 DAY)")[0]->avg;

    }

    public function refunds($sku){
        /*return PredictiveStocks::where('sku','=',$sku)
            ->where('date_entry','>=','CURRENT_DATE - INTERVAL 14 DAY')
            ->sum('refunds');*/
        return DB::connection($this->connection)
            ->select("SELECT sum(refunds) as avg FROM product_avg  WHERE sku = '".$sku."' AND date_entry >= DATE_SUB(NOW(), INTERVAL 14 DAY)")[0]->avg;
    }

    public function attributes()
    {
        return $this->hasOne('App\Attributes', 'product_id', 'id');
    }
    public function categories(){
        return $this->belongsToMany('App\Categories','category_product','product_id','category_id');
    }
}
