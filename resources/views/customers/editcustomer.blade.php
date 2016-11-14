
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
    <h3 class="page-title">Edit Customer
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
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif
            <div class="col-md-12 error_div">

            </div>
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
                        {!! Form::open(array('method' => 'patch', 'id' => 'customer-form',  'action' => array('CustomerController@update', $customerById->id))) !!}
                            <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                            <div class="form-body">

                                <div class="form-group">
                                    <label>Username</label>

                                    <input name="username" id="username" value="{{ $customerById->username }}" type="text" class="form-control input-sm" >
                                </div>
                                <div class="form-group">
                                    <label>Birthday</label>

                                    <input name="birthday" id="birthday" value="" type="text" class="form-control input-sm" >
                                </div>

                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status"  id="status" class="form-control">
                                        <option @if($customerById->password == 'active') selected="selected" @endif value="active">Active</option>
                                        <option @if($customerById->password == 'inactive') selected="selected" @endif value="inactive">Inactive</option>
                                        <option @if($customerById->password == 'deleted') selected="selected" @endif value="deleted">Delete</option>

                                    </select>
                                </div>

                                <span class="caption-subject font-blue-sharp bold uppercase">Customer Billing Address</span>

                                <div class="form-group">

                                </div>
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input name="billing_first_name"  id="billing_first_name" value="@if (isset($customerById['CustomerBilling'])) {{  $customerById['CustomerBilling']->first_name }} @endif " type="text"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input name="billing_last_name"  id="billing_last_name"  value="@if (isset($customerById['CustomerBilling'])) {{ $customerById['CustomerBilling']->last_name }} @endif" type="text"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Address 1</label>
                                    <input name="billing_address1"  id="billing_address1"  value="@if (isset($customerById['CustomerBilling'])){{ $customerById['CustomerBilling']->address_1 }} @endif" type="text"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input name="billing_city"  id="billing_city" type="text"  value="@if (isset($customerById['CustomerBilling'])) {{ $customerById['CustomerBilling']->city }} @endif"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Post Code</label>
                                    <input name="billing_post_code"  id="bill_post_code" value="@if (isset($customerById['CustomerBilling'])) {{ $customerById['CustomerBilling']->postcode }} @endif" type="text"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <select name="billing_country"  id="billing_country" class="form-control">

                                        <option @if (isset($customerById['CustomerBilling'])) @if($customerById['CustomerBilling']->country == 'NL') selected="selected" @endif  @endif value="NL">Netherlands</option>
                                        <option @if (isset($customerById['CustomerBilling'])) @if($customerById['CustomerBilling']->country == 'BE') selected="selected" @endif  @endif value="BE">Belgium</option>



                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input name="billing_phone"  id="billing_phone" type="text" value="@if (isset($customerById['CustomerBilling'])){{ $customerById['CustomerBilling']->phone }} @endif"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input name="billing_email"  id="billing_email" type="text" value="@if (isset($customerById['CustomerBilling'])) {{ $customerById['CustomerBilling']->email }} @endif"  class="form-control input-sm">
                                </div>
                                <span class="caption-subject font-blue-sharp bold uppercase">Customer Shipping Address</span>

                                <div class="form-group">

                                </div>
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input name="shipping_first_name"  id="shipping_first_name" type="text" value="@if (isset($customerById['CustomerShipping'])) {{ $customerById['CustomerShipping']->first_name }}  @endif"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input name="shipping_last_name"  id="shipping_last_name" type="text"  value="@if (isset($customerById['CustomerShipping'])) {{ $customerById['CustomerShipping']->last_name }}  @endif"   class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Address 1</label>
                                    <input name="shipping_address1"  id="shipping_address1" type="text"  value="@if (isset($customerById['CustomerShipping'])) {{ $customerById['CustomerShipping']->address_1 }}  @endif"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input name="shipping_city"  id="shipping_city" type="text"  value="@if (isset($customerById['CustomerShipping'])) {{ $customerById['CustomerShipping']->city }}  @endif"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Post Code</label>
                                    <input name="shipping_post_code"  id="ship_post_code" value="@if (isset($customerById['CustomerShipping'])) {{ $customerById['CustomerShipping']->postcode }}  @endif" type="text"  class="form-control input-sm">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <select name="shipping_country"  id="shipping_country" class="form-control">
                                        <option @if (isset($customerById['CustomerShipping'])) @if($customerById['CustomerShipping']->country == 'NL') selected="selected" @endif  @endif value="NL">Netherlands</option>
                                        <option @if (isset($customerById['CustomerShipping'])) @if($customerById['CustomerBilling']->country == 'BE') selected="selected" @endif  @endif value="BE">Belgium</option>


                                    </select>
                                </div>


                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn blue">Update</button>
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