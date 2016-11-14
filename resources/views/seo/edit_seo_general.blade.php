@extends('layouts.master')

@section('css')

{!! HTML::style('assets/css/reports_plugins.css') !!}
@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>

    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title">SEO Algemeen

</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->


    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />

    <div class="row">
        <div class="col-md-12">
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

            <div class="clearfix"></div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet">

                        <div class="portlet-body">
                            <ul class="nav nav-tabs">
                                <li  class="active">
                                    <a  href="#titles" data-toggle="tab">Titels en omschrijvingen </a>
                                </li>
                                <li>
                                    <a href="#sitemaps" data-toggle="tab">Sitemaps</a>
                                </li>
                                <li>
                                    <a href="#310redirects" data-toggle="tab">301 Redirects</a>
                                </li>

                            </ul>
                            <div class="tab-content tabcustom" style="position: relative" >

                                <div class="tab-pane fade active in custom_tabwidth"   id="titles" style="">
                                    <form id="seo-form" action="{{action('PluginsController@saveSeoGeneralSettings')}}" method="post">
                                        <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                    <?php

                                    if(Session::get('connection') == 'themusthaves'){
                                        $con_title = 'The Musthaves';
                                    }
                                    else if(Session::get('connection') == 'musthavesforreal' ){
                                        $con_title = 'Musthaves For Real';
                                    }


                                        ?>
                                    <input name="con_title" type="hidden" id="con_title" value="{{$con_title}}">
                                        <div class="form-group">
                                            <div class="col-md-12 control-label"><h3><strong>Product Tags </strong></h3></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Titel</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[product-tag][seoTitle]" name="seo[product-tag][seoTitle]" value="{{$data['product-tag']['title']}}"  class=" form-control input-sm">
                                                <span id="title_count" class="count_alert"></span>
                                                <input type="hidden" id="seo[product-tag][type]" name="seo[product-tag][type]" value="product-tag"  class=" form-control input-sm">

                                            </div>
                                            <div class="col-md-4"></div>;
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Omschrijving</div>
                                            <div class="col-md-6">
                                                <textarea  id="seo[product-tag][seoDesc]"  name="seo[product-tag][seoDesc]" rows="3" class=" form-control input-sm">{{$data['product-tag']['description']}}
                                                </textarea><span id="desc_count" class="count_alert"></span>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Index/No Index</label>
                                            <div class="col-md-9">
                                                <div class="radio-list">
                                                     <label class="radio-inline">
                                                <div class="radio">
                                                     <input type="radio" name="seo[product-tag][isIndex]" value="index" <?php if($data['product-tag']['is_index'] == '1' || $data['product-tag']['is_index'] == ''){ echo "checked";} ?>></div> Index</label>
                                                <label class="radio-inline">
                                                <div class="radio">
                                                     <input type="radio" name="seo[product-tag][isIndex]"  value="non-index" <?php if($data['product-tag']['is_index'] == '0'){ echo "checked";} ?>></div> No Index</label>
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
                                                    <input type="radio" name="seo[product-tag][isfollow]"  value="follow" <?php if($data['product-tag']['is_follow'] == '1' || $data['product-tag']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                <label class="radio-inline">
                                                <div class="radio">
                                                    <input type="radio" name="seo[product-tag][isfollow]"  value="no-follow" <?php if($data['product-tag']['is_follow'] == '0'){ echo "checked";} ?> ></div> No Follow</label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">URL voorbeeld</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[product-tag][url]" name="seo[product-tag][url]" value="{{$data['product-tag']['template_url']}}"  class="form-control input-sm">
                                            </div>
                                        <div class="col-md-4"></div>
                                        <div class="clearfix"></div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-12 control-label"><h3><strong>Bericht Tags</strong></h3></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Titel</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[post-tag][seoTitle]" name="seo[post-tag][seoTitle]" value="{{$data['post-tag']['title']}}"  class=" form-control input-sm">
                                                <span id="title_count" class="count_alert"></span>
                                                <input type="hidden" id="seo[post-tag][type]" name="seo[post-tag][type]" value="post-tag"  class=" form-control input-sm">

                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Omschrijving</div>
                                            <div class="col-md-6">
                                                <textarea  id="seo[post-tag][seoDesc]"  name="seo[post-tag][seoDesc]" rows="3" class=" form-control input-sm">{{$data['product-tag']['description']}}</textarea><span id="desc_count" class="count_alert"></span>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Index/No Index</label>
                                            <div class="col-md-9">
                                                <div class="radio-list">
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[post-tag][isIndex]" value="index"  <?php if($data['post-tag']['is_index'] == '1' || $data['post-tag']['is_index'] == ''){ echo "checked";} ?>></div> Index</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[post-tag][isIndex]"  value="non-index" <?php if($data['post-tag']['is_index'] == '0'){ echo "checked";} ?>></div> No Index</label>
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
                                                            <input type="radio" name="seo[post-tag][isfollow]"  value="follow" <?php if($data['post-tag']['is_follow'] == '1' || $data['post-tag']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[post-tag][isfollow]"  value="no-follow"  <?php if($data['post-tag']['is_follow'] == '0'){ echo "checked";} ?> ></div> No Follow</label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">URL voorbeeld</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[post-tag][url]" name="seo[post-tag][url]" value="{{$data['post-tag']['template_url']}}"  class="form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-12 control-label"><h3><strong>Details afbeelding</strong></h3></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Titel</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[img][seoTitle]" name="seo[img][seoTitle]" value="{{$data['media']['title']}}"  class=" form-control input-sm">
                                                <span id="title_count" class="count_alert"></span>
                                                <input type="hidden" id="seo[img][type]" name="seo[img][type]" value="media"  class=" form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Omschrijving</div>
                                            <div class="col-md-6">
                                                <textarea  id="seo[img][seoDesc]"  name="seo[img][seoDesc]" rows="3" class=" form-control input-sm">{{$data['media']['description']}}
                                                </textarea><span id="desc_count" class="count_alert"></span>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Index/No Index</label>
                                            <div class="col-md-9">
                                                <div class="radio-list">
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[img][isIndex]" value="index"  <?php if($data['media']['is_index'] == '1' || $data['media']['is_index'] == ''){ echo "checked";} ?>></div> Index</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[img][isIndex]"  value="non-index" <?php if($data['media']['is_index'] == '0'){ echo "checked";} ?>></div> No Index</label>
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
                                                            <input type="radio" name="seo[img][isfollow]"  value="follow" <?php if($data['media']['is_follow'] == '1' || $data['media']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[img][isfollow]"  value="no-follow"  <?php if($data['media']['is_follow'] == '0'){ echo "checked";} ?> ></div> No Follow</label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">URL voorbeeld</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[img][url]" name="seo[img][url]" value="{{$data['media']['template_url']}}"  class="form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-12 control-label"><h3><strong>Attributen</strong></h3></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Titel</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[attr][seoTitle]" name="seo[attr][seoTitle]" value="{{$data['attribute']['title']}}"  class=" form-control input-sm">
                                                <span id="title_count" class="count_alert"></span>
                                                <input type="hidden" id="seo[attr][type]" name="seo[attr][type]" value="attribute"  class=" form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Omschrijving</div>
                                            <div class="col-md-6">
                                                <textarea  id="seo[attr][seoDesc]"  name="seo[attr][seoDesc]" rows="3" class=" form-control input-sm">{{$data['search_result']['description']}}
                                                </textarea><span id="desc_count" class="count_alert"></span>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Index/No Index</label>
                                            <div class="col-md-9">
                                                <div class="radio-list">
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[attr][isIndex]" value="index" <?php if($data['attribute']['is_index'] == '1' || $data['attribute']['is_index'] == ''){ echo "checked";} ?>></div> Index</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[attr][isIndex]"  value="non-index" <?php if($data['attribute']['is_index'] == '0'){ echo "checked";} ?> ></div> No Index</label>
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
                                                            <input type="radio" name="seo[attr][isfollow]"  value="follow"  <?php if($data['attribute']['is_follow'] == '1' || $data['attribute']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[attr][isfollow]"  value="no-follow"  <?php if($data['attribute']['is_follow'] == '0'){ echo "checked";} ?>  ></div> No Follow</label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">URL voorbeeld</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[attr][url]" name="seo[attr][url]" value="{{$data['attribute']['template_url']}}"  class="form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-12 control-label"><h3><strong>Zoekresultaten</strong></h3></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Titel</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[search][seoTitle]" name="seo[search][seoTitle]" value="{{$data['search_result']['title']}}"  class=" form-control input-sm">
                                                <span id="title_count" class="count_alert"></span>
                                                <input type="hidden" id="seo[search][type]" name="seo[search][type]" value="search-result"  class=" form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Omschrijving</div>
                                            <div class="col-md-6">
                                                <textarea  id="seo[search][seoDesc]"  name="seo[search][seoDesc]" rows="3" class=" form-control input-sm">{{$data['search_result']['description']}}</textarea><span id="desc_count" class="count_alert"></span>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Index/No Index</label>
                                            <div class="col-md-9">
                                                <div class="radio-list">
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[search][isIndex]" value="index"  <?php if($data['search_result']['is_index'] == '1' || $data['search_result']['is_index'] == ''){ echo "checked";} ?>></div> Index</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[search][isIndex]"  value="non-index" <?php if($data['search_result']['is_index'] == '0'){ echo "checked";} ?>  ></div> No Index</label>
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
                                                            <input type="radio" name="seo[search][isfollow]"  value="follow" <?php if($data['search_result']['is_follow'] == '1' || $data['search_result']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[search][isfollow]"  value="no-follow"  <?php if($data['search_result']['is_follow'] == '0'){ echo "checked";} ?>  ></div> No Follow</label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">URL voorbeeld</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[search][url]" name="seo[search][url]" value="{{$data['search_result']['template_url']}}"  class="form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-12 control-label"><h3><strong>404</strong></h3></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Titel</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[404][seoTitle]" name="seo[404][seoTitle]" value="{{$data['_404']['title']}}"  class=" form-control input-sm">
                                                <span id="title_count" class="count_alert"></span>
                                                <input type="hidden" id="seo[404][type]" name="seo[404][type]" value="404"  class=" form-control input-sm">

                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">Omschrijving</div>
                                            <div class="col-md-6">
                                                <textarea  id="seo[404][seoDesc]"  name="seo[404][seoDesc]" rows="3" class=" form-control input-sm">{{$data['_404']['description']}}
                                                </textarea><span id="desc_count" class="count_alert"></span>
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Index/No Index</label>
                                            <div class="col-md-9">
                                                <div class="radio-list">
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[404][isIndex]" value="index" <?php if($data['_404']['is_index'] == '1' || $data['_404']['is_index'] == ''){ echo "checked";} ?>></div> Index</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[404][isIndex]"  value="non-index"  <?php if($data['_404']['is_index'] == '0'){ echo "checked";} ?> ></div> No Index</label>
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
                                                            <input type="radio" name="seo[404][isfollow]"  value="follow" <?php if($data['_404']['is_follow'] == '1' || $data['_404']['is_follow'] == ''){ echo "checked";} ?>></div> Follow</label>
                                                    <label class="radio-inline">
                                                        <div class="radio">
                                                            <input type="radio" name="seo[404][isfollow]"  value="no-follow"  <?php if($data['_404']['is_follow'] == '0'){ echo "checked";} ?>  ></div> No Follow</label>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 control-label">URL voorbeeld</div>
                                            <div class="col-md-6">
                                                <input type="text" id="seo[404][url]" name="seo[404][url]" value="{{$data['_404']['template_url']}}"  class="form-control input-sm">
                                            </div>
                                            <div class="col-md-4"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-actions">
                                                <button type="submit" class="btn blue">Indienen</button>

                                            </div></div>
                                        </form>
                                </div>
                                <div class="tab-pane fade  custom_tabwidth"   id="sitemaps" style="">

                                    <div  class="col-md-3">
                                    </div>
                                    <div   class="col-md-9">
                                    </div>
                                </div>
                                <div class="tab-pane fade  custom_tabwidth"   id="310redirects" style="">

                                    <div  class="col-md-3">
                                    </div>
                                    <div   class="col-md-9">
                                    </div>
                                </div>
                              </div>
                            <div class="clearfix margin-bottom-20"> </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>



@endsection

@section('scripts')
    {!! HTML::script('assets/js/reports_plugins.js') !!}
    {!! HTML::script('assets/js/reports.js') !!}

    <script>
        $('.date-picker').datepicker();


    </script>



@endsection