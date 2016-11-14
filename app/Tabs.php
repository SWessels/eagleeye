<?php

namespace App;

use App\Functions\Functions;
use DB;


class Tabs extends BaseModel
{
    public  $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getAllTabs(){
        return Tabs::orderBy('id', 'desc')->where('type', '<>', 'details')->with('products')->paginate(20);
    }

    public function addNewTab($input)
    {
        $title = $input['title'];
        $desc  = $input['tab-editor'];
        $parent_id  = $input['parent_id'];
        if($parent_id == 0)
        {
            $type = 'global';
        }else{
            $type = 'custom';
        }


        $data = array(
            'name' 	        => $title,
            'description'   => $desc,
            'parent_id'     => $parent_id,
            'type'          => $type
        );
        return Tabs::insert($data);
    }

    public static function saveTab($input)
    {
        return Tabs::insert($input);
    }

    public static function getDetailTab($product_id)
    {
        return Tabs::where('parent_id', $product_id)->where('type', 'details')->with('products')->first();
    }

    public function getTabById($id = false)
    {
        if($id)
        {
            return Tabs::where('id', $id)->with('products')->first();
        }else{
            return false;
        }

    }

    public function UpdateTabById($input = false, $id =  false)
    {
        if($input && $id)
        {
            $name           = trim($input['title']);
            $desc           = trim($input['tab-editor']);
            $parent_id      = $input['parent_id'];
            if($parent_id == 0)
            {
                $type = 'global';
            }else{
                $type = 'custom';
            }

            $dataUpdate = array(
                'name' 	        => $name,
                'description'   => $desc,
                'parent_id'     => $parent_id,
                'type'          => $type
            );

            return Tabs::where('id', $id)->update($dataUpdate);
        }else{
            return false;
        }
    }

    public static function checkIfDetailsTabExist($product_id)
    {

        return Tabs::where('type', '=', 'details')->where('parent_id', $product_id)->first();
    }

    public function updateDetailsTab($input = false)
    {
        if($input)
        {
            // update details tab if tab id not empty
            $details_tab = Tabs::checkIfDetailsTabExist($input['product_id']);

            if(is_null($details_tab))
            {
                $name   = trim($input['details_tab']['title']);
                $desc   = trim($input['details_tab']['desc']);
                $parent_id = $input['product_id'];
                $type = 'details';

                $data = array(
                    'name' 	        => $name,
                    'description'   => $desc,
                    'parent_id'     => $parent_id,
                    'type'          => $type
                );
                Tabs::insert($data);

            }else{
                $name   = trim($input['details_tab']['title']);
                $desc   = trim($input['details_tab']['desc']);
                $dataUpdate = array(
                    'name' => $name,
                    'description' => $desc
                );
                Tabs::where('parent_id', $input['product_id'])->where('type', 'details')->update($dataUpdate);
            }
        }

        return true;
    }

    public function updateCustomTabById($input = false)
    {


        if(isset($input['tabs_data']))
        { 
            foreach ($input['tabs_data'] as $tab)
            { 
                $name   = trim($tab['title']);
                $desc   = trim($tab['desc']);
                $id     = $tab['tab_id'];

                $dataUpdate = array(
                    'name' => $name,
                    'description' => $desc
                );

                Tabs::where('id', $id)->update($dataUpdate);
            }
            return true;
        }else{
            return false;
        }
    }
    
    public function deleteCustomTab($id = false)
    {
        if($id)
        {
            $tab = Tabs::find($id);
            if($tab->forceDelete())
            {
                return true;
            }
        }

        return false;

    }


    /**
     * relations
     * */

    public function products(){
        return $this->belongsTo('App\Products', 'parent_id', 'id');
    }


    /**
     * end of relations
     * */


    public static function getGlobalTabs()
    {
        return Tabs::where('parent_id',  0)->get();
    }

    public static function getTabsByProductId($id =  false)
    {
        if($id)
        {
            return Tabs::where('parent_id',$id)->where('type', 'custom')->get();
        }
    }

    public function addCustomTab($input = false)
    {
        if($input)
        {
            $title = '';
            $desc  = '';
            $parent_id  = $input['product_id'];

            $data = array(
                'name' 	        => $title,
                'description'   => $desc,
                'parent_id'     => $parent_id,
                'type'          => 'custom'
            );
            if(Tabs::insert($data))
            {
               return DB::connection($this->connection)->getPdo()->lastInsertId();
            }else{
                return false;
            }

        }else{
            return false;
        }

    }
}
