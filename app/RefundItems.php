<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class RefundItems extends BaseModel
{
    public $table = "refund_items";
    protected $fillable = [
        'refund_id', 'product_id', 'unit_price', 'unit_tax', 'qty', 'total' ,'total_tax','order_id'
    ];

    public function getRefundAvg($args){
        return DB::connection($this->connection)->table('refund_items')
            ->select(DB::raw('sum(qty) as product_count, product_id'))
            ->whereIn('refund_id', $args)
            ->groupBy('product_id')
            ->get();

    }


    public function refund()
    {
        return $this->belongsTo('App\Refunds', 'refund_id', 'id');
    }
}
