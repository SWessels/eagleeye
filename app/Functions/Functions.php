<?php
namespace App\Functions;

use DB;
use Session;
use Config;
use NumberFormatter;
use Intervention\Image\ImageManagerStatic as Image;

class Functions{


    public static function checkIdInDB( $id , $table)
    {
 
        $query = DB::connection(Session::get('connection'))->table($table);
        $query->where('id', '=', $id);
        return $query->count();
    }


   public static function makeSlug($title, $duplicates_count = 0 ,$table ,$id,$type)
    {
        $duplicates_count = (int) $duplicates_count ;

        $slug = $title = str_slug($title);
        if($id != '' || $id !=0 ){
            if ($duplicates_count > 0) {
                $slug = $slug.'-'.$duplicates_count;

                if($type != ''){

                    $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->where('id', '!=' ,$id)->where('type' , '=' , $type)->count();}
                else{
                    $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->where('id', '!=' ,$id)->count();
                }
                if ($rowCount > 0) {
                    return self::makeSlug($title, ++$duplicates_count,$table ,$id,$type);
                } else {
                    return $slug;
                }
            }
            else {
                if($type != ''){

                    $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->where('id', '!=' ,$id)->where('type' , '=' , $type)->count();}
                else{
                    $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->where('id', '!=' ,$id)->count();
                }
                if ($rowCount > 0) {
                    return self::makeSlug($title, ++$duplicates_count,$table ,$id,$type);
                } else {
                    return $title;
                }
            }  
        }
        else{
        if ($duplicates_count > 0) {
            $slug = $slug.'-'.$duplicates_count;
            if($type != ''){
            $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->where('type' , '=' , $type)->count();
             }
            else {  $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->count();  }



            if ($rowCount > 0) {
                return self::makeSlug($title, ++$duplicates_count,$table ,$id,$type);
            } else {
                return $slug;
            }
        } 
        else {
            if($type != ''){
                $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->where('type' , '=' , $type)->count();
            }
            else {  $rowCount = DB::connection(Session::get('connection'))->table($table)->where('slug', $slug)->count();  }

            if ($rowCount > 0) {

                return self::makeSlug($title, ++$duplicates_count,$table ,$id,$type);
            } else {

                return $title;
            }
        }
    }}
    
    public static function validPrice($price = false)
    {
        if($price)
        {
            if($price!=''  && $price != '0' && $price !='0.00' )
            {
                return true;
            }
        }

        return false;
    }

    public static function priceForDB($price = false)
    {
        if($price)
        {
            // 123.45,67
            $cleanString = preg_replace('/([^0-9\.,])/i', '', $price);
            $onlyNumbersString = preg_replace('/([^0-9])/i', '', $price);

            $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

            $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
            $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

            return (float) str_replace(',', '.', $removedThousendSeparator);
        }
    }



    public static function moneyForField($money)
    {
        if(Functions::validPrice($money)) {

            $fmt = new NumberFormatter('de_DE', NumberFormatter::DECIMAL);
            $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
            $format_money = $fmt->format($money);

            if (intl_is_failure($fmt->getErrorCode())) {
                return report_error("Formatter error");
            }
          
            return $format_money;

        }
    }




    public static function money($money)
    {
        if(Functions::validPrice($money)) {


            $fmt = new NumberFormatter('de_DE', NumberFormatter::DECIMAL);
            $money = $fmt->format($money);
            $symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
            $money = $symbol . $money;

            if (intl_is_failure($fmt->getErrorCode())) {
                return report_error("Formatter error");
            }


            return $money;
        }
    }

    public static function getCurrencySymbol()
    {
        $fmt = new NumberFormatter('de_DE', NumberFormatter::DECIMAL);
        $symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        return $symbol;
    }




    public static function waitList($meta)
    {

        $wait_list = array();
        if(!is_array($meta))
        {
            return $wait_list; 
        }
        foreach($meta as $item)
        {
            if(!empty($item) && $item->meta_value!='')
            {
                $wait_list = unserialize($item->meta_value);
            }
        }

        return $wait_list;
    }
    public static function getVarNameWithSize($product_id){

        $name_detail =  DB::connection(Session::get('connection'))->select('select a.name, b.attributes FROM products a
                        left join attributes b  on b.product_id = a.id
                         where  a.id = '.$product_id.'  ');

        if(!empty($name_detail[0]->attributes)){

            $attr = array_values(unserialize($name_detail[0]->attributes));
            $att_string = " - <strong>".$attr[0]."</strong>";
        }
        else{
            $att_string = '';
        }
      return  $pname = substr($name_detail[0]->name, 17).$att_string;

    }

    public static function getProductName($product_id){
        $parent_detail = DB::connection(Session::get('connection'))->table('report_byproducts_name')->where('parent', '=',$product_id)->first();
       return $pname =  $parent_detail->parent_name;
    }

    public static function clean_imageTitle($string){
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9.\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.

    }


    public static function ResizeImages($width , $height, $path, $file_name, $file_extension,$destinationPath2)
    {
        $resize_file_name = $file_name."-".$width."x".$height.".".$file_extension;
        return $img_resize = Image::make($path)->resize($width, $height)->save($destinationPath2.$resize_file_name);
    }


    public static function ResizeImagesThumbnail($width , $height, $path, $file_name, $file_extension,$destinationPath2)
    {
        $resize_file_name = $file_name."-thumbnail".".".$file_extension;
        return $img_resize = Image::make($path)->resize($width, $height)->save($destinationPath2.$resize_file_name);
    }


    public static function getDomainName($connection)
    {
        $domains = Config::get('domains');
        return $domains[$connection];
    }

}


?>