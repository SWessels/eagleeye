<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions;
use Illuminate\Support\Facades\DB;

class Pricing extends BaseModel
{

    protected $table = 'pricing';
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    public function getAllPricing()
    {
        return Pricing::orderBy('id', 'desc')->paginate(60);
    }

    public function savePricing($pricing, $pricing_ids, $exclude_ids, $order_value_ids, $action_id = NULL)
    {

        //dd($order_value_ids);
        DB::connection($this->connection)->beginTransaction();

        try {

            if($action_id !== null)
            {
                $saved_pricing = Pricing::where('id', $action_id )->update($pricing);
                $lastInsertedId = $action_id;
                DB::connection($this->connection)->table('pricing_ids')->where('pricing_id', $action_id)->delete();
                DB::connection($this->connection)->table('exclude_ids')->where('pricing_id', $action_id)->delete();
                DB::connection($this->connection)->table('pricing_categories')->where('pricing_id', $action_id)->delete();

            }else{
                $saved_pricing = Pricing::insert($pricing);
                $lastInsertedId = DB::connection($this->connection)->getPdo()->lastInsertId();
            }


            if ($saved_pricing || $action_id != null ) {

                //dd(!empty($pricing_ids['applies_on']));
                $p_ids = [];
                if (isset($pricing_ids['applies_on']) && !empty($pricing_ids['applies_on'])) {

                    foreach ($pricing_ids['applies_on'] as $pricing_id) {

                        $p_ids[] = ['applies_on' => $pricing_id, 'pricing_id' => $lastInsertedId];
                    }

                   DB::connection($this->connection)->table('pricing_ids')->insert( $p_ids );

                }

                $e_ids = [];

                if (isset($exclude_ids['exclude']) && !empty($exclude_ids['exclude'])) {
                    foreach ($exclude_ids['exclude'] as $exclude_id) {
                        $e_ids[] = ['product_id' => $exclude_id, 'pricing_id' => $lastInsertedId];
                    }

                    DB::connection($this->connection)->table('exclude_ids')->insert($e_ids);
                }

                if(!empty($order_value_ids))
                {
                    $order_v_ids = [] ;
                        foreach ($order_value_ids as $order_value_id)
                        {
                            $order_v_ids[] = ['order_value_id' => $order_value_id, 'pricing_id' => $lastInsertedId];
                        }

                    DB::connection($this->connection)->table('pricing_categories')->insert($order_v_ids);
                }
            }
            DB::connection($this->connection)->commit();


        }
        catch (\Exception $e) {
            DB::connection($this->connection)->rollback();
            return array('action' => 'false', 'msg' => 'Failed saving Action!', 'error_details' => $e->getMessage().$e->getCode());
        }
    }


    public function getPricingById($id)
    {
        return Pricing::where('id', $id)->first();
    }


    public function getPricingIds($id)
    {
        return DB::connection($this->connection)->table('pricing_ids')->where('pricing_id', $id)->get();
    }

    public function getExcludeIds($id)
    {
        return DB::connection($this->connection)->table('exclude_ids')->where('pricing_id', $id)->get();
    }

    public function deletePricingData($id)
    {
        DB::connection($this->connection)->table('pricing_ids')->where('pricing_id', $id)->delete();
        DB::connection($this->connection)->table('exclude_ids')->where('pricing_id', $id)->delete();
    }


    public function getOrderValues()
    {
        return DB::connection($this->connection)->table('action_order_values')->get();
    }

    public function getPricingCategories($id)
    {
        return DB::connection($this->connection)->table('pricing_categories')->where('pricing_id' , $id)->get();
    }
}
