
@extends('layouts.master_angular')

@section('css')
{!! HTML::style('assets/css/post_plugins.css') !!}

@endsection

@section('content')

        <!-- BEGIN PAGE BAR -->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
                    <li><a href={{ route('posts.index') }}>Blogberichten</a></li>
                </ul>

            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->
            <h3 class="page-title"> Blogberichten
                <small>Blogbericht wijzigen</small>
            </h3>
            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->

            {{--<form id="post-form" action="{{action('PostsController@update' , $postByid->id )}}"  method="patch">--}}

            {!! Form::open(array('method' => 'patch', 'id'=> 'post-form' ,'action' => array('PostsController@update',$postByid->id))) !!}
            <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
            <div class="row">
                @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        {{ Session::get('flash_message') }}
                    </div>

                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-md-9">
                    <div class="form-group">
                        <label>Naam</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ $postByid->title }}">
                        <input type="hidden" name="post_id" value="{{ $postByid->id }}" id="post_id">

                        <p id="perma-link-section">
                            <strong>Permalink:</strong> {{ url('/') }}/post/<span
                                    id="post-slug">{{ $postByid->slug }}</span>
                            <button class="btn btn-sm blue-soft btn-outline edit-slug-with-id"><i class="fa fa-pencil"></i></button>

                        </p>
                    </div>
                    <div class="form-group">

                        <input type="hidden" class="form-control" name="slug" id="slug" value="{{ $postByid->slug }}">


                    </div>
                    <div class="portlet box default">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i>Bericht beschrijving
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <textarea class="form-control" name="post-editor" id="post-editor">{{$postByid->description }}</textarea>
                        </div>
                    </div>
                    <div class="portlet box default">
                        <div class="portlet-title">
                            <div class="caption">
                                </i>SEO Afdeling
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                            </div>
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
                                            <span id="example_title" class="exmp_title"><span id="link_title">{{$postByid->title}}</span> | <?php echo $con_title; ?></span><br>
                                            <span id="example_url" class="exmp_url">{{ url('/') }}/post/<span id="link_slug">{{ $postByid->slug}}/</span></span><br>
                                            <span id="example_desc" class="exmp_desc" >{{limit_paragraph($postByid->description, 400)}}</span>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Titel</div>
                                        <div class="col-md-6">
                                            <input type="text" id="seo_title" name="seo_title" value="{{ $postByid['seo_details']['title']}}"  class=" form-control input-sm">
                                            <span id="title_count" class="count_alert"></span>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Beschrijving</div>
                                        <div class="col-md-6">
                                        <textarea  id="seo_desc"  name="seo_desc" rows="5" class=" form-control input-sm">{{$postByid['seo_details']['description']}}
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
                                                        <input type="radio" name="is_index" value="index" <?php if($postByid['seo_details']['is_index'] == '1' || $postByid['seo_details']['is_index'] == ''){ echo "checked";} ?> ></div> Index</label>
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_index"  value="non-index" <?php if($postByid['seo_details']['is_index'] == '0'){ echo "checked";} ?> ></div> No Index</label>

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
                                                        <input type="radio" name="is_follow"  value="follow" <?php if($postByid['seo_details']['is_follow'] == '1'  || $postByid['seo_details']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                <label class="radio-inline">
                                                    <div class="radio">
                                                        <input type="radio" name="is_follow"  value="no-follow" <?php if($postByid['seo_details']['is_follow'] == '0'){ echo "checked";} ?>></div> No Follow</label>

                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Canonieke URL</div>
                                        <div class="col-md-9">
                                            <input type="text" id="can_url" name="can_url" value="<?php if($postByid['seo_details']['canonical_url'] == ''){ echo url('/').'/post/'.$postByid->slug."/"; } else{ echo $postByid['seo_details']['canonical_url'] ;}?>"  class="form-control input-sm">

                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Redirect URL</div>
                                        <div class="col-md-9">
                                            <input type="text" id="red_url" name="red_url" value="{{$postByid['seo_details']['redirect']}}"  class="form-control input-sm">

                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                <div class="col-md-3">
                    <div class="portlet box default">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gear"></i>Publiceren
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <input type="hidden" name="action" id="form-action" value="">
                            <button  id="publish"  class="btn btn-danger pull-left btn-sm publish-post">Bijwerken</button>
                            <button  id="draft"  class="btn btn-primary pull-right btn-sm ">Concept</button>

                            <div class="clearfix"></div>
                            <hr>
                            <ul class="published">
                                <li><i class="fa fa-key"></i> Status: <strong class="showpub">{{ucfirst($postByid->status) }}</strong> <a class="editpublish" href="javascript:;">Wijzigen</a>
                                    <div id="available-status" class="row" style="display:none">
                                        <div class="col-md-12">
                                            <select name="status" id="status" class="form-control" >
                                                <option value="publish"    @if($postByid->status == 'publish' ) selected = "selected"  @endif>Gepubliceerd</option>
                                                <option value="deleted"  @if($postByid->status == 'deleted' ) selected = "selected" @endif >Verwijderd</option>
                                                <option value="draft" @if($postByid->status == 'draft' ) selected = "selected" @endif >Concept</option>

                                            </select>
                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="col-md-12">

                                            <button class="btn btn-default btn-sm addstatus">OK</button><a  class="btn btn-default btn-sm cancelpublish"  href="javascript:;">Annuleren</a>
                                        </div> </div>
                                </li>
                                <li><i class="fa fa-eye"></i> Zichtbaarheid: <strong class="showvis">{{  ucfirst($postByid->visibilty) }}</strong> <a class="editvisible" href="javascript:;">Wijzigen</a>
                                    <div id="available-visible" class="row" style="display:none">
                                        <div class="col-md-12">
                                            <select name="visible" id="visible" class="form-control" >
                                                <option value="visible"   @if($postByid->visibilty == 'visible' ) selected = "selected" @else  @endif>Zichtbaar</option>
                                                <option value="hidden"   @if($postByid->visibilty == 'hidden' ) selected = "selected" @else  @endif>Verborgen</option>

                                            </select>
                                        </div><div class="clearfix"></div>
                                        <div class="col-md-12"><button class="btn btn-default btn-sm addvisible">OK</button><a class="btn btn-default btn-sm cancelvisible"  href="javascript:;">Annuleren</a></div>
                                    </div></li>
                                <li><i class="icon-calendar"></i> Geplaatst op: <strong class="showdate">{{ date("d F,Y H:i" ,strtotime($postByid->published_at)) }}</strong> <a class="editcal"
                                                                                                                                                                              href="javascript:;">Wijzigen</a>
                                    <div id="available-calender" class="row" style="display:none">
                                        <div class="col-md-12">
                                            <?php

                                            $dateExp = explode(' ',$postByid->published_at);
                                            $datePart = $dateExp[0];
                                            $datePartExp = explode('-',$datePart);
                                            $yy = $datePartExp[0];
                                            $mm = $datePartExp[1];
                                            $dd = $datePartExp[2];

                                            $timePart = $dateExp[1];
                                            $timePartExp = explode(':',$timePart);
                                            $hr = $timePartExp[0];
                                            $min = $timePartExp[1];
                                            ?>

                                            <select style="width:80px; padding:0px" name="mm" id="mm"  >
                                                @for($i = 1 ; $i<= 12; $i++)
                                                    {{ $i = sprintf("%02d", $i) }}
                                                    <option value="{{ $i }}" <?php if($i == $mm)  { echo "selected" ;} ?>   >{{ $i }}- {{  date("M", mktime(0, 0, 0, $i, 10)) }}</option>
                                                @endfor
                                            </select>

                                            <input style="width:20px; padding:0px" size="2" type="text" name="dd" id="dd" value="{{$dd}}" >,
                                            <input style="width:40px; padding:0px" size="4" type="text" name="yy" id="yy" value="{{$yy}}" >@
                                            <input style="width:20px; padding:0px"   size="2" type="text" name="hr" id="hr" value="{{$hr}}" >:
                                            <input style="width:20px; padding:0px" size="2" type="text" name="min" id="min" value="{{$min}}" >




                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-12"><button class="btn btn-default btn-sm addcal">OK</button>
                                            <a class="btn btn-default btn-sm cancelcal"  href="javascript:;">Annuleren</a></div>
                                    </div></li>
                            </ul>

                            <hr>
                            <button  id="trash"  class="btn btn-primary pull-right btn-sm ">Verwijderen</button>


                        </div>

                    </div>
                    <div class="portlet box default">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-bars"></i>Bericht categorieën
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row category-checkbox-list">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1_1" data-toggle="tab"> Alle bericht categorieën </a></li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="tab_1_1">
                                        {{-- @foreach($CategoriesById as $categoryId)--}}
                                        <ul class="all-post-categories">
                                            {{--{{array_count_values($categories['selected'])}}--}}
                                            @if (count($categories) > 0)
                                                <ul>
                                                    @foreach ($categories  as $category)

                                                        @include('chunks.postcategories', array('category' => $category, 'selected'=> [] ))


                                                    @endforeach
                                                </ul>

                                            @endif
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">

                            <a href="javascript:;" class="btn btn-sm btn-default addnewpostcategory"> <i class="fa fa-plus"></i> Nieuwe bericht categorie toevoegen</a>
                            <div class="top10"></div>
                            <div id="new-post-section" style="display:none">
                                <input type="hidden" name="product-choose-category" id="product-choose-category">
                                <div class="col-md-12 col-sm-12">

                                    <input type="text" class="form-control input-sm" id="add-new-category">
                                </div>
                                <div class="clearfix"></div>
                                <div class="top5"></div>
                                <div class="col-md-12 col-sm-12">
                                    <select class="form-control input-sm" id="parent-category">
                                        <option></option>
                                        @if (count($categories) > 0)

                                            @foreach ($categories as $category)
                                                @include('chunks.categoriesoption', $category)
                                            @endforeach


                                        @endif
                                    </select>


                                </div>
                                <div class="clearfix"></div>
                                <div class="top5"></div>
                                <div class="col-md-12 col-sm-12">
                                    <button class="btn btn-primary btn-sm add-new-category-btn">Nieuwe bericht categorie toevoegen
                                    </button>
                                </div>

                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="portlet box default">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-tags"></i>Bericht tags
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body" style="line-height:30px">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control input-sm typeahead" name="tag" id="new-tag">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-default btn-sm addtag">Toevoegen</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <small><em>Tags met komma scheiden</em></small>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <ul class="tags">
                                        @foreach($postByid['posttags'] as $selectedTag)
                                            <li><a href="javascript:;" class="remove-tag" data-tag-id="{{$selectedTag['id']}}"><i class="fa fa-remove"></i><input class="tag{{$selectedTag['id']}}"  name="tags[]" id="tags[]" type="hidden" value="{{$selectedTag['id']}}" ></a>{{$selectedTag['name']}} </li>

                                        @endforeach
                                    </ul>
                                </div>


                                <div class="col-md-12 col-sm-12">
                                    <hr>
                                    <a href="javascript:;" class="available-tags">Kies uit beschikbare tags</a>
                                    <div class="clearfix"></div>
                                    <div id="available-tags" style="display:none">
                                        @foreach($tags as $t)
                                            <a href="javascript:;" class="add-available-tag" data-tag-id="{!!$t->id!!}">{{$t->name}}</a>
                                        @endforeach
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="portlet box default">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i>Uitgelichte afbeelding
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <a class="show-images-btn"  data-title="featured" data-toggle="modal" href="#full">Kies uitgelichte afbeelding</a>
                            <div class="show-image-section">
                                <div class="display-image" >
                                    @if(count($postByid['media_featured_image']) > 0)


                                        {!! HTML::image($postByid['media_featured_image']['path'],$postByid['media_featured_image']['alt_text'], array('width' => '186px' , 'height' => '285px')) !!}

                                        <input type="hidden" name="old_image_id" id="old_image_id" value="{{$postByid['media_featured_image']['id']}}"  >
                                        <a class="remove-images-btn" data-img-id='{{$postByid['media_featured_image']['id']}}'>Remove featured image</a>

                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>



                </div>

            </div>
            </form>

            <div class="modal fade" id="tag-already-available" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Choose Tags</h4>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="button" class="btn green">Save changes</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
@extends('includes.gallery')

@endsection

@section('scripts')
    {!! HTML::script('assets/js/post_plugins.js') !!}
    {!! HTML::script('assets/plugins/ckeditor/ckeditor.js') !!}
    {!! HTML::script('assets/js/posts.js') !!}
    {!! HTML::script('assets/js/seo.js') !!}
    {!! HTML::script('assets/js/angular_js.js') !!}
    {!! HTML::script('assets/js/media_angularjs.js') !!}
@endsection
