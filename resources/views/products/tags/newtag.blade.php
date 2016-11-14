
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
            <a href="{{ route('tags.index') }}"> <span>Tags</span></a>
        </li>
    </ul>

</div>
<br>

<div class="page-bar">
    {{--<ul class="page-breadcrumb">--}}
        {{--<li> <a href="../pages/index.html">Home</a>  </li>--}}

        {{--<li> <span>Categories</span> </li>--}}
    {{--</ul>--}}
    <div class="page-toolbar">

    </div>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Add New Tag
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
    <div class="col-md-12 ">
    <div class="portlet-body">
        <div id="context" data-toggle="context" data-target="#context-menu">
            <p> </p>
        </div>
        </div><div class="col-md-8 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light bordered">
        
            <div class="portlet-body form">
                <form role="form" action=" {{action('TagsController@store')}} " method="post">
                <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                    <div class="form-body">
                       
                        <div class="form-group">
                            <label>Name</label>
                            <div class="input-icon">
                              <!--  <i class="fa fa-bell-o font-green"></i>-->
                                <input name="name" id="name" type="text" class="form-control" > </div>
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <div class="input-icon input-icon-sm">
                               <!-- <i class="fa fa-bell-o"></i>-->
                                <input name="slug" id="slug" type="text" class="form-control input-sm" > </div>
                        </div>

                       <div class="form-group">
                        <label>description</label>
                        <textarea name="desc"  id="desc" class="form-control" rows="3" cols="4"></textarea>
                    </div>

                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn blue">Submit</button>
                        {{--<button type="button" class="btn default">Cancel</button>--}}
                    </div>
                </form>
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