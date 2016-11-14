
@extends('layouts.master')
@section('css')

@endsection
@section('content')
this is content

<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Home</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <a href="/">Products</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <a href="{{ route('categories.index') }}"> <span>Categories</span></a>
        </li>
    </ul>

</div>
<br>
<div class="page-bar">
    {{--<ul class="page-breadcrumb">--}}
        {{--<li> <a href="../pages/index.html">Home</a>  </li>--}}

        {{--<a href="{{ route('categories.index') }}"> <span>Categories</span></a>--}}
    {{--</ul>--}}
    @if(Session::has('flash_message2'))
        <div class="alert alert-success">
            {{ Session::get('flash_message2') }}
        </div>
    @endif

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Edit Category
    <br>
    <small></small> </h3>
  @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<div class="row">
    <div class="col-md-8 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
        
            <div class="portlet-body form">
                {!! Form::open(array('method' => 'patch' ,'action' => array('CategoriesController@update',$data['category']->id))) !!}

                <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                    <div class="form-body">
                       
                        <div class="form-group">
                            <label>Name</label>

                                <input name="name" id="name" type="text" class="form-control"  value="{!! $data['category']->name !!}" >
                        </div>
                        <div class="form-group">
                            <label>Slug</label>

                                <input name="slug" id="slug" type="text" class="form-control input-sm" value="{!! $data['category']->slug !!}" >
                        </div>
                        <div class="form-group">
                            <label>Parent</label>
                            <select name="parent"  id="parent" class="form-control">
                                <option value="">No</option>
                                @if (count($data['allcategory']) > 0)
                    
                                    @foreach ($data['allcategory'] as $allcategory)
                                        @include('chunks.categoriesoptionEdit', $allcategory)
                                   @endforeach
                                 @endif
                             </select>
                        </div>
                        <div class="form-group">
                            <label>description</label>
                            <textarea name="desc"  id="desc" class="form-control" rows="3" cols="6">{!! $data['category']->description !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Publish</label>
                            <select name="status"  id="status" class="form-control">

                                <option @if($data['category']->status == 'publish'){ selected = "selected" } @endif  value="publish">Publish</option>
                                <option @if($data['category']->status == 'deleted'){ selected = "selected" }@endif  value="deleted">UnPublish</option>

                            </select>
                        </div>
                        <div class="portlet-body">

                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#generalSeo" data-toggle="tab">General</a>
                                </li>
                                <li>
                                    <a href="#advancedSeo" data-toggle="tab">Advanced</a>
                                </li>

                            </ul>

                            <div class="tab-content">

                                <div class="tab-pane active" id="generalSeo">
                                    <?php if(Session::get('connection') == 'themusthaves'){
                                        $con_title = 'The Musthaves';
                                    }  else if(Session::get('connection') == 'musthavesforreal' ){
                                        $con_title = 'Musthaves For Real';
                                    }?>
                                    <input name="con_title" type="hidden" id="con_title" value="{{$con_title}}">
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Search result example</div>
                                        <div class="col-md-6">
                                            <span id="example_title" class="exmp_title"><span id="link_title">{{$data['category']->name}}</span> | <?php echo $con_title; ?></span><br>
                                            <span id="example_url" class="exmp_url">{{ url('/') }}/product-category/<span id="link_slug">{{ $data['category']->slug}}/</span></span><br>
                                            <span id="example_desc" class="exmp_desc" >{{limit_paragraph($data['category']->description, 200)}}</span>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Title</div>
                                        <div class="col-md-6">
                                            <input type="text" id="seo_title" name="seo_title" value="{{ $data['category']['seo_details']['title']}}"  class=" form-control input-sm">
                                            <span id="title_count" class="count_alert"></span>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Description</div>
                                        <div class="col-md-6">
                                        <textarea  id="seo_desc"  name="seo_desc" rows="5" class=" form-control input-sm">{{$data['category']['seo_details']['description']}}
                                            </textarea><span id="desc_count"  class="count_alert"></span>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                        <hr>
                                    </div>

                                </div>
                                <div class="tab-pane" id="advancedSeo"  >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Index/Non Index</label>
                                        <div class="col-md-9">
                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_index" value="index" <?php if($data['category']['seo_details']['is_index'] == '1' || $data['category']['seo_details']['is_index'] == ''){ echo "checked";} ?> ></div> Index</label>
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_index"  value="non-index" <?php if($data['category']['seo_details']['is_index'] == '0'){ echo "checked";} ?> ></div> Non Index</label>

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Follow/No Follow</label>
                                        <div class="col-md-9">
                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_follow"  value="follow" <?php if($data['category']['seo_details']['is_follow'] == '1'  || $data['category']['seo_details']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_follow"  value="no-follow" <?php if($data['category']['seo_details']['is_follow'] == '0'){ echo "checked";} ?>></div> No Follow</label>

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Canonical URL</div>
                                        <div class="col-md-9">
                                            <input type="text" id="can_url" name="can_url" value="<?php if($data['category']['seo_details']['canonical_url'] == ''){ echo url('/').'/product-category/'.$data['category']->slug."/"; } else{ echo $data['category']['seo_details']['canonical_url'] ;}?>"  class="form-control input-sm">

                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Redirect URL</div>
                                        <div class="col-md-9">
                                            <input type="text" id="red_url" name="red_url" value="{{$data['category']['seo_details']['redirect']}}"  class="form-control input-sm">

                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>

                            </div>

                        </div>
                        
                     
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn blue">Update</button>
                        {{--<button type="button" class="btn default">Cancel</button>--}}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>

</div>



@endsection

@section('scripts')
    {!! HTML::script('assets/js/product_category.js') !!}
@endsection