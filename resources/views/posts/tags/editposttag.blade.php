
@extends('layouts.master')
@section('css')
    {!! HTML::style('assets/css/post_plugins.css') !!}
@endsection
@section('content')


    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <a href="/posts">Blogberichten</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <a href="{{ route('posttags.index') }}"> <span>Bericht tags</span></a>
            </li>
        </ul>

    </div>
    <br>
    <div class="page-bar">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif

    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h3 class="page-title">Bericht tag wijzigen
    </h3>
    {{--@if (count($errors) > 0)--}}
    {{--<div class="alert alert-danger">--}}
    {{--<ul>--}}
    {{--@foreach ($errors->all() as $error)--}}
    {{--<li>{{ $error }}</li>--}}
    {{--@endforeach--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--@endif--}}
    <div class="row">
        <div class="col-md-8 ">
            <!-- BEGIN SAMPLE FORM PORTLET-->
            <div class="portlet light bordered">

                <div class="portlet-body form">
                    {!! Form::open(array('method' => 'patch' ,'action' => array('PostTagsController@update',$tagbyid->id))) !!}

                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                    <div class="form-body">

                        <div class="form-group">
                            <label>Naam</label>

                            <input name="name" id="name" type="text" class="form-control"  value="{!! $tagbyid->name !!}" >
                        </div>
                        <div class="form-group">
                            <label>Slug</label>

                            <input name="slug" id="slug" type="text" class="form-control input-sm" value="{!! $tagbyid->slug !!}" >
                        </div>

                        <div class="form-group">
                            <label>Beschrijving</label>
                            <textarea name="desc"  id="desc" class="form-control" rows="3" cols="6">{!! $tagbyid->description !!}</textarea>
                        </div>



                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn blue">Bijwerken</button>
                        {{--<button type="button" class="btn default">Annuleren</button>--}}
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