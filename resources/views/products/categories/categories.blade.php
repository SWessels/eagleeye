@extends('layouts.master')

@section('css')
@endsection

@section('content')

        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Home</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <span>Categories</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}



<div class="row">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="col-md-4">
        <div class="portlet-body">
            <div id="context" data-toggle="context" data-target="#context-menu">
                <p>Product categories for your store can be managed here. To change the order of categories in the frontend. To see more categories, click the "display options"
                    link at the top of the page </p>
            </div>
        </div>

    <div class="col-md-12">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-red-sunglo">
                    <i class="icon-social-dribbble font-blue-sharp"></i>
                    <span class="caption-subject font-blue-sharp bold uppercase">Add New category</span>
                </div>

            </div>
            <div class="portlet-body form">
                <form role="form" id="category_add" action=" {{action('CategoriesController@store')}} " method="post">
                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                    <div class="form-body">

                        <div class="form-group">
                            <label>Name</label>

                                <input name="name" id="name" type="text" class="form-control" >
                        </div>
                        <div class="form-group">
                            <label>Slug</label>

                                <input name="slug" id="slug" type="text" class="form-control input-sm" >
                        </div>

                        <div class="form-group">
                            <label>Parent</label>
                            <select name="parent"  id="parent" class="form-control">
                                <option value="0">No</option>
                                @if (count($categories) > 0)

                                    @foreach ($categories as $category)
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
                                            <label>Search result example</label><br>
                                            <span id="example_title" class="exmp_title"><span id="link_title">Title</span> | <?php echo $con_title; ?></span><br>
                                            <span id="example_url" class="exmp_url">{{ url('/') }}/product-category/<span id="link_slug"></span></span><br>
                                            <span id="example_desc" class="exmp_desc" ></span>

                                        </div>
                                        <div class="form-group">
                                            <label>Title</label><br>
                                            <input type="text" id="seo_title" name="seo_title" value=""  class=" form-control input-sm"><br>
                                            <span id="title_count" class="count_alert"></span>

                                        </div>
                                        <div class="form-group">
                                            <label>Description</label><br>
                                              <textarea  id="seo_desc"  name="seo_desc" rows="3" class=" form-control input-sm">
                                                </textarea><span id="desc_count" class="count_alert"></span>

                                        </div>
                                   </div>
                                <div class="tab-pane" id="advancedSeo"  >
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Index/Non Index</label>
                                        <div class="col-md-9">
                                            <div class="radio-list">
                                                <label>
                                                    <div class="radio">
                                                        <input type="radio" name="is_index" value="index" checked></div> Index</label>
                                                <label>
                                                    <div class="radio">
                                                        <input type="radio" name="is_index"  value="non-index" ></div> Non Index</label>
                                             </div>
                                        </div>
                                    </div>   <div class="clearfix"></div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Follow/No Follow</label>
                                        <div class="col-md-9">
                                            <div class="radio-list">
                                                <label>
                                                    <div class="radio">
                                                        <input type="radio" name="is_follow"  value="follow" checked></div> Follow</label>
                                                <label>
                                                    <div class="radio">
                                                        <input type="radio" name="is_follow"  value="no-follow" ></div> No Follow</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Canonical URL</label><br>
                                        <input type="text" id="can_url" name="can_url" value=""  class="form-control input-sm">
                                    </div>

                                    <div class="form-group">
                                        <label>Redirect URL</label><br>
                                        <input type="text" id="red_url" name="red_url" value=""  class="form-control input-sm">
                                    </div>


                                </div>

                            </div>

                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn blue">Submit</button>
                        {{--<button type="button" class="btn default">Cancel</button>--}}
                    </div>
                </form>
            </div>
        </div>

    </div>
        </div>
    <div class="col-md-8">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
            @endif

                    <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                       Product Categories </div>

                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6">
                                    All({{ count($categories) }})
                                </div>
                                <div class="col-sm-6">
                                    <form class="pull-right col-sm-6 npall nmall">
                                        <div class="form-group">
                                            <div class="input-group input-group-sm hide">
                                                <input type="text" class="form-control" placeholder="Search">
                                                    <span class="input-group-btn">
                                                        <button class="btn red" type="button">Search</button>
                                                    </span>
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover" id="datatable_products">
                            <thead>
                            <tr role="row" class="heading">
                                {{--<th width="5%">
                                    <input type="checkbox" class="group-checkable">
                                </th>--}}
                                <th width="20%"> Name </th>
                                <th width="25%"> Description </th>
                                <th width="18%"> Slug </th>
                                <th width="3%"> Count </th>
                               
                            </tr>

                            </thead>
                            <tbody>




                            @if(count($categories) > 0)

                                @foreach($categories as $category)
                                
                                    @include('chunks.categorieslist', $category)
                                @endforeach
                            @else
                                <tr>
                                    <td align="center" colspan="7"><h4>No Categories Found</h4></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                        {{--@if(count($categories) > 0)
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $categories->links() !!}

                                </div>
                            </div>
                        @endif--}}
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
    </div>
</div>

{{-- end of page content --}}
@endsection



@section('scripts')
    {!! HTML::script('assets/js/product_category.js') !!}

@endsection

