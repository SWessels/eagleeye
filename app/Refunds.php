<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Refunds extends BaseModel
{
    public $table = "refunds";
    public  $timestamps = false;
    protected $fillable = [
        'id','order_id', 'created_at', 'amount','reason'
    ];

    protected $timestemps = false;

    public function getRefunds($args){

        $query = "SELECT id FROM refunds where date(created_at) = '".$args."'";
        return DB::connection($this->connection)->select(DB::raw($query));
    }

    public function order()
    {
        return $this->belongsTo('App\Orders', 'order_id', 'id');
    }

    public function refundItems()
    {
        return $this->hasMany('App\RefundItems', 'refund_id', 'id');
    }
}
