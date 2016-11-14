@extends('layouts.master')

@section('css')

{!! HTML::style('assets/css/reports_plugins.css') !!}
@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li> <i class="fa fa-angle-double-right"></i>
        <li><a href={{ route('revenue_report.index') }}>Omzetrapport</a></li>
    </ul>

</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title">Omzetrapport

</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->

<form id="revenue-form" action=""  method="post">
    <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />

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

            <div class="row">
                <div class="col-md-12">
                    <div class="col-sm-6 npl">
                     <a href="{{route('revenue_report.index')}}">Verkopen per datum</a> <span class="badge badge-primary"></span> | <a href="{{url('/revenue_report/sale_by_product')}}">Verkopen per product</a> <span class="badge badge-primary"></span>

                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet">

                        <div class="portlet-body">
                        <ul class="nav nav-tabs">
                            <li>
                                 <a  href="#tab_year" data-toggle="tab">Jaar </a>
                            </li>
                            <li>
                                <a href="#tab_lmonth" data-toggle="tab"> Vorige maand</a>
                            </li>
                            <li>
                                <a href="#tab_cmonth" data-toggle="tab">Huidige maand</a>
                            </li>
                            <li>
                                <a href="#tab_14days" data-toggle="tab">Afgelopen 14 dagen</a>
                            </li>
                            <li  class="active">
                                <a href="#tab_7days" data-toggle="tab"> Afgelopen 7 dagen</a>
                            </li>
                            <li>
                                <a href="#tab_yesterday" data-toggle="tab">Gisteren</a>
                            </li>
                            <li>
                                <a href="#tab_today" data-toggle="tab">Vandaag</a>
                            </li>
                            <li>
                                <a href="#tab_custom" data-toggle="tab">Aangepast </a>

                            </li>

                        </ul>
                        <div class="tab-content tabcustom" style="position: relative" >



                                    <div class="tab-pane fade  custom_tabwidth"   id="tab_year" style="">
                                        <div class="row">
                                            <div  class="col-md-8"></div>
                                            <div  class="col-md-4">
                                                <button id="year_per_day" type="button"  class="btn btn-default" >Per Dag</button>
                                                <button id="year_per_week" type="button"  class="btn btn-default" >Per Week</button>
                                                <button id="year_per_month" type="button"  class="btn btn-default" >Per Maand</button>
                                            </div>
                                        </div>
<hr>
                                        <div  class="col-md-3">

                                        <div class="btn-group-vertical custom_buttonwidth">
                                            <button id="gross_sale" type="button"  class="btn btn-default  highlight_series tips bheight" data-series="3" data-tip="Dit"></button>
                                            <button id="net_gross_sale" type="button" class="btn btn-default  highlight_series tips bheight" data-series="4" data-tip="Dit"></button>
                                            <button id="average_monthly_sale" type="button"  class="btn btn-default  highlight_series tips bheight" data-series="2" data-tip="Dit"></button>
                                            <button id="orders_placed" type="button"  class="btn btn-default  highlight_series tips bheight" data-series="1" data-tip="Dit"></button>
                                            <button id="total_item" type="button"  class="btn btn-default  highlight_series tips bheight" data-series="0" data-tip="Dit"></button>
                                            <button id="total_refund" type="button"  class="btn btn-default  highlight_series tips bheight" data-series="5" data-tip="Dit"></button>

                                        </div>


                                    </div>
                                        <div   class="col-md-9">
                                            <div class="chart-container">
                                                <div id="dateBy_year" class="custom_graphwidth"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade custom_tabwidth" style=" color: #8a8a8a" id="tab_lmonth">
                                        <div class="row">
                                            <div  class="col-md-9"></div>
                                            <div  class="col-md-3">
                                                <button id="lmonth_per_day" type="button"  class="btn btn-default" >Per Dag</button>
                                                <button id="lmonth_per_week" type="button"  class="btn btn-default" >Per Week</button>
                                            </div>
                                        </div>
                                        <hr>

                                        <div  class="col-md-3">
                                            <div class="btn-group-vertical custom_buttonwidth">
                                                <button id="gross_sale_lmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="3" data-tip="Dit"></button>
                                                <button id="net_gross_sale_lmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="4" data-tip="Dit"></button>
                                                <button id="average_monthly_sale_lmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="2" data-tip="Dit"></button>
                                                <button id="orders_placed_lmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                                <button id="total_item_lmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                                <button id="total_refund_lmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="5" data-tip="Dit"></button>

                                            </div>


                                        </div>
                                        <div   class="col-md-9">
                                            <div class="chart-container">
                                                <div id="dateBy_lmonth" class="custom_graphwidth"></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade custom_tabwidth" id="tab_cmonth">
                                        <div class="row">
                                            <div  class="col-md-9"></div>
                                            <div  class="col-md-3">
                                                <button id="cmonth_per_day" type="button"  class="btn btn-default" >Per Dag</button>
                                                <button id="cmonth_per_week" type="button"  class="btn btn-default" >Per Week</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div  class="col-md-3">
                                            <div class="btn-group-vertical custom_buttonwidth">
                                                <button id="gross_sale_cmonth" type="button" class="btn btn-default  highlight_series2 tips bheight" data-series="3" data-tip="Dit"></button>
                                                <button id="net_gross_sale_cmonth" type="button" class="btn btn-default  highlight_series2 tips bheight" data-series="4" data-tip="Dit"></button>
                                                <button id="average_monthly_sale_cmonth" type="button" class="btn btn-default  highlight_series2 tips bheight" data-series="2" data-tip="Dit"></button>
                                                <button id="orders_placed_cmonth" type="button" class="btn btn-default  highlight_series2 tips bheight" data-series="1" data-tip="Dit"></button>
                                                <button id="total_item_cmonth" type="button" class="btn btn-default  highlight_series2 tips bheight" data-series="0" data-tip="Dit"></button>
                                                <button id="total_refund_cmonth" type="button" class="btn btn-default  highlight_series2 tips bheight" data-series="5" data-tip="Dit"></button>

                                            </div>
                                        </div>
                                        <div   class="col-md-9">
                                            <div class="chart-container">
                                                <div id="dateBy_cmonth" class="custom_graphwidth"></div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane fade custom_tabwidth" id="tab_14days">
                                        <div class="row">
                                            <div  class="col-md-9"></div>
                                            <div  class="col-md-3">
                                                <button id="14days_per_day" type="button"  class="btn btn-default" >Per Dag</button>
                                                <button id="14days_per_week" type="button"  class="btn btn-default" >Per Week</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div  class="col-md-3">
                                            <div class="btn-group-vertical custom_buttonwidth">
                                                <button id="gross_sale_14days" type="button" class="btn btn-default  highlight_series3 tips bheight" data-series="3" data-tip="Dit"></button>
                                                <button id="net_gross_sale_14days" type="button" class="btn btn-default  highlight_series3 tips bheight" data-series="4" data-tip="Dit"></button>
                                                <button id="average_monthly_sale_14days" type="button" class="btn btn-default  highlight_series3 tips bheight" data-series="2" data-tip="Dit"></button>
                                                <button id="orders_placed_14days" type="button" class="btn btn-default  highlight_series3 tips bheight" data-series="1" data-tip="Dit"></button>
                                                <button id="total_item_14days" type="button" class="btn btn-default  highlight_series3 tips bheight" data-series="0" data-tip="Dit"></button>
                                                <button id="total_refund_14days" type="button" class="btn btn-default  highlight_series3 tips bheight" data-series="5" data-tip="Dit"></button>

                                            </div>
                                        </div>
                                        <div   class="col-md-9">
                                            <div class="chart-container">
                                                <div id="dateBy_14days" class="custom_graphwidth"></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade active in custom_tabwidth" id="tab_7days">
                                        <div  class="col-md-3">
                                            <div class="btn-group-vertical custom_buttonwidth">
                                                <button id="gross_sale_7days" type="button" class="btn btn-default  highlight_series4 tips bheight" data-series="3" data-tip="Dit"></button>
                                                <button id="net_gross_sale_7days" type="button" class="btn btn-default  highlight_series4 tips bheight" data-series="4" data-tip="Dit"></button>
                                                <button id="average_monthly_sale_7days" type="button" class="btn btn-default  highlight_series4 tips bheight" data-series="2" data-tip="Dit"></button>
                                                <button id="orders_placed_7days" type="button" class="btn btn-default  highlight_series4 tips bheight" data-series="1" data-tip="Dit"></button>
                                                <button id="total_item_7days" type="button" class="btn btn-default  highlight_series4 tips bheight" data-series="0" data-tip="Dit"></button>
                                                <button id="total_refund_7days" type="button" class="btn btn-default  highlight_series4 tips bheight" data-series="5" data-tip="Dit"></button>

                                            </div>
                                        </div>
                                        <div   class="col-md-9">
                                            <div class="chart-container">
                                                 <div id="dateBy_7days" class="custom_graphwidth"></div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade custom_tabwidth" id="tab_yesterday">
                                        <div  class="col-md-3">
                                            <div class="btn-group-vertical custom_buttonwidth">
                                                <button id="gross_sale_yesterday" type="button" class="btn btn-default  highlight_series5 tips bheight" data-series="3" data-tip="Dit"></button>
                                                <button id="net_gross_sale_yesterday" type="button" class="btn btn-default  highlight_series5 tips bheight" data-series="4" data-tip="Dit"></button>
                                                <button id="average_monthly_sale_yesterday" type="button" class="btn btn-default  highlight_series5 tips bheight" data-series="2" data-tip="Dit"></button>
                                                <button id="orders_placed_yesterday" type="button" class="btn btn-default  highlight_series5 tips bheight" data-series="1" data-tip="Dit"></button>
                                                <button id="total_item_yesterday" type="button" class="btn btn-default  highlight_series5 tips bheight" data-series="0" data-tip="Dit"></button>
                                                <button id="total_refund_yesterday" type="button" class="btn btn-default  highlight_series5 tips bheight" data-series="5" data-tip="Dit"></button>

                                            </div>
                                        </div>
                                        <div   class="col-md-9">
                                            <div class="chart-container">
                                                <div id="dateBy_yesterday" class="custom_graphwidth"></div>
                                            </div>
                                        </div>

                                    </div>
                            <div class="tab-pane fade custom_tabwidth" id="tab_today">
                                <div  class="col-md-3">
                                    <div class="btn-group-vertical custom_buttonwidth">
                                        <button id="gross_sale_today" type="button" class="btn btn-default  highlight_series6 tips bheight" data-series="3" data-tip="Dit"></button>
                                        <button id="net_gross_sale_today" type="button" class="btn btn-default  highlight_series6 tips bheight" data-series="4" data-tip="Dit"></button>
                                        <button id="average_monthly_sale_today" type="button" class="btn btn-default  highlight_series6 tips bheight" data-series="2" data-tip="Dit"></button>
                                        <button id="orders_placed_today" type="button" class="btn btn-default  highlight_series6 tips bheight" data-series="1" data-tip="Dit"></button>
                                        <button id="total_item_today" type="button" class="btn btn-default  highlight_series6 tips bheight" data-series="0" data-tip="Dit"></button>
                                        <button id="total_refund_today" type="button" class="btn btn-default  highlight_series6 tips bheight" data-series="5" data-tip="Dit"></button>

                                    </div>
                                </div>
                                <div   class="col-md-9">
                                    <div class="chart-container">
                                        <div id="dateBy_today" class="custom_graphwidth"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade custom_tabwidth" id="tab_custom">
                                <div class="row">
                                <div  class="col-md-4">
                                <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="mm/dd/yyyy">
                                    <input type="text" class="form-control" id="from" name="from">
                                    <span class="input-group-addon"> tot </span>
                                    <input type="text" class="form-control" id="to"  name="to"> </div>
                                    </div>
                                    <div  class="col-md-8">
                                    <button id="go_custom" class="btn btn-default">UITVOEREN</button>
                                    </div></div>
                                <div class="row">
                                    <div  class="col-md-8"></div>
                                    <div  class="col-md-4">
                                        <button id="custom_per_day" type="button"  class="btn btn-default" >Per Dag</button>
                                        <button id="custom_per_week" type="button"  class="btn btn-default" >Per Week</button>
                                        <button id="custom_per_month" type="button"  class="btn btn-default" >Per Month</button>
                                    </div>
                                </div>
                                <hr>
                                <div  class="col-md-3">
                                    <div class="btn-group-vertical custom_buttonwidth">
                                       <button id="gross_sale_custom" type="button" class="btn btn-default  highlight_series7 tips bheight" data-series="3" data-tip="Dit"></button>
                                        <button id="net_gross_sale_custom" type="button" class="btn btn-default  highlight_series7 tips bheight" data-series="4" data-tip="Dit"></button>
                                        <button id="average_monthly_sale_custom" type="button" class="btn btn-default  highlight_series7 tips bheight" data-series="2" data-tip="Dit"></button>
                                        <button id="orders_placed_custom" type="button" class="btn btn-default  highlight_series7 tips bheight" data-series="1" data-tip="Dit"></button>
                                        <button id="total_item_custom" type="button" class="btn btn-default  highlight_series7 tips bheight" data-series="0" data-tip="Dit"></button>
                                        <button id="total_refund_custom" type="button" class="btn btn-default  highlight_series7 tips bheight" data-series="5" data-tip="Dit"></button>

                                    </div>
                                </div>
                                <div   class="col-md-9">
                                    <div class="chart-container">
                                        <div id="dateBy_custom" class="custom_graphwidth">

                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                        <div class="clearfix margin-bottom-20"> </div>
                        </div>
                    </div>
                </div>
            </div>


          </div>

  </div>
</form>


@endsection

@section('scripts')
    {!! HTML::script('assets/js/reports_plugins.js') !!}
    {!! HTML::script('assets/js/reports.js') !!}

    <script>
        $('.date-picker').datepicker();


    </script>



@endsection