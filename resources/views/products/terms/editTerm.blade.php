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
                <a href="{{ route('attributes.index') }}"> <span>Attributes</span></a>
            </li>
        </ul>

    </div>
    <br>
    <div class="page-bar">
        {{--<ul class="page-breadcrumb">--}}
        {{--<li> <a href="../pages/index.html">Home</a>  </li>--}}

        {{--<a href="{{ route('categories.index') }}"> <span>Categories</span></a>--}}
        {{--</ul>--}}
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif

    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title"> Edit Term
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
                    {!! Form::open(array('method' => 'patch' ,'action' => array('TermsController@update',$termbyid->id))) !!}
                    <input type="hidden" name="att_id" id="att_id" value="<?php echo $termbyid->attributes_id ?>" >
                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                    <div class="form-body">

                        <div class="form-group">
                            <label>Name</label>

                                <input name="name" id="name" type="text" class="form-control"  value="{!! $termbyid->name !!}" >
                        </div>
                        <div class="form-group">
                            <label>Slug</label>

                                <input name="slug" id="slug" type="text" class="form-control input-sm" value="{!! $termbyid->slug !!}" >
                        </div>
                        <div class="form-group">
                            <label>description</label>
                            <textarea name="desc"  id="desc" class="form-control" rows="3" cols="6">{!! $termbyid->description !!}</textarea>
                        </div>



                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn blue">Update</button>
                        {{--<button type="button" class="btn default">Cancel</button>--}}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- END SAMPLE FORM PORTLET-->
            <!-- BEGIN SAMPLE FORM PORTLET-->

            <!-- END SAMPLE FORM PORTLET-->
            <!-- BEGIN SAMPLE FORM PORTLET-->

            <!-- END SAMPLE FORM PORTLET-->
        </div>

    </div>



@endsection