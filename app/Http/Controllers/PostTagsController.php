<?php

namespace App\Http\Controllers;

use App\Functions\Functions;
use App\PostTags;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use phpDocumentor\Reflection\DocBlock\Tag;

use DB;
class PostTagsController extends BaseController
{

    protected $tag;
    public function __construct()
    {

        $this->middleware('auth');

        parent::__construct();

        $posttag =  new PostTags();
        $this->posttag = $posttag; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=''){
            $tags = $this->posttag->fsearchposttags($_REQUEST['keywords']);

        }
        else{
            $tags = $this->posttag->listAllTags();

        }
        return view('posts.tags.posttags', ['tags' => $tags]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'       => 'required',
            'slug'   => 'required',
        ]);
        $input = $request->all();
        $t =  $this->posttag->AddnewTag($input);
        Session::flash('flash_message', 'Post Tag Successfully Added!');

        return redirect('posttags');
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id_exists = Functions::checkIdInDB($id ,$this->posttag->table);
        if($id_exists > 0) {
            $tagbyid = $this->posttag->getTagbyId($id);
            return view('posts.tags.editposttag', ['tagbyid' => $tagbyid]);
        }
        else{
            abort(404, 'Not Found.');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $tupdate =  $this->posttag->tagUpdatebyId($id, $input);
        $tagbyid = $this->posttag->getTagbyId($id);

        Session::flash('flash_message', 'Post Tag Successfully Updated!');
        return view('posts.tags.editposttag', ['tagbyid' => $tagbyid]);

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
       
        
       $delPosttag = $this->posttag->DeletePostTags($input);
        
        Session::flash('flash_message', 'Post Tag Successfully Removed!');

        return redirect('posttags');
    }


}
