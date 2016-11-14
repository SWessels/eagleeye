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
            <span>Media</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}

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
                    <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-Products"></i>Media</div>
                    <div class="actions">
                        {{--<a href="{{  url('/media/generate_thumbnails') }}" class="btn orders btn-info">
                            <i class="fa fa-plus"></i>
                            <span class="hidden-xs">Genereer thumbnails</span>
                        </a>

                        <a href="{{ route('posts.create') }}" class="btn orders btn-info">
                            <i class="fa fa-plus"></i>
                            <span class="hidden-xs"> Nieuw bestand</span>
                        </a>--}}
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6 npl">


                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-12 npl">

                                    <form  {{action('MediaController@index')}} class="form-horizental">
                                        <div class="col-sm-3 pull-right npr">

                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                                    <span class="input-group-btn">
                                                        <button class="btn red" type="submit">Zoeken</button>
                                                    </span>
                                                </div>
                                                <!-- /input-group -->
                                            </div>

                                        </div>
                                    </form>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <form role="form" name="media_delform" id="media_delform" action=" " method="post">
                                <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                <div class="row">


                                    <div class="clearfix"></div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                           {{-- @if(count($data['posts']) > 0)

                                                {!! $data['posts']->appends(Input::except('page'))->links() !!}

                                            @endif--}}
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>

                                </div>
                                <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                                    <thead>


                                    </thead>
                                    <tbody>

                                    <tr>
                                       <div class="tiles">

                                        @if(count($all_media) > 0)
                                        @foreach($all_media as $media)
                                                   <div class="tile image " style="width:120px !important; height:184px !important" data-toggle="modal" href="#full">
                                            <div class="tile-body show-images-btn" style="width:120px !important; height:184px !important" data-id="{{$media['id']}}">
                                              <a class="show-images-btn"  data-id="{{$media['id']}}" >
                                              <img class="img_centered" src= '{{galleryThumb($media['path'])}}' alt=""  width = "100%"></a>
                                            </div>
                                        </div>
                                    @endforeach
                    </div>
                                    @else

                                            <td align="center" colspan="8"><h4>No Media Found</h4></td>

                                        @endif
                                    </tr>
                                    </tbody>
                                </table>
                                @if(count($all_media) > 0)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="pull-right">
                                              {{--  {!! $all_media->appends(Input::except('page'))->links() !!}--}}
                                                {!! $all_media->links() !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>

                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
    </div>

    <div class="modal fade" id="full" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-full" style="height: 620px">
            <div class="modal-content" style="height: 620px">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Details bijlage</h4>
                </div>
                <div class="modal-body">


                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_5_2" style="height: 620px">

                                <div class="row col-md-8 library-section"style="height: 482px" >
                                  <div class="clearfix"></div>
                                    <div class="top20"></div>
                                    <div class="tiles show_media_img" style="height: 100%">
                                        {{-- <input type="checkbox" id="myCheckbox1" />
                                         <label for="myCheckbox1"><img src="assets/img/photo3.jpg" /></label>--}}
                                   </div>


                                </div>
                                <div class="col-md-4 product-description library-section" style="height:auto">
                                    <div class="detail-section" >

                                    </div>
                                    <div class="row col-md-12 col-sm-12 top15">

                                        {{-- {!! Form::open(array('method' => 'patch' ,'action' => array('ProductsController@create'))) !!}--}}
                                        <div class="form-body ">


                                            <div class="form-group col-md-12">
                                                <label>URL</label>
                                                <input name="checkimg[path]" id="path" readonly type="text" class="form-control" placeholder="">

                                            </div>
                                            <div class="form-group col-md-12">
                                                <label>Ingevoegd door</label>
                                                <input name="uploaded_by" id="uploaded_by" readonly type="text" class="form-control " placeholder="">
                                            </div>
                                            <div  class="form-group col-md-12">
                                                <label>Titel</label>
                                               <input name="title" id="title" type="text" class="form-control" placeholder="">
                                            </div>
                                            <div  class="form-group col-md-12">
                                                    <label>Alt Tekst</label>
                                                    <span  value=""  id="alt_text_field"><input name="alt_text" id="alt_text" type="text" class="form-control" placeholder=""></span>
                                            </div>
                                            <div  class="form-group col-md-12 pull-right">
                                            <button id="save_title" class="btn btn-sm blue-soft btn-outline save_title">Wijzigingen opslaan</button> <span style="color: green" id="changes_save"></span></div>




                                        </div>

                                        {{-- {{ Form::close() }}--}}

                                    </div>

                                 </div>
                                <div class="clearfix"></div>

                            </div>
                        </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


{{-- end of page content --}}
@endsection

@section('scripts')
    {!! HTML::script('assets/js/media.js') !!}
@endsection
