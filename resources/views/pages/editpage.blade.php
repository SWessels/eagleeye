@extends('layouts.master')

@section('css')

{!! HTML::style('assets/css/post_plugins.css') !!}
@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
        <li><a href={{ route('posts.index') }}>Pagina's</a></li>
    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Pagina's
    <small>Pagina wijzigen</small>
</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->

{{--<form id="post-form" action="{{action('PostsController@update' , $postByid->id )}}"  method="patch">--}}

{!! Form::open(array('method' => 'patch', 'id'=> 'post-form' ,'action' => array('PagesController@update',$pageByid->id))) !!}
<input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
<div class="row">
    @if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>

    @endif<div class="col-md-9">
        <div class="form-group">
            <label>Naam</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $pageByid->title }}">
            <input type="hidden" name="page_id" value="{{ $pageByid->id }}" id="page_id">
            <p id="perma-link-section">
                <strong>Permalink:</strong> {{ url('/') }}/post/<span
                        id="page-slug">{{ $pageByid->slug }}</span>
                <button class="btn btn-sm blue-soft btn-outline edit-slug-with-id"><i class="fa fa-pencil"></i></button>

            </p>
        </div>
        <div class="form-group">

            <input type="hidden" class="form-control" name="slug" id="slug" value="{{ $pageByid->slug }}">


        </div>
        <div class="portlet box default">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Inhoud pagina
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <textarea class="form-control" name="page-editor" id="page-editor">{{$pageByid->description }}</textarea>
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
                                    <span id="example_title" class="exmp_title"><span id="link_title">{{$pageByid->title}}</span> | <?php echo $con_title; ?></span><br>
                                    <span id="example_url" class="exmp_url">{{ url('/') }}/page/<span id="link_slug">{{ $pageByid->slug}}/</span></span><br>
                                    <span id="example_desc" class="exmp_desc" >{{limit_paragraph($pageByid->description, 200)}}</span>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Titel</div>
                                <div class="col-md-6">
                                    <input type="text" id="seo_title" name="seo_title" value="{{ $pageByid['seo_details']['title']}}"  class=" form-control input-sm">
                                    <span id="title_count" class="count_alert"></span>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Omschrijving</div>
                                <div class="col-md-6">
                                        <textarea  id="seo_desc"  name="seo_desc" rows="5" class=" form-control input-sm">{{$pageByid['seo_details']['description']}}
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
                                                <input type="radio" name="is_index" value="index" <?php if($pageByid['seo_details']['is_index'] == '1' || $pageByid['seo_details']['is_index'] == ''){ echo "checked";} ?> ></div> Index</label>
                                        <label class="radio-inline">
                                            <div class="radio">
                                                <input type="radio" name="is_index"  value="non-index" <?php if($pageByid['seo_details']['is_index'] == '0'){ echo "checked";} ?> ></div> No Index</label>

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
                                                <input type="radio" name="is_follow"  value="follow" <?php if($pageByid['seo_details']['is_follow'] == '1'  || $pageByid['seo_details']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                        <label class="radio-inline">
                                            <div class="radio">
                                                <input type="radio" name="is_follow"  value="no-follow" <?php if($pageByid['seo_details']['is_follow'] == '0'){ echo "checked";} ?>></div> No Follow</label>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Canonieke URL</div>
                                <div class="col-md-9">
                                    <input type="text" id="can_url" name="can_url" value="<?php if($pageByid['seo_details']['canonical_url'] == ''){ echo url('/').'/page/'.$pageByid->slug."/"; } else{ echo $pageByid['seo_details']['canonical_url'] ;}?>"  class="form-control input-sm">

                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Redirect URL</div>
                                <div class="col-md-9">
                                    <input type="text" id="red_url" name="red_url" value="{{$pageByid['seo_details']['redirect']}}"  class="form-control input-sm">

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
                <input name="action" id="publish" type="submit" value="update" class="btn btn-danger pull-left btn-sm publish-post">
                <input name="action" id="draft" type="submit" value="draft" class="btn btn-primary pull-right btn-sm ">
                <div class="clearfix"></div>
                <hr>
                <ul class="published">
                    <li><i class="fa fa-key"></i> Status: <strong class="showpub">{{ucfirst($pageByid->status) }}</strong> <a class="editpublish" href="javascript:;">Wijzigen</a>
                        <div id="available-status" class="row" style="display:none">
                            <div class="col-md-12">
                                <select name="status" id="status" class="form-control" >
                                    <option value="publish"    @if($pageByid->status == 'publish' ) selected = "selected"  @endif>Gepubliceerd</option>
                                    <option value="deleted"  @if($pageByid->status == 'deleted' ) selected = "selected" @endif >Verwijderd</option>
                                    <option value="draft" @if($pageByid->status == 'draft' ) selected = "selected" @endif >Concept</option>

                                </select>
                            </div>
                            <div class="clearfix"></div>

                            <div class="col-md-12">

                                <button class="btn btn-default btn-sm addstatus">OK</button><a  class="btn btn-default btn-sm cancelpublish"  href="javascript:;">Annuleren</a>
                            </div> </div>
                    </li>
                    <li><i class="fa fa-eye"></i> Zichtbaarheid: <strong class="showvis">{{  ucfirst($pageByid->visibilty) }}</strong> <a class="editvisible" href="javascript:;">Wijzigen</a>
                        <div id="available-visible" class="row" style="display:none">
                            <div class="col-md-12">
                                <select name="visible" id="visible" class="form-control" >
                                    <option value="visible"   @if($pageByid->visibilty == 'visible' ) selected = "selected" @else  @endif>Zichtbaar</option>
                                    <option value="hidden"   @if($pageByid->visibilty == 'hidden' ) selected = "selected" @else  @endif>Verborgen</option>

                                </select>
                            </div><div class="clearfix"></div>
                            <div class="col-md-12"><button class="btn btn-default btn-sm addvisible">OK</button><a class="btn btn-default btn-sm cancelvisible"  href="javascript:;">Annuleren</a></div>
                        </div></li>
                    <li><i class="icon-calendar"></i> Geplaatst op: <strong class="showdate">{{ date("d F,Y H:i" ,strtotime($pageByid->published_at)) }}</strong> <a class="editcal"
                                                                                                                                                                  href="javascript:;">Wijzigen</a>
                        <div id="available-calender" class="row" style="display:none">
                            <div class="col-md-12">
                                <?php

                                $dateExp = explode(' ',$pageByid->published_at);
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
                <input name="action" id="trash" type="submit" value="Verwijderen" class="btn btn-primary pull-right btn-sm ">

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




@endsection

@section('scripts')
    {!! HTML::script('assets/js/post_plugins.js') !!}
    {!! HTML::script('assets/plugins/ckeditor/ckeditor.js') !!}

    {!! HTML::script('assets/js/global.js') !!}
    {!! HTML::script('assets/js/pages.js') !!}
    {!! HTML::script('assets/js/seo.js') !!}
@endsection