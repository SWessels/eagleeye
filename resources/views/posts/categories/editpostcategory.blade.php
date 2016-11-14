
@extends('layouts.master')
@section('css')

    {!! HTML::style('assets/css/post_plugins.css') !!}
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
                <a href="/">Posts</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <a href="{{ route('PostCategories.index') }}"> <span>Bericht categorieën</span></a>
            </li>
        </ul>

    </div>
    <br>
    <div class="page-bar">
        {{--<ul class="page-breadcrumb">--}}
        {{--<li> <a href="../pages/index.html">Home</a>  </li>--}}

        {{--<a href="{{ route('categories.index') }}"> <span>Categorieën</span></a>--}}
        {{--</ul>--}}
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif

    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> Bericht categorie wijzigen
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
                    {!! Form::open(array('method' => 'patch' ,'action' => array('PostCategoryController@update',$data['category']->id))) !!}

                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                    <div class="form-body">

                        <div class="form-group">
                            <label>Naam</label>

                                <input name="name" id="name" type="text" class="form-control"  value="{!! $data['category']->name !!}" >
                        </div>
                        <div class="form-group">
                            <label>Slug</label>

                                <input name="slug" id="slug" type="text" class="form-control input-sm" value="{!! $data['category']->slug !!}" >
                        </div>
                        <div class="form-group">
                            <label>Hoofdcategorie
                                {{-- @foreach ($data['category'] as $category)
                                   <pre>  {{ print_r($category)  }}</pre>
                                     <br /> @endforeach

                                  @foreach ($data['allcategory'] as $allcategory)
                                   <pre>  {{ print_r($allcategory)  }}</pre>
                                     <br /> @endforeach--}}


                            </label>
                            <select name="parent"  id="parent" class="form-control">
                                <option value="">Nee</option>
                                @if (count($data['allcategory']) > 0)

                                    @foreach ($data['allcategory'] as $allcategory)
                                        @include('chunks.categoriesoptionEdit', $allcategory)
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Publiceren</label>
                            <select name="status"  id="status" class="form-control">

                                <option @if($data['category']->status == 'publish'){ selected = "selected" } @endif  value="publish">Publiceren</option>
                                <option @if($data['category']->status == 'deleted'){ selected = "selected" }@endif  value="deleted">Concept</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Beschrijving</label>
                            <textarea name="desc"  id="desc" class="form-control" rows="3" cols="6">{!! $data['category']->description !!}</textarea>
                        </div>
                        <div class="portlet-body">

                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#generalSeo" data-toggle="tab">Algemeen</a>
                                </li>
                                <li>
                                    <a href="#advancedSeo" data-toggle="tab">Geavanceerd</a>
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
                                        <div class="col-md-3 control-label">Voorbeeld zoekresultaat</div>
                                        <div class="col-md-6">
                                            <span id="example_title" class="exmp_title"><span id="link_title">{{$data['category']->name}}</span> | <?php echo $con_title; ?></span><br>
                                            <span id="example_url" class="exmp_url">{{ url('/') }}/post-category/<span id="link_slug">{{ $data['category']->slug}}/</span></span><br>
                                            <span id="example_desc" class="exmp_desc" >{{limit_paragraph($data['category']->description, 200)}}</span>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Titel</div>
                                        <div class="col-md-6">
                                            <input type="text" id="seo_title" name="seo_title" value="{{ $data['category']['seo_details']['title']}}"  class=" form-control input-sm">
                                            <span id="title_count" class="count_alert"></span>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Beschrijving</div>
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
                                        <label class="col-md-3 control-label">Index/No Index</label>
                                        <div class="col-md-9">
                                            <div class="radio-list">
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_index" value="index" <?php if($data['category']['seo_details']['is_index'] == '1' || $data['category']['seo_details']['is_index'] == ''){ echo "checked";} ?> ></div> Index</label>
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_index"  value="non-index" <?php if($data['category']['seo_details']['is_index'] == '0'){ echo "checked";} ?> ></div> No Index</label>

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
                                        <div class="col-md-3 control-label">Canonieke URL</div>
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
                        <button type="submit" class="btn blue">Bijwerken</button>
                        {{--<button type="button" class="btn default">Annuleren</button>--}}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

        </div>

    </div>



@endsection

@section('scripts')
    {!! HTML::script('assets/js/post_category.js') !!}
@endsection