@extends('layouts.master')

@section('css')


{!! HTML::style('assets/css/menu_plugins.css') !!}


@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
        <li><a href={{ route('menu.index') }}>Menu</a></li>
    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title">Menu toevoegen

</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->

{{--<form id="post-form" action="{{action('PostsController@update' , $postByid->id )}}"  method="patch">--}}

<form id="menu-form" action="{{action('MenuController@store')}}"  method="post">
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

        <div class="col-md-6">
           <div class="form-group">
            <label>Menu naam</label>
            <input type="text" class="form-control" name="name" id="name" value="">
            <input type="hidden" name="menu_id" value="" id="menu_id">
            </div>  <button  id="add"  class="btn  pull-left btn-sm publish-post">Toevoegen</button>

        </div>
        <div class="col-md-6">


        </div>


</div>
</form>



@endsection

@section('scripts')
    {!! HTML::script('assets/js/menu_plugins.js') !!}
    {!! HTML::script('assets/plugins/ckeditor/ckeditor.js') !!}
    {!! HTML::script('assets/js/menu.js') !!}

@endsection