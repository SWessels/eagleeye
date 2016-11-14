@extends('layouts.master')

@section('css')
    {!! HTML::style('assets/css/actions_plugins.css') !!}
@endsection

@section('content')

    <!-- BEGIN tab BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-angle-double-right"></i>
            </li>
            <li>
                <span>Create Actions</span>
            </li>
        </ul>

    </div>
    <br>

    {{-- pae content --}}

    <div class="row" id="actions-page">
        <div class="col-md-12">
            @if(Session::has('flash_message'))
                <div class="alert alert-success">
                    {{ Session::get('flash_message') }}
                </div>
        @endif
                @if(Session::has('error_message'))
                    <div class="alert alert-danger">
                        {{ Session::get('error_message') }}
                    </div>
            @endif

        <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-Products"></i>Create Action
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <div class="row">
                            <div class="col-sm-7 col-sm-offset-2">
                                <form role="form" action="{{action('PricingController@store')}}" method="post" id="action_form" enctype="multipart/form-data
">
                                    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                    <input type="hidden" name="p_type" id="p_type" value="p">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Title</label>
                                            <div class="col-md-8">
                                                <input class="form-control input-inline input-sm " name="action_title" id="action_title"  value=""  >
                                                <span class="help-block action_title_error"> Action Title is required. </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Eligible Customers</label>
                                            <div class="col-md-8">
                                                <div class="mt-radio-inline">
                                                    <label class="mt-radio">
                                                        <input type="radio" name="eligible_customers_type"   class="eligible_customers_type" value="all" checked="checked"> All
                                                        <span></span>
                                                    </label>
                                                    <label class="mt-radio">
                                                        <input type="radio" name="eligible_customers_type"  class="eligible_customers_type"  value="specific" > Specific
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group min_past_orders_div">
                                            <label class="col-md-4 control-label">Customer Min Past Orders</label>
                                            <div class="col-md-8">
                                                <input class="form-control input-inline input-sm " name="min_past_orders" id="min_past_orders" value=""  >
                                                <span class="help-block min_past_orders_error" > Number of minimum past orders is required </span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Active From</label>
                                            <div class="col-md-8">
                                                <div class="row" style="padding-left:15px;">
                                                    <div class="col-sm-3 sm-select">
                                                        <select name="p_from_day" id="p_from_day" class="">

                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3 sm-select month-name">
                                                        <select name="p_from_month" id="p_from_month" class="" onchange="getMonthDays($('#p_from_year').val(), this.value, 'p_from_day')">

                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3 sm-year">
                                                        <select name="p_from_year" id="p_from_year" class="" onchange="getYearMonths(this.value, 'p_from_month')">
                                                            <?php
                                                            for($y = 2016; $y <= 2060; $y++)
                                                                {
                                                                    echo '<option value="'.$y.'">'.$y.'</option>';
                                                                }

                                                            ?>
                                                        </select>
                                                    </div>



                                                    <span class="datetime-sep"> </span>
                                                    <div class="col-sm-3 sm-select">
                                                        <select name="p_from_hour" id="p_from_hour" class="">
                                                            <?php
                                                            for($ph = 0; $ph < 24; $ph++)
                                                            {
                                                                echo '<option value="'.sprintf("%'.02d", $ph).'">'.sprintf("%'.02d", $ph).'</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                    <span class="time-sep">:</span>
                                                    <div class="col-sm-3 sm-select">

                                                        <select name="p_from_min" id="p_from_min" class="">
                                                            <?php
                                                            for($pm = 0; $pm < 60; $pm++)
                                                            {
                                                                echo '<option value="'.sprintf("%'.02d", $pm).'">'.sprintf("%'.02d", $pm).'</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <span class="help-block">  </span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Active To</label>
                                            <div class="col-md-8">
                                                <div class="row" style="padding-left:15px;">
                                                    <div class="col-sm-3 sm-select">
                                                        <select name="p_to_day" id="p_to_day" class="">

                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3 sm-select month-name">
                                                        <select name="p_to_month" id="p_to_month" class="" onchange="getMonthDays($('#p_to_year').val(), this.value, 'p_to_day')">

                                                        </select>
                                                    </div>

                                                    <div class="col-sm-3 sm-year">
                                                        <select name="p_to_year" id="p_to_year" class="" onchange="getYearMonths(this.value, 'p_to_month')">
                                                            <?php
                                                            for($y = 2016; $y <= 2060; $y++)
                                                            {
                                                                echo '<option value="'.$y.'">'.$y.'</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>



                                                    <span class="datetime-sep"> </span>
                                                    <div class="col-sm-3 sm-select">
                                                        <select name="p_to_hour" id="p_to_hour" class="">
                                                            <?php
                                                            for($ph = 0; $ph < 24; $ph++)
                                                            {
                                                                echo '<option value="'.sprintf("%'.02d", $ph).'">'.sprintf("%'.02d", $ph).'</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                    <span class="time-sep">:</span>
                                                    <div class="col-sm-3 sm-select">

                                                        <select name="p_to_min" id="p_to_min" class="">
                                                            <?php
                                                            for($pm = 0; $pm < 60; $pm++)
                                                            {
                                                                echo '<option value="'.sprintf("%'.02d", $pm).'">'.sprintf("%'.02d", $pm).'</option>';
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <span class="help-block datetime_error"> From date must be less than To date   </span>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Method</label>
                                            <div class="col-md-8">
                                                <select class="form-control input-inline input-sm " name="pricing_method" id="pricing_method">
                                                    <option value="1">Percentage</option>
                                                    <option value="2">Fixed Amount</option>
                                                    <option value="3">Free Shipping</option>
                                                    <option value="4">Free Product</option>
                                                </select>
                                                <span class="help-block">  </span>
                                            </div>
                                        </div>

                                        <div class="percentage_div method_div active" id="percentage_div">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Discount</label>
                                                <div class="col-md-8">
                                                    <input type="text" name="p_discount" id="p_discount" placeholder="% value" value="" class="form-control input-inline input-sm " >
                                                    <span class="help-block p_discount_error"  > Discount value is required </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Applies On</label>
                                                <div class="col-md-8">
                                                    <div class="mt-radio-inline">
                                                        <label class="mt-radio">
                                                            <input type="radio" name="p_applies_on"  class="p_applies_on" value="all" checked="checked"> All
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="p_applies_on" class="p_applies_on"  value="category" > Category
                                                            <span class=""></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="p_applies_on"   class="p_applies_on" value="products"> Products

                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p_percentage_all_div p_applies_on_div active">

                                            </div>

                                            <div class="p_percentage_category_div p_applies_on_div">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Category</label>
                                                    <div class="col-md-8">
                                                        <select id="p_applies_category" name="p_applies_category[]"  data-tags="true" data-placeholder="Select Category" data-allow-clear="true" class="form-control input-inline input-sm  select2" >
                                                            <option></option>
                                                            @foreach($categories as $category)
                                                                <option value="{{$category['category_id']}}">{{ $category['category_name'] }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block p_applies_category_error"> Category is required </span>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="p_percentage_products_div p_applies_on_div ">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Products</label>
                                                    <div class="col-md-8">
                                                        <select id="p_applies_on_products" name="p_applies_on_products[]" data-tags="true" data-placeholder="Select Products" data-allow-clear="true" multiple  class="form-control input-inline input-sm  select2" >
                                                            @foreach($products as $product)
                                                                <option value="{{$product->id}}">{{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block p_applies_on_products_error "> Product(s) are required. </span>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Exclude</label>
                                                <div class="col-md-8">
                                                    <select data-tags="true" data-placeholder="Select Products" data-allow-clear="true" class="form-control input-inline input-sm  select2" name="p_all_exclude[]" id="p_all_exclude" multiple >

                                                        @foreach($products as $product)
                                                            <option value="{{$product->id}}">{{ $product->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Order Value</label>
                                                <div class="col-md-8">
                                                    <select name="p_order_value[]" id="p_order_value" class="form-control input-inline input-sm  select2" multiple data-tags="true" data-placeholder="Select order values">
                                                        {!! $i = 0 !!}
                                                        @foreach($order_values as $order_value)
                                                            @if($order_value->max_order_value == '' || $order_value->max_order_value == 0)
                                                                {!! $max_order = '&infin;' !!}
                                                            @else
                                                                {!! $max_order =  money($order_value->max_order_value) !!}
                                                            @endif

                                                            <option  value="{{ $order_value->id }}">{{ money($order_value->min_order_value) }} - {{ $max_order }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="button" id="add_order_value_btn" class="btn btn-xs btn-primary pull-right"><i class="fa fa-plus"></i> </button>
                                                    <span class="help-block p_order_value_error"> Order Value is required </span>
                                                </div>
                                            </div>
                                            <div class="form-group" id="add_order_value_div">
                                                <label class="col-md-4 control-label"></label>
                                                <div class="col-md-8" style="padding-left:0">
                                                    <div class="col-sm-5">
                                                        Min Order Value: <input type="text" class="form-control input-sm " id="min_order_new" name="min_order_new">
                                                    </div>
                                                    <div class="col-sm-5">
                                                        Max Order Value: <input type="text" class="form-control input-sm" id="max_order_new" name="max_order_new">
                                                    </div>
                                                    <div class="col-sm-2 " style="padding:0">
                                                        <button type="button" id="save_order_value" style="margin-top: 18px;" class="btn btn-sm btn-primary">Save</button>
                                                    </div>
                                                    <span class="err_msg" style="padding-left:15px;"></span>
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Min Number of Products</label>
                                                <div class="col-md-8">
                                                    <input class="form-control input-inline input-sm " name="p_number_of_products" name="p_number_of_products"  value=""  >
                                                    <span class="help-block">  </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Visible In</label>
                                                <div class="col-md-8">
                                                    <div class="mt-radio-inline">
                                                        <label class="mt-radio">
                                                            <input type="radio" name="p_visible_in" id="p_visible_in_catalog" value="catalog" checked="checked"> Catalog
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="p_visible_in" id="p_visible_in_cart" value="cart" > Cart
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Coupon Based</label>
                                                <div class="col-md-8">
                                                    <div class="mt-radio-inline">
                                                        <label class="mt-radio">
                                                            <input type="radio" name="p_coupon_based"   value="no"  checked="checked"> No
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="p_coupon_based"   value="yes"> Yes
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" id="p_coupon_div">
                                                <label class="col-md-4 control-label">Coupon</label>
                                                <div class="col-md-8">
                                                    <select id="p_coupon_id" name="p_coupon_id" data-tags="true" data-placeholder="Select Coupon" data-allow-clear="true" class="form-control input-inline input-sm  select2" >
                                                        <option></option>
                                                        @foreach($coupons as $coupon)
                                                            <option value="{{$coupon->id}}">{{ $coupon->code }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block p_coupon_id_error"> Coupon is required  </span>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="fixed_div method_div" id="fixed_div">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Discount</label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_discount" id="f_discount" placeholder="discount value" value="" class="form-control input-inline input-sm " >
                                                        <span class="help-block f_discount_error"> Discount value is required.  </span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Applies On</label>
                                                    <div class="col-md-8">
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="f_applies_on"  " class="f_applies_on" value="all" checked="checked"> All
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="f_applies_on"   class="f_applies_on"  value="category" > Category
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="f_applies_on"  class="f_applies_on" value="products"> Products
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="f_percentage_all_div f_applies_on_div active">

                                                </div>

                                                <div class="f_percentage_category_div f_applies_on_div">
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Category</label>
                                                        <div class="col-md-8">
                                                            <select  id="f_applies_on_category" name="f_applies_on_category[]" data-tags="true" data-placeholder="Select Category" data-allow-clear="true" class="form-control input-inline input-sm  select2" >
                                                                <option></option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{$category['category_id']}}">{{ $category['category_name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block f_applies_on_category_error"> Category is required </span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="f_percentage_products_div f_applies_on_div ">
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Products</label>
                                                        <div class="col-md-8">
                                                            <select id="f_applies_on_products" name="f_applies_on_products[]" data-tags="true" data-placeholder="Select Products" data-allow-clear="true" multiple  class="form-control input-inline input-sm  select2" >
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}">{{ $product->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block f_applies_on_products_error"> Product(s) are required </span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Exclude</label>
                                                    <div class="col-md-8">
                                                        <select id="f_all_exclude" name="f_all_exclude[]"  data-tags="true" data-placeholder="Select Products" data-allow-clear="true" multiple  class="form-control input-inline input-sm  select2" >
                                                            @foreach($products as $product)
                                                                <option value="{{$product->id}}">{{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block">  </span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Order Value</label>
                                                    <div class="col-md-8">
                                                        <select name="f_order_value[]" id="f_order_value" class="form-control input-inline input-sm  select2" multiple data-tags="true" data-placeholder="Select order values" >
                                                            {!! $i = 0 !!}
                                                            @foreach($order_values as $order_value)
                                                                @if($order_value->max_order_value == '' || $order_value->max_order_value == 0)
                                                                    {!! $max_order = '&infin;' !!}
                                                                @else
                                                                    {!! $max_order =  money($order_value->max_order_value) !!}
                                                                @endif

                                                                <option value="{{ $order_value->id }}">{{ money($order_value->min_order_value) }} - {{ $max_order }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" id="f_add_order_value_btn" class="btn btn-xs btn-primary pull-right"><i class="fa fa-plus"></i> </button>
                                                        <span class="help-block f_order_value_error"> Order Value is required </span>
                                                    </div>
                                                </div>
                                            <div class="form-group" id="f_add_order_value_div">
                                                <label class="col-md-4 control-label"></label>
                                                <div class="col-md-8" style="padding-left:0">
                                                    <div class="col-sm-5">
                                                        Min Order Value: <input type="text" class="form-control input-sm " id="f_min_order_new" name="f_min_order_new">
                                                    </div>
                                                    <div class="col-sm-5">
                                                        Max Order Value: <input type="text" class="form-control input-sm" id="f_max_order_new" name="f_max_order_new">
                                                    </div>
                                                    <div class="col-sm-2 " style="padding:0">
                                                        <button type="button" id="f_save_order_value" style="margin-top: 18px;" class="btn btn-sm btn-primary">Save</button>
                                                    </div>
                                                    <span class="f_err_msg" style="padding-left:15px;"></span>
                                                </div>
                                            </div>


                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Min Number of Products</label>
                                                    <div class="col-md-8">
                                                        <input class="form-control input-inline input-sm " name="f_number_of_products" value=""  >
                                                        <span class="help-block">  </span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Visible In</label>
                                                    <div class="col-md-8">
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="f_visible_in" id="f_visible_in_catalog" value="catalog" checked="checked"> Catalog
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="f_visible_in" id="f_visible_in_cart" value="cart" > Cart
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Coupon Based</label>
                                                    <div class="col-md-8">
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="f_coupon_based"   value="no"  checked="checked"> No
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="f_coupon_based"   value="yes"> Yes
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" id="f_coupon_div">
                                                    <label class="col-md-4 control-label">Coupon</label>
                                                    <div class="col-md-8">
                                                        <select name="f_coupon_id" id="f_coupon_id" data-tags="true" data-placeholder="Select Coupon" data-allow-clear="true" class="form-control input-inline input-sm  select2" >
                                                            <option></option>
                                                            @foreach($coupons as $coupon)
                                                                <option value="{{$coupon->id}}">{{ $coupon->code }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block f_coupon_id_error"> Coupon is required </span>
                                                    </div>
                                                </div>
                                        </div>

                                        <div class="free_shipping_div method_div" id="free_shipping_div">

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Min Order value</label>
                                                <div class="col-md-8">
                                                    <input class="form-control input-inline input-sm " name="fs_min_order_value" id="fs_min_order_value" value=""  >
                                                    <span class="help-block fs_min_order_value_error"> Minimum order value is required </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Visible with success message</label>
                                                <div class="col-md-8">
                                                    <div class="mt-radio-inline">
                                                        <label class="mt-radio">
                                                            <input type="radio" name="fs_visible_on_success" id="fs_visible_on_success_no" class="fs_visible_on_success" value="no" checked="checked"> No
                                                            <span></span>
                                                        </label>
                                                        <label class="mt-radio">
                                                            <input type="radio" name="fs_visible_on_success" id="fs_visible_on_success_yes" class="fs_visible_on_success" value="yes" > Yes
                                                            <span></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="free_product_div method_div" id="free_product_div">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Min Number of Products</label>
                                                <div class="col-md-8">
                                                    <input class="form-control input-inline input-sm " name="fp_number_of_products" id="fp_number_of_products"  value=""  >
                                                    <span class="help-block fp_number_of_products_error">  Minimum number of products is required. </span>
                                                </div>
                                            </div>

                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Applies On</label>
                                                    <div class="col-md-8">
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="fp_applies_on"  class="fp_applies_on"  value="category" checked="checked" > Category
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="fp_applies_on"   class="fp_applies_on" value="products"> Products
                                                                <span ></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="fp_percentage_category_div fp_applies_on_div active">
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Category</label>
                                                        <div class="col-md-8">
                                                            <select  name="fp_applies_on_category[]" id="fp_applies_on_category" data-tags="true" data-placeholder="Select Category" data-allow-clear="true" class="form-control input-inline input-sm  select2" >
                                                                <option></option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{$category['category_id']}}">{{ $category['category_name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block fp_applies_on_category_error"> Category is required  </span>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="fp_percentage_products_div fp_applies_on_div ">
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label">Products</label>
                                                        <div class="col-md-8">
                                                            <select  id="fp_applies_on_products"  name="fp_applies_on_products[]" data-tags="true" data-placeholder="Select Products" data-allow-clear="true" multiple  class="form-control input-inline input-sm  select2" >
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}">{{ $product->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="help-block fp_applies_on_products_error"> Product(s) are required </span>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Use as discount</label>
                                                    <div class="col-md-8">
                                                        <div class="mt-radio-inline">
                                                            <label class="mt-radio">
                                                                <input type="radio" name="fp_as_discount" class="fp_as_discount"  value="cheapest" checked="checked" > Cheapest Product
                                                                <span></span>
                                                            </label>
                                                            <label class="mt-radio">
                                                                <input type="radio" name="fp_as_discount"  class="fp_as_discount" value="product"> Specific Product
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="fp_specific_product ">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label">Choose Product</label>
                                                    <div class="col-md-8">
                                                        <select name="fp_use_as_discount" id="fp_use_as_discount" data-tags="true" data-placeholder="Select Product" data-allow-clear="true" class="form-control input-inline input-sm  select2"  >
                                                            <option></option>
                                                            @foreach($products as $product)
                                                                <option value="{{$product->id}}">{{ $product->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <span class="help-block fp_use_as_discount_error"> Product is required </span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                            <div class="row">
                                                <div class="col-md-offset-4 col-md-7">
                                                    <button type="button" id="submitbtn" class="btn green"> Save Action</button>
                                                </div>
                                            </div>

                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
        </div>

        <div class="modal fade in" id="quick-edit-model" tabindex="-1" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Modal Title</h4>
                    </div>
                    <div class="modal-body"> Modal body goes here </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="button" class="btn green">Save changes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

    {{-- end of tab content --}}
@endsection

@section('scripts')
    <script type="text/javascript">
        $('body').on('change', '#pricing_method', function () {
            $('.method_div').removeClass('active');
            if($(this).val() == 1)
            {
                $('#percentage_div').addClass('active');
                $('#p_type').val('p') ;
            }else if($(this).val() == 2){
                $('#fixed_div').addClass('active');
                $('#p_type').val('f') ;
            }else if($(this).val() == 3){
                $('#free_shipping_div').addClass('active');
                $('#p_type').val('fs') ;
            }else if($(this).val() == 4){
                $('#free_product_div').addClass('active');
                $('#p_type').val('fp') ;
            }
        });


        $('body').on('change', '.p_applies_on', function () {
            $('.p_applies_on_div').removeClass('active');
            if($(this).val() == 'all')
            {
                laodAllproducts('p');
                $('.p_percentage_all_div').addClass('active');
            }else if($(this).val() == 'category'){
                $('.p_percentage_category_div').addClass('active');
            }else if($(this).val() == 'products'){
                laodAllproducts('p');
                $('.p_percentage_products_div').addClass('active');
            }
        });


        $('body').on('change', '.f_applies_on', function () {
            $('.f_applies_on_div').removeClass('active');
            if($(this).val() == 'all')
            {
                laodAllproducts('f');
                $('.f_percentage_all_div').addClass('active');
            }else if($(this).val() == 'category'){
                $('.f_percentage_category_div').addClass('active');
            }else if($(this).val() == 'products'){
                laodAllproducts('f');
                $('.f_percentage_products_div').addClass('active');
            }
        });

       /* $('body').on('change', '.fs_applies_on', function () {
            $('.fs_applies_on_div').removeClass('active');
            if($(this).val() == 'all')
            {
                laodAllproducts('fs');
                $('.fs_percentage_all_div').addClass('active');
            }else if($(this).val() == 'category'){
                $('.fs_percentage_category_div').addClass('active');
            }else if($(this).val() == 'products'){
                $('.fs_percentage_products_div').addClass('active');
            }
        });*/





        $('body').on('change', '.fp_applies_on', function () {
            $('.fp_applies_on_div').removeClass('active');
            if($(this).val() == 'all')
            {
                laodAllproducts('fp');
                $('.fp_percentage_all_div').addClass('active');
            }else if($(this).val() == 'category'){

                $('.fp_percentage_category_div').addClass('active');
            }else if($(this).val() == 'products'){
                laodAllproducts('fp');
                $('.fp_percentage_products_div').addClass('active');
            }
        });

        $("#p_applies_category").change(function() {
            var cat = $(this).val();
            $.ajax({
                url: '/ajax/get_category_products',
                data: {catid:cat},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    $('#p_all_exclude').empty().append(response.select_options);
                    $('#p_all_exclude').select2();

                }
            });
        });

        $("#f_applies_on_category").change(function() {
            var cat = $(this).val();
            $.ajax({
                url: '/ajax/get_category_products',
                data: {catid:cat},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    $('#f_all_exclude').empty().append(response.select_options);
                    $('#f_all_exclude').select2();

                }
            });
        });



        $('body').on('change', 'input:radio[name=p_coupon_based]', function () {
            if (this.value == 'yes') {
                $('#p_coupon_div').show();
            }
            else if (this.value == 'no') {
                $('#p_coupon_div').hide();
            }
        });

        $('body').on('change', 'input:radio[name=f_coupon_based]', function () {
            if (this.value == 'yes') {
                $('#f_coupon_div').show();
            }
            else if (this.value == 'no') {
                $('#f_coupon_div').hide();
            }
        });



        $('body').on('click', '#add_order_value_btn', function () {
            $("#add_order_value_btn i").toggleClass('fa-minus');
            $('#add_order_value_div').toggle();
        });

        $('body').on('click', '#f_add_order_value_btn', function () {
            $("#f_add_order_value_btn i").toggleClass('fa-minus');
            $('#f_add_order_value_div').toggle();
        });


        $('body').on('click', '#save_order_value', function () {
            if($('#min_order_new').val() == '' && $('#max_order_new').val() == '')
            {
                $('.err_msg').html('Min or Max order value is required');
                return false
            }else
            {
                $('.err_msg').html('');
            }

            if($('#min_order_new').val() !='') {
                if ($('#min_order_new').val().match(/^\d+$/)) {
                    $('.err_msg').html('');
                } else {

                    $('.err_msg').html('values must be numbers only');
                    return false
                }
            }

            if($('#max_order_new').val() !='') {
                if ($('#max_order_new').val().match(/^\d+$/)) {
                    $('.err_msg').html('');
                } else {

                    $('.err_msg').html('max value must be a number or empty');
                    return false
                }
            }


            $.ajax({
                url: '/ajax/save_order_value',
                data: {minval:$('#min_order_new').val(), maxval:$('#max_order_new').val()},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.action  == true )
                    {
                        $('#p_order_value')
                                .append($("<option></option>")
                                        .attr("value",response.option_id)
                                        .text(response.option_text));
                        $("#add_order_value_btn i").toggleClass('fa-minus');
                        $('#add_order_value_div').toggle();

                        $('#p_order_value').select2();

                        $('#min_order_new').val('');
                        $('#max_order_new').val('');
                    }else if(response.action == false){
                        $('.err_msg').html(response.msg);
                    }


                }
            });


        });

        $('body').on('click', '#f_save_order_value', function () {

            if($('#f_min_order_new').val() == '' && $('#f_max_order_new').val() == '')
            {
                $('.f_err_msg').html('Min or Max order value is required');
                return false
            }else
            {
                $('.f_err_msg').html('');
            }

            if($('#f_min_order_new').val() !='') {
                if ($('#f_min_order_new').val().match(/^\d+$/)) {
                    $('.f_err_msg').html('');
                } else {

                    $('.f_err_msg').html('values must be numbers only');
                    return false
                }
            }

            if($('#f_max_order_new').val() !='') {
                if ($('#f_max_order_new').val().match(/^\d+$/)) {
                    $('.f_err_msg').html('');
                } else {

                    $('.f_err_msg').html('max value must be a number or empty');
                    return false
                }
            }


            $.ajax({
                url: '/ajax/save_order_value',
                data: {minval:$('#f_min_order_new').val(), maxval:$('#f_max_order_new').val()},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.action  == true )
                    {
                        $('#f_order_value')
                                .append($("<option></option>")
                                        .attr("value",response.option_id)
                                        .text(response.option_text));
                        $("#f_add_order_value_btn i").toggleClass('fa-minus');
                        $('#f_add_order_value_div').toggle();

                        $('#f_order_value').select2();

                        $('#f_min_order_new').val('');
                        $('#f_max_order_new').val('');
                    }else if(response.action == false){
                        $('.f_err_msg').html(response.msg);
                    }
                }
            });


        });


        $('body').on('change', 'input:radio[name=fp_as_discount]', function () {
            if (this.value == 'product') {
                $('.fp_specific_product').show();
            }
            else if (this.value != 'product') {
                $('.fp_specific_product').hide();
            }
        });



        $('body').on('change', 'input:radio[name=eligible_customers_type]', function () {
            if (this.value != 'all') {
                $('.min_past_orders_div').show();
            }
            else if (this.value == 'all') {
                $('.min_past_orders_div').hide();
            }
        });


        function laodAllproducts(p_type)
        {
            $.ajax({
                url: '/ajax/get_category_products',
                data: {catid:'all'},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    $('#'+p_type+'_all_exclude').empty().append(response.select_options);
                    $('#'+p_type+'_all_exclude').select2();
                }
            });
        }

        function getYearMonths(year, action_type) {
            $.ajax({
                url: '/ajax/get_months_of_year',
                data: {year:year},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    $('#'+action_type).empty().append(response.select_options);
                    if(action_type == 'p_from_month')
                    {
                        getMonthDays(year, $('#p_from_month').val(), 'p_from_day');
                    }

                    if(action_type == 'p_to_month')
                    {
                        getMonthDays(year, $('#p_to_month').val(), 'p_to_day');
                    }

                }
            });
        }

        function getMonthDays(year, month , action_type) {
            $.ajax({
                url: '/ajax/get_days_of_month',
                data: {year:year, month:month},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                    $('#'+action_type).empty().append(response.select_options);
                    (action_type);
                }
            });
        }

        $(document).ready(function(){
            var js_date = new Date();
            getYearMonths(js_date.getFullYear(), 'p_from_month');
            getMonthDays(js_date.getFullYear(), js_date.getMonth()+1, 'p_from_day' );

            getYearMonths(js_date.getFullYear(), 'p_to_month');
            getMonthDays(js_date.getFullYear(), js_date.getMonth()+1, 'p_to_day' );
        });


        $('body').on('click', '#submitbtn', function () {

            var eligible_customers_type = $("input[name='eligible_customers_type']:checked").val();

            var from_date = fromDate() ;

            var to_date = toDate();

            var price_method = $('#pricing_method').val();

            switch(price_method) {
                case '1':

                    var errors = false;
                    if($('#action_title').val() == '') { hasError('action_title'); errors = true; }else{ hasNoError('action_title');   }
                    if(eligible_customers_type == 'specific'){
                        if($('#min_past_orders').val()  == '') {    hasError('min_past_orders'); errors = true;  }else{ hasNoError('min_past_orders') }
                    }
                    if(new Date(from_date) >= new Date(to_date))
                    { errors = true; $('.datetime_error').show(); }else{ $('.datetime_error').hide(); }

                    if($('#p_discount').val()  == '') {    hasError('p_discount'); errors = true;   }else{ hasNoError('p_discount') }
                    if($('#p_discount').val().match(/^\d+$/)) {  hasNoError('p_discount'); } else { hasError('p_discount'); errors = true;}



                    if($('#p_order_value').val()  == null || $('#p_order_value').val()  == '') {    hasError('p_order_value'); errors = true;   }else{ hasNoError('p_order_value') }


                        var applies_on = $("input[name='p_applies_on']:checked").val();

                    if(applies_on == 'category') {
                        if($('#p_applies_category').val() == '')
                        {
                            errors = true;
                            hasError('p_applies_category');
                        }else{
                            hasNoError('p_applies_category');
                        }
                    }

                    if(applies_on == 'products') {
                        //alert($('#p_applies_on_products').val());
                        if($('#p_applies_on_products').val() == null || $('#p_applies_on_products').val() == '')
                        {
                            errors = true;
                            hasError('p_applies_on_products');
                        }else{
                            hasNoError('p_applies_on_products');
                        }
                    }


                    var p_coupon_based = $("input[name='p_coupon_based']:checked").val();

                    if(p_coupon_based == 'yes') {
                        if($('#p_coupon_id').val() == '')
                        {
                            errors = true;
                            hasError('p_coupon_id');
                        }else{
                            hasNoError('p_coupon_id');
                        }
                    }
                    if(errors == false)
                    {
                        submitAction(2);
                    }
                    break;
                case '2':

                    var errors = false;
                    if($('#action_title').val() == '') { hasError('action_title'); errors = true; }else{ hasNoError('action_title');   }
                    if(eligible_customers_type == 'specific'){
                        if($('#min_past_orders').val()  == '') {    hasError('min_past_orders'); errors = true;  }else{ hasNoError('min_past_orders') }
                    }
                    if(new Date(from_date) >= new Date(to_date))
                    { errors = true; $('.datetime_error').show(); }else{ $('.datetime_error').hide(); }


                    if($('#f_discount').val()  == '') {    hasError('f_discount'); errors = true;  }else{ hasNoError('f_discount') }
                    if($('#f_discount').val().match(/^\d+$/)) {  hasNoError('f_discount'); } else { hasError('f_discount'); errors = true;}

                    if($('#f_order_value').val()  == null || $('#f_order_value').val()  == '') {    hasError('f_order_value'); errors = true;   }else{ hasNoError('f_order_value') }
                    var applies_on = $("input[name='f_applies_on']:checked").val();

                    if(applies_on == 'category') {
                        if($('#f_applies_on_category').val() == '')
                        {
                            hasError('f_applies_on_category');
                        }else{
                            hasNoError('f_applies_on_category');
                        }
                    }

                    if(applies_on == 'products') {

                        if($('#f_applies_on_products').val() == null || $('#f_applies_on_products').val() == '')
                        {
                            hasError('f_applies_on_products');
                        }else{
                            hasNoError('f_applies_on_products');
                        }
                    }


                    var f_coupon_based = $("input[name='f_coupon_based']:checked").val();

                    if(f_coupon_based == 'yes') {
                        if($('#f_coupon_id').val() == '')
                        {
                            hasError('f_coupon_id');
                        }else{
                            hasNoError('f_coupon_id');
                        }
                    }

                    if(errors == false)
                    {
                        submitAction(2);
                    }
                    break;
                case '3':

                    var errors = false;
                    if($('#action_title').val() == '') { hasError('action_title'); errors = true; }else{ hasNoError('action_title');   }
                    if(eligible_customers_type == 'specific'){
                        if($('#min_past_orders').val()  == '') {    hasError('min_past_orders'); errors = true;  }else{ hasNoError('min_past_orders') }
                    }
                    if(new Date(from_date) >= new Date(to_date))
                    { errors = true; $('.datetime_error').show(); }else{ $('.datetime_error').hide(); }

                    if($('#fs_min_order_value').val()  == '') {    hasError('fs_min_order_value');  }else{ hasNoError('fs_min_order_value') }

                    if(errors == false)
                    {
                        submitAction(3);
                    }
                    break;


                case '4':

                    var errors = false;
                    if($('#action_title').val() == '') { hasError('action_title'); errors = true; }else{ hasNoError('action_title');   }
                    if(eligible_customers_type == 'specific'){
                        if($('#min_past_orders').val()  == '') {    hasError('min_past_orders'); errors = true;  }else{ hasNoError('min_past_orders') }
                    }
                    if(new Date(from_date) >= new Date(to_date))
                    { errors = true; $('.datetime_error').show(); }else{ $('.datetime_error').hide(); }

                    if($('#fp_number_of_products').val()  == '') {    hasError('fp_number_of_products');  }else{ hasNoError('fp_number_of_products') }
                    var applies_on = $("input[name='fp_applies_on']:checked").val();

                    if(applies_on == 'category') {
                        if($('#fp_applies_on_category').val() == '')
                        {
                            hasError('fp_applies_on_category');
                        }else{
                            hasNoError('fp_applies_on_category');
                        }
                    }

                    if(applies_on == 'products') {

                        if($('#fp_applies_on_products').val() == null || $('#fp_applies_on_products').val() == '')
                        {
                            hasError('fp_applies_on_products');
                        }else{
                            hasNoError('fp_applies_on_products');
                        }
                    }


                    var cheap_or_product = $("input[name='fp_as_discount']:checked").val();

                    if(cheap_or_product == 'product') {
                        if($('#fp_use_as_discount').val() == '' || $('#fp_use_as_discount').val() == null)
                        {
                            hasError('fp_use_as_discount');
                        }else{
                            hasNoError('fp_use_as_discount');
                        }
                    }

                    if(errors == false)
                    {
                        submitAction(4);
                    }
                    break;
            }


        });


        function submitAction()
        {
            $('#action_form').submit();
           /* var form = $('#action_form');
            console.log(form.serialize());
            $.ajax( {
                type: "POST",
                url: form.attr( 'action' ),
                data: form.serialize(),
                dataType: 'json',
                success: function( response ) {
                    if(response.action == 'false')
                    {
                        $('#error_msg').html(response.msg);
                    }else if(response.action == 'true'){
                        window.location.href = '<?php echo url('actions'); ?>';
                    }

                }
            } );*/

        }

        function hasError(el) {
            $('#'+el).parent().addClass('has-error');
            $('.'+el+'_error').show();
        }

        function hasNoError(el) {
            $('#'+el).parent().removeClass('has-error');
            $('.'+el+'_error').hide();
        }

        function fromDate() {
            var py = $('#p_from_year').val();
            var pm = $('#p_from_month').val();
            var pd = $('#p_from_day').val();
            var ph = $('#p_from_hour').val();
            var pmin = $('#p_from_min').val();

            return py +'-' + pm + '-' + pd +  ' ' + ph + ':' + pmin;
        }

        function toDate() {
            var ty = $('#p_to_year').val();
            var tm = $('#p_to_month').val();
            var td = $('#p_to_day').val();
            var th = $('#p_to_hour').val();
            var tmin = $('#p_to_min').val();

            return ty +'-' + tm + '-' + td +  ' ' + th + ':' + tmin;
        }


    </script>

    {!! HTML::script('assets/js/actions_js.js') !!}
    {!! HTML::script('assets/js/actions_plugins.js') !!}
    {!! HTML::script('assets/js/laravel-delete.js') !!}

    <script type="text/javascript">
        $('.select2').select2({ placeholder: 'Select'});
        $('#date_range').daterangepicker({
            "timePicker": true,
            "timePicker24Hour": true,
            "startDate": "09/09/2016",
            "endDate": "09/15/2016"
        }, function(start, end, label) {
            ("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
        });
    </script>

@endsection
