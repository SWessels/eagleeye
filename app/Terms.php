<?php

namespace App;

use App\Functions\Functions;
use DB;


class Terms extends BaseModel
{

    public  $timestamps = false;
    public $table = 'terms';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function productattributes(){
        return $this->belongsTo('App\ProductAttributes', 'attributes_id');
    }
    public function fsearchterms($keyword , $att_id){

        $keyword = "%".str_replace('+','',$keyword)."%";

        return $terms = Terms::where('name', 'like',  $keyword )->where('attributes_id', '=',  $att_id )
            ->orderBy('id', 'desc')->paginate(60);


       /* return ProductAttributes::with(['Terms' => function ($query) use ($keyword) {
                                                 $query->where('name', 'like', $keyword); }])
                                            ->where('id', $att_id)->orderBy('id', 'desc')->paginate(2);*/

        
    }
    public  function  getTermsbyIdAttid($id){

        return $terms = Terms::where('attributes_id', '=',  $id )
            ->orderBy('term_index', 'asc')->paginate(60);

       /* return ProductAttributes::with('Terms')
           ->where('id', $id)->orderBy('id', 'desc')->Paginate(2);*/


    }

    public function getAttName($id){
        return ProductAttributes::where('id', '=',  $id )->value('name');

    }

    public function getTermById($id){
        return Terms::where('id', $id)->orderBy('term_index', 'asc')->first();  
    }

    public function getTermBySlug($slug){
        return Terms::where('slug', $slug)->orderBy('term_index', 'asc')->first();
    }
     

   

    public function saveTerm($array){
       
        $slugstr = trim($array['slug']);
        $slug =	Functions::makeSlug($slugstr ,$duplicates_count = 0 ,$this->table, $id = '', $type= '');
        $name = trim($array['name']);
        $desc = trim($array['desc']);
        $attid = trim($array['att_id']);
        $data = array(
            'name' 			=> $name,
            'slug' 			=> $slug,
            'description'   => $desc,
            'attributes_id' => $attid 
        );

        Terms::insert($data); 
        $inserted = DB::getPdo()->lastInsertId(); 
        
    }
    public  function termUpdatebyId($id, $arr){
        
        $name = trim($arr['name']);
        $desc = trim($arr['desc']);
        $slugstr = trim($arr['slug']);
        $slug =	Functions::makeSlug($slugstr ,$duplicates_count = 0 ,$this->table, $id, $type= '');
        $dataupdate = array(
            'name' 			=> $name,
            'slug' 			=> $slug,
            'description'   => $desc,
            'updated_at'	=> date('Y-m-d H:i:s')
        );
        $q=	Terms::where('id', $id)->update($dataupdate);


    }
    public function save_term_indexes(){
        $id = trim($_POST['id']);
        $indexid = trim($_POST['indexid']);
        $dataupdate_index = array( 'term_index' => $indexid);
         $q = Terms::where('id',$id)->update($dataupdate_index);

    }

    public function getAttributeIDByTerm($term)
    {
         
        return Terms::select('attributes_id')->where('terms.slug',$term)->first();
    }

    public function checkIfExistByAttributeId($att_id = false, $slug = false)
    {
        $count = 0 ;
        if($att_id && $slug)
        {
            $count = Terms::where('slug',$slug)->where('attributes_id',$att_id)->get()->count();
        }

        return $count;
    }
    
}
