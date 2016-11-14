
@extends('layouts.master')
@section('css')

@endsection
@section('content')


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

        {{--<li> <span>Categories</span> </li>--}}
    {{--</ul>--}}
    <div class="page-toolbar">

    </div>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Add New Category
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
    <div class="col-md-12 ">
    <div class="portlet-body">
        <div id="context" data-toggle="context" data-target="#context-menu">
            <p>Product categories for your store can be managed here. To change the order of categories in the frontend,
                you can use drag and drop to sort them. To see more categories, click the "display options"
                link at the top of the page </p>
        </div>
        </div>
        <div class="col-md-8 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">

                <div class="portlet-body form">
                    <form role="form" action=" {{action('CategoriesController@store')}} " method="post">
                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                        <div class="form-body">

                            <div class="form-group">
                                <label>Name</label>
                                <div class="input-icon">
                                  <!--  <i class="fa fa-bell-o font-green"></i>-->
                                    <input name="name" id="name" type="text" class="form-control" > </div>
                            </div>
                            <div class="form-group">
                                <label>Slug</label>
                                <div class="input-icon input-icon-sm">
                                   <!-- <i class="fa fa-bell-o"></i>-->
                                    <input name="slug" id="slug" type="text" class="form-control input-sm" > </div>
                            </div>

                            <div class="form-group">
                                <label>Parent</label>
                                <select name="parent"  id="parent" class="form-control">
                                    <option value="0">No</option>
                                    @if (count($data['category']) > 0)

                                                    @foreach ($data['category'] as $category)
                                                        @include('chunks.categoriesoption', $category)
                                                    @endforeach


                                                @endif


                                </select>
                            </div>
                           <div class="form-group">
                            <label>description</label>
                            <textarea name="desc"  id="desc" class="form-control" rows="3" cols="4"></textarea>
                        </div>
                            <div class="form-group">
                                <label>Publish</label>
                                <select name="status"  id="status" class="form-control">
                                    <option value="publish">Publish</option>
                                    <option value="deleted">UnPublish</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn blue">Submit</button>
                            {{--<button type="button" class="btn default">Cancel</button>--}}
                        </div>
                    </form>
                </div>

                <div class="portlet box default">
                    <div class="portlet-title">
                        <div class="caption">
                            </i>SEO Section
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                        </div>
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
                                <?php

                                if(Session::get('connection') == 'themusthaves'){
                                    $con_title = 'The Musthaves';
                                }
                                else if(Session::get('connection') == 'musthavesforreal' ){
                                    $con_title = 'Musthaves For Real';
                                }?>
                                <input name="con_title" type="hidden" id="con_title" value="{{$con_title}}">
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Search result example</div>
                                    <div class="col-md-6">
                                        <span id="example_title" class="exmp_title"><span id="link_title">Title</span> | <?php echo $con_title; ?></span><br>
                                        <span id="example_url" class="exmp_url">{{ url('/') }}/page/<span id="link_slug"></span></span><br>
                                        <span id="example_desc" class="exmp_desc" ></span>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Title</div>
                                    <div class="col-md-6">
                                        <input type="text" id="seo_title" name="seo_title" value=""  class=" form-control input-sm">
                                        <span id="title_count" class="count_alert"></span>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Description</div>
                                    <div class="col-md-6">
                                            <textarea  id="seo_desc"  name="seo_desc" rows="3" class=" form-control input-sm">
                                                </textarea><span id="desc_count" class="count_alert"></span>
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
                                                    <input type="radio" name="is_index" value="index" checked></div> Index</label>
                                            <label class="radio-inline">
                                                <div class="radio">
                                                    <input type="radio" name="is_index"  value="non-index" ></div> Non Index</label>

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
                                                    <input type="radio" name="is_follow"  value="follow" checked></div> Follow</label>
                                            <label class="radio-inline">
                                                <div class="radio">
                                                    <input type="radio" name="is_follow"  value="no-follow" ></div> No Follow</label>

                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Canonical URL</div>
                                    <div class="col-md-9">
                                        <input type="text" id="can_url" name="can_url" value=""  class="form-control input-sm">

                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Redirect URL</div>
                                    <div class="col-md-9">
                                        <input type="text" id="red_url" name="red_url" value=""  class="form-control input-sm">

                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

</div>



@endsection