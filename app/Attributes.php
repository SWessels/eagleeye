<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Attributes extends BaseModel
{
	public $table = "attributes";
	public  $timestamps = false;

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
	}

	public function products(){
		return $this->hasOne('App\Products','product_id','id');
	}

	public function terms()
	{
		return $this->hasMany('App\Terms', 'attribute_id', 'id');
	}
	public  function  getattributesbyId($id){
		return Attributes::where('id', $id)->first();
	}
	public  function attributeUpdatebyId($id, $arr){
		//dd($arr);
		$name = trim($arr['name']);
		//$slug = trim($arr['slug']);
		$slug= str_slug($arr['slug'] , "-");
		$slug =	$this->checkSlug($slug);
		$dataupdate = array(
			'name' 			=> $name,
			'slug' 			=> $slug,
			'updated_at'	=> date('Y-m-d H:i:s')
		);
		$q=	Attributes::where('id', $id)
			->update($dataupdate);

	}
	public function fGetAllAttributes(){

		return Attributes::with('Terms')->orderBy('term_index', 'asc')->get();


	}
	public function checkSlug($s){
//$s='test test-2';
		$count = Attributes::where('slug', '=', $s)->count();
		echo $count;
		echo "<br>";
		if ($count  > 0) {
			//$s = $s."-"."1";
			/*echo $s;echo "<br>";*/
			$se = explode("-",$s);
			echo $end = end($se);
			if(is_numeric($end) )
			{
				$scount = $end;
				$scount  = $scount +1;

			}
			else{
				$scount  = 1;
			}

			$stitle = $se[0];


			$s = $stitle ."-".$scount;
			return $this->checkSlug($s);echo "<br>";//exit;
		//	return $string = str_replace(' ', '-', $s1);


		}

		else{
			return $s;
		}

	}

	public function AddnewAtt($array){

		$slugstr = str_slug($array['slug'] , "-");
		$slug =	$this->checkSlug($array['slug']);
		$name = trim($array['name']);


		$data = array(
			'name' 			=> $name,
			'slug' 			=> $slug,

			'created_at'	=> date('Y-m-d H:i:s')
		);
		//dd($data);
		Attributes::insert( $data );

		return redirect('attributes');

	}

	public  function  getAttributesByProductId($id){
		return Attributes::where('product_id', $id)->first();
	}
	
	

	

}
