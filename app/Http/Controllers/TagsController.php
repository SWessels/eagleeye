<?php

namespace App\Http\Controllers;

use App\Tags;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\DocBlock\Tag;
use App\Functions\Functions;
use DB;
class TagsController extends BaseController
{

    protected $tag;
    public function __construct()
    {

        
       $tag =  new Tags();

        parent::__construct();
        $tag =  new Tags();

        $this->tag = $tag;

    }

    public function index()
    {

        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=''){
            $tags = $this->tag->fsearchtags($_REQUEST['keywords']);

        }
        else{
            $tags = $this->tag->listAllTags();

        }

        foreach($tags as $tag)
        {
            $tag->setAttribute('product_count', $this->tag->getCountProductsOfTags($tag->id));
        }
 


        return view('products.tags.tags', ['tags' => $tags]);

    }

    public function create()
    {

        return view('products.tags.newtag');
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'name'       => 'required',
            'slug'   => 'required',
        ]);
        $input = $request->all();
        $t =  $this->tag->AddnewTag($input);
        Session::flash('addtag', 'Tag Successfully Added!');

        return redirect('tags');



    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $id_exists = Functions::checkIdInDB($id ,$this->tag->table);
        if($id_exists > 0) {
            $tagbyid = $this->tag->getTagbyId($id);
            return view('products.tags.edittag', ['tagbyid' => $tagbyid]);
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
        $this->validate($request, [
            'name'       => 'required',
            'slug'   => 'required',
        ]);
        $input = $request->all();

        $tupdate =  $this->tag->tagUpdatebyId($id, $input);
        $tagbyid = $this->tag->getTagbyId($id);

        Session::flash('flash_message', 'Tag Successfully Updated!');
        return view('products.tags.edittag', ['tagbyid' => $tagbyid]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @retSearchTagurn \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $input = $request->all();
        if($input['remove'] == 'rm'){

            foreach($input['del'] as $key => $del){


                DB::table('tags')->where('id', '=',$del)->delete();
                DB::table('product_tag')->where('tag_id', '=',$del)->delete();
            }


        }
        Session::flash('addtag', 'Tag Successfully Removed!');

        return redirect('tags');
    }


}
