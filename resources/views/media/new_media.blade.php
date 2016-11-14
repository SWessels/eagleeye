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
            <span>Nieuw bestand</span>
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
                        <i class="fa fa-Products"></i>Bestand toevoegen</div>
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

                                    <div class="clearfix"></div>
                                </div>
                            </div>
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
                                        <form action="#" id="fileupload" enctype="multipart/form-data" >
                                            <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                            <input type="hidden" name="media_type" id="media_type" value="" >
                                            <input type="hidden" name="parent_id" value="" id="parent_id" >
                                            <input type="hidden" name="image_type" value="" id="image_type" >
                                            <div align="center" style="border: 1px solid beige ; height: 200px ; padding-top: 40px;" class="img_append">
                                                <div class="row fileupload-buttonbar">
                                                    <div class="col-lg-7">
                                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                                        {{--  <span class="btn green ">--}}
                                                        <span class="btn green fileinput-button ">
                                            <i class="fa fa-plus"></i>
                                            <span> Bestanden toevoegen... </span>
                                            <input class="up" type="file" name="files[]" id="files" multiple> </span>
                                                        {{--<button type="submit" class="btn blue start upload_files" >
                                                            <i class="fa fa-upload "></i>
                                                            <span> Invoegen </span>
                                                        </button>--}}

                                                    </div>

                                                </div>
                                            </div>


                                        </form>
                                    </tr>
                                    </tbody>
                                </table>


                        </div>

                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
    </div>




{{-- end of page content --}}
@endsection

@section('scripts')
    {!! HTML::script('assets/js/media.js') !!}
@endsection
