@extends('layouts.master')

@section('css')

{!! HTML::style('assets/css/post_plugins.css') !!}
@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
        <li><a href={{ route('pages.index') }}>Pagina's</a></li>
    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Pagina's
    <small>Pagina toevoegen</small>
</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->

<form id="post-form" action="{{action('PagesController@store')}}"  method="post">
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
                <input type="text" class="form-control" name="name" id="name" value="">
                <input type="hidden" name="page_id" value="" id="page_id">
                <p id="perma-link-section">
                    <strong>Permalink:</strong> {{ url('/') }}/pages/<span
                            id="page-slug"></span>
                    <button class="btn btn-sm blue-soft btn-outline edit-slug"><i class="fa fa-pencil"></i></button>

                </p>
            </div>
            <div class="form-group">

                <input type="hidden" class="form-control" name="slug" id="slug" value="">


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
                    <textarea class="form-control" name="page-editor" id="page-editor"></textarea>
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
                            <?php

                            if(Session::get('connection') == 'themusthaves'){
                                $con_title = 'The Musthaves';
                            }
                            else if(Session::get('connection') == 'musthavesforreal' ){
                                $con_title = 'Musthaves For Real';
                            }?>
                            <input name="con_title" type="hidden" id="con_title" value="{{$con_title}}">

                            <div class="form-group">
                                <div class="col-md-3 control-label">Voorbeeld zoekresultaat</div>
                                <div class="col-md-6">
                                    <span id="example_title" class="exmp_title"><span id="link_title">Title</span> | <?php echo $con_title; ?></span><br>
                                    <span id="example_url" class="exmp_url">{{ url('/') }}/page/<span id="link_slug"></span></span><br>
                                    <span id="example_desc" class="exmp_desc" ></span>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Titel</div>
                                <div class="col-md-6">
                                    <input type="text" id="seo_title" name="seo_title" value=""  class=" form-control input-sm">
                                    <span id="title_count" class="count_alert"></span>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Omschrijving</div>
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
                                <label class="col-md-3 control-label">Index/No Index</label>
                                <div class="col-md-9">
                                    <div class="radio-list">
                                        <label class="radio-inline">
                                            <div class="radio">
                                                <input type="radio" name="is_index" value="index" checked></div> Index</label>
                                        <label class="radio-inline">
                                            <div class="radio">
                                                <input type="radio" name="is_index"  value="non-index" ></div> No Index</label>

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
                                <div class="col-md-3 control-label">Canonieke URL</div>
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
                    <input name="action" id="publish" type="submit" value="publish" class="btn btn-danger pull-left btn-sm publish-post">
                    <input name="action" id="draft" type="submit" value="draft" class="btn btn-primary pull-right btn-sm ">


                    <div class="clearfix"></div>
                    <hr>
                    <ul class="published">
                        <li><i class="fa fa-key"></i> Status: <strong class="showpub">Publiceren</strong> <a class="editpublish" href="javascript:;">Wijzigen</a>
                            <div id="available-status" class="row" style="display:none">
                                <div class="col-md-12">
                                    <select name="status" id="status" class="form-control" >
                                        <option value="publish">Gepubliceerd</option>
                                        <option value="deleted">Verwijderd</option>
                                        <option value="draft">Concept</option>

                                    </select>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-12">

                                    <button class="btn btn-default btn-sm addstatus">OK</button><a  class="btn btn-default btn-sm cancelpublish"  href="javascript:;">Annuleren</a>
                                </div> </div>
                        </li>
                        <li><i class="fa fa-eye"></i> Zichtbaarheid: <strong class="showvis">Zichtbaar</strong> <a class="editvisible" href="javascript:;">Wijzigen</a>
                            <div id="available-visible" class="row" style="display:none">
                                <div class="col-md-12">
                                    <select name="visible" id="visible" class="form-control" >
                                        <option value="visible">Zichtbaar</option>
                                        <option value="hidden">Verborgen</option>

                                    </select>
                                </div><div class="clearfix"></div>
                                <div class="col-md-12"><button class="btn btn-default btn-sm addvisible">OK</button><a class="btn btn-default btn-sm cancelvisible"  href="javascript:;">Annuleren</a></div>
                            </div></li>
                        <li><i class="icon-calendar"></i>Publiceer: Direct {{--<strong class="showdate">{{ date("d F,Y H:i") }}</strong>--}} <a class="editcal"
                                                                                                                                href="javascript:;">Wijzigen</a>
                            <div id="available-calender" class="row" style="display:none">
                                <div class="col-md-12">


                                    <select style="width:80px; padding:0px" name="mm" id="mm"  >
                                        @for($i = 1 ; $i<= 12; $i++)
                                            {{ $i = sprintf("%02d", $i) }}
                                            <option value="{{ $i }}" <?php if($i == date('m') ) { echo "selected" ;} ?>   >{{ $i }}- {{  date("M", mktime(0, 0, 0, $i, 10)) }}</option>
                                        @endfor
                                    </select>

                                    <input style="width:20px; padding:0px" size="2" type="text" name="dd" id="dd" value="{{date('d')}}" >,
                                    <input style="width:40px; padding:0px" size="4" type="text" name="yy" id="yy" value="{{date('Y')}}" >@
                                    <input style="width:20px; padding:0px"   size="2" type="text" name="hr" id="hr" value="{{date('H')}}" >:
                                    <input style="width:20px; padding:0px" size="2" type="text" name="min" id="min" value="{{date('i')}}" >




                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><button class="btn btn-default btn-sm addcal">OK</button>
                                    <a class="btn btn-default btn-sm cancelcal"  href="javascript:;">Annuleren</a></div>
                            </div></li>
                    </ul>

                    <hr>


                    <div class="clearfix"></div>

                </div>

            </div>





            {{-- <div class="portlet box default">
                 <div class="portlet-title">
                     <div class="caption">
                       <i class="fa fa-gift"></i>Uitgelichte afbeelding
                     </div>
                     <div class="tools">
                         <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                     </div>
                 </div>
                 <div class="portlet-body">

                     {!! HTML::image("assets/img/dummy.jpg", 'Dummy image', array('width' => '100%')) !!}

                     <a class="" data-toggle="modal" href="#full">Uitgelichte afbeelding toevoegen</a>

                 </div>

             </div>--}}

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

<div class="modal fade" id="full" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Afbeeldingen toevoegen aan afbeeldingsgalerij</h4>
            </div>
            <div class="modal-body">

                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li>
                            <a href="#tab_5_1" data-toggle="tab"> Bestanden uploaden </a>
                        </li>
                        <li class="active">
                            <a href="#tab_5_2" data-toggle="tab"> Mediabibliotheek </a>
                        </li>

                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab_5_1">
                            <form action="upload.php" class="dropzone dropzone-file-area" id="my-dropzone">

                            </form>
                        </div>
                        <div class="tab-pane active" id="tab_5_2">

                            <div class="row col-md-9 library-section">
                                <div class="row col-md-2">
                                    <select class="form-control input-sm">
                                        <option>Alle mediabestanden</option>
                                        <option>Audio</option>
                                        <option>Video</option>
                                        <option>Afbeeldingen</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control input-sm">
                                        <option>Alle datums</option>
                                        <option>16 March 2016</option>
                                        <option>15 March 2016</option>
                                        <option>14 March 2016</option>
                                    </select>
                                </div>

                                <div class="pull-right col-md-2">
                                    <input type="text" class="form-control input-sm" placeholder="zoeken">
                                </div>

                                <div class="clearfix"></div>
                                <div class="top20"></div>
                                <div class="tiles">
                                    <?php for($i = 1;$i <= 100;$i++){ ?>
                                    <div class="tile image">
                                        <div class="tile-body">
                                            {!! HTML::image("assets/img/photo3.jpg", 'Dummy image', array('width' => '100%')) !!} </div>
                                        <div class="tile-object">
                                            <div class="name"> Media</div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>


                            </div>
                            <div class="col-md-3 product-description library-section">

                                <h4>Product 1</h4>
                                <hr>
                                <div class="row col-md-6">
                                    {!! HTML::image("assets/img/photo3.jpg", 'Dummy image', array('width' => '100%')) !!}
                                </div>


                                <div class="col-md-6">
                                    <p class="top10">
                                        <strong>photo3.jpg</strong>
                                    </p>
                                    <p class="top3">
                                        92 kB
                                    </p>
                                    <p class="top3">
                                        586 × 900
                                    </p>

                                    <p class="top3">
                                        <a href="javascript:;">Afbeelding bewerken</a>
                                    </p>
                                    <p class="top3 red">
                                        <a href="javascript:;">Permanent verwijderen</a>
                                    </p>

                                </div>
                                <hr>
                                <div class="row col-md-12 col-sm-12 top15">

                                    {!! Form::open(array('method' => 'patch' ,'action' => array('ProductsController@create'))) !!}
                                    <div class="form-body">


                                        <div class="form-group">
                                            <label>URL</label>
                                            <input type="text" class="form-control input-medium" placeholder="">
                                            <div class="input-icon right input-medium margin-top-10">

                                                <label>Title</label>
                                                <input type="text" class="form-control" placeholder=""></div>
                                            <div class="input-icon input-medium margin-top-10">
                                                <label>Onderschrift</label>
                                                <textarea class="form-control padding5"></textarea></div>
                                            <div class="input-group input-medium margin-top-10">
                                                <label>Alt tekst</label>
                                                <input type="text" class="form-control" placeholder=""></div>
                                            <div class="input-group input-medium margin-top-10">
                                                <label>Beschrijving</label>
                                                <textarea class="form-control padding5"></textarea>
                                            </div>
                                            <hr>
                                        </div>


                                    </div>

                                    {{ Form::close() }}

                                </div>


                            </div>
                            <div class="clearfix"></div>

                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn green">Opslaan</button>
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