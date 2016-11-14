@extends('layouts.master')

@section('css')

{!! HTML::style('assets/css/coupon_plugins.css') !!}
@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
        <li><a href={{ route('coupons.index') }}>Coupons</a></li>
    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title">Coupon
    <small>Add Coupon</small>
</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->

<form id="coupon-form" action="{{action('CouponsController@store')}}"  method="post">
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
            <div class="col-md-12 error_div">

            </div>
        <div class="col-md-4">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" name="code" id="code" value="">
                    <input type="hidden" name="coupon_id" value="" id="coupon_id">

                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" id="description" ></textarea>
                    <input type="hidden" name="coupon_id" value="" id="coupon_id">

                </div>



                <div class="form-group">
                    <label>Discout Type</label>
                    <select name="discount_type" id="discount_type" class="form-control">
                        <option value="cart" >Cart Discount</option>
                        <option value="cart%">Cart % Discount</option>
                        <option value="product%">Product % Discount</option>

                    </select>
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label >Coupon Amount</label>
                    <input type="text" name="coupon_amount" id="coupon_amount"  class="form-control" >
                    <div class="clearfix"></div>
                </div>

                <div class="form-group">
                    <label >Allow Free Shipping</label>
                    <input name="is_shipping" id="is_shipping"  type="checkbox">
                    <div class="clearfix"></div>

                </div>
                <div class="form-group">
                    <label>Coupon Expiry Date</label>
                    <input type="text" class="form-control date-picker" data-date="{!! date('m/d/Y') !!}" data-date-format="mm/dd/yyyy" name="coupon_expiry_date" value="">
                    <div class="clearfix"></div>

                </div>

                <div class="form-group">
                    <label>Minimum Spend</label>
                    <input type="text" name="min_spend" class="form-control">
                    <div class="clearfix"></div>

                </div>
                <div class="form-group">
                    <label>Maximum Spend</label>
                    <input type="text" name="max_spend" class="form-control" >
                    <div class="clearfix"></div>

                </div>
            <div class="form-group">
                <label >Show on cart/ checkout?</label>
                <input name="show_cart" id="show_cart"  type="checkbox">
                <div class="clearfix"></div>

            </div>




            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label>Individual use only</label>
                    <label><input name="is_individual" id="is_individual" type="checkbox"></label>
                    <div class="clearfix"></div>
                </div>


                <div class="form-group">
                    <label>Products</label>


                    <select id="products"  name="products[]" class="form-control select2-multiple pselect2-multiple" multiple  placeholder="Search for a product">

                    </select>

                    <div class="clearfix"></div>

                </div>
                <div class="form-group">
                    <label>Exclude Products</label>

                    <select id="exc_products" name="exc_products[]" class="form-control select2-multiple epselect2-multiple" multiple placeholder="Search for a product">

                    </select>


                    <div class="clearfix"></div>

                </div>
                <hr>
                <div class="form-group">
                    <label>Categories</label>

                    <select id="categories" name="categories[]" class="form-control  cselect2-multiple" multiple placeholder="Any Category">

                    </select>


                    <div class="clearfix"></div>

                </div>
                <div class="form-group">
                    <label>Exclude Categories</label>

                    <select id="exc_categories" name="exc_categories[]" class="form-control ecselect2-multiple" multiple placeholder="No Categories">

                    </select>


                    <div class="clearfix"></div>

                </div>
                <hr>


                <div class="form-group">
                    <label >Usage limit per coupon</label>
                    <input type="text" id="limit_coupon" name="limit_coupon" class="form-control" >
                    <div class="clearfix"></div>

                </div>
                <div class="form-group">
                    <label>Usage limit per user</label>
                    <input type="text" name="limit_user" class="form-control" >
                    <div class="clearfix"></div>
                </div>



            </div>
        <div class="col-md-3">
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gear"></i>Publish
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <input type="hidden" name="action" id="form-action" value="">
                    <button  id="publish"  class="btn btn-danger pull-left btn-sm publish-post">Publish</button>
                    <button  id="draft"  class="btn btn-primary pull-right btn-sm ">Draft</button>

                    <div class="clearfix"></div>
                    <hr>
                    <ul class="published">
                        <li><i class="fa fa-key"></i> Status: <strong class="showpub">Publish</strong> <a class="editpublish" href="javascript:;">Edit</a>
                            <div id="available-status" class="row" style="display:none">
                                <div class="col-md-12">
                                    <select name="status" id="status" class="form-control" >
                                        <option value="publish">Publish</option>
                                        <option value="draft">Draft</option>

                                    </select>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-12">

                                    <button class="btn btn-default btn-sm addstatus">OK</button><a  class="btn btn-default btn-sm cancelpublish"  href="javascript:;">Cancel</a>
                                </div> </div>
                        </li>

                        <li><i class="icon-calendar"></i>Publish: Immediately <a class="editcal" href="javascript:;">Edit</a>
                            <div id="available-calender" class="row" style="display:none">
                                <div class="col-md-12">


                                    <select style="width:80px; padding:0px" name="mm" id="mm"  >
                                        @for($i = 1 ; $i<= 12; $i++)
                                            {{ $i = sprintf("%02d", $i) }}
                                            <option value="{{ $i }}" <?php if($i == date('m') ) { echo "selected" ;} ?>   >{{ $i }}- {{  date("M", mktime(0, 0, 0, $i, 10)) }}</option>
                                        @endfor
                                    </select>

                                    <input style="width:20px; padding:0px" size="2" type="text" name="dd" id="dd" value="{{date('d')}}" >,
                                    <input style="width:40px; padding:0px" size="4" type="text" name="yy" id="yy" value="{{date('Y')}}" >@
                                    <input style="width:20px; padding:0px"   size="2" type="text" name="hr" id="hr" value="{{date('H')}}" >:
                                    <input style="width:20px; padding:0px" size="2" type="text" name="min" id="min" value="{{date('i')}}" >




                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><button class="btn btn-default btn-sm addcal">OK</button>
                                    <a class="btn btn-default btn-sm cancelcal"  href="javascript:;">Cancel</a></div>
                            </div></li>
                    </ul>

                    <hr>


                    <div class="clearfix"></div>

                </div>

            </div>


        </div>

    </div>
</form>






@endsection

@section('scripts')
    {!! HTML::script('assets/js/coupon_plugins.js') !!}
    {!! HTML::script('assets/js/coupon_js.js') !!}

    {!! HTML::script('assets/js/global.js') !!}
    {!! HTML::script('assets/js/coupon.js') !!}
    <script>
        $('.date-picker').datepicker();
        $("input[name='limit_coupon']").TouchSpin({
            verticalbuttons: true
        });
        $("input[name='limit_user']").TouchSpin({
            verticalbuttons: true
        });
        $(function(){

        })
    </script>
@endsection