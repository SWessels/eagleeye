
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
                <a href="{{ route('customers.index') }}">Customers</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <a href="{{ route('customers.create') }}"> <span>Add</span></a>
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
    <h3 class="page-title"> Add New Customer
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
            </div>
            <div class="col-md-8 ">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <span class="caption-subject font-blue-sharp bold uppercase">Customer Detail</span>
                    <div class="portlet-body form">
                        <form role="form" id="customer-form" action=" {{action('CustomerController@store')}} " method="post">
                            <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                            <div class="form-body">

                                <div class="form-group">
                                    <label>Username</label>

                                        <input name="username" id="username" type="text" class="form-control" ><div  id="error-div"></div>
                                </div>
                                <div class="form-group">
                                    <label>First Name</label>

                                        <!-- <i class="fa fa-bell-o"></i>-->
                                        <input name="first_name" id="first_name" type="text" class="form-control input-sm" >
                                </div>

                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input name="last_name"  id="last_name" type="text" class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input name="email"  id="email" type="text"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input name="password"  id="password" type="password"  class="form-control input-sm">
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status"  id="status" class="form-control">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="delete">Delete</option>

                                    </select>
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
@section('scripts')
    {!! HTML::script('assets/js/products_js.js') !!}
    {!! HTML::script('assets/js/customer_js.js') !!}
    {!! HTML::script('assets/js/global.js') !!}
    {!! HTML::script('assets/js/customer.js') !!}
@endsection