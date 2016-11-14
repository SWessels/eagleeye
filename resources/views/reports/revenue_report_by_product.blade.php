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
                            <li >
                                 <a  href="#tab_product_year" data-toggle="tab">Jaar </a>
                            </li>
                          <li>
                                <a href="#tab_product_lmonth" data-toggle="tab"> Vorige maand</a>
                            </li>
                            <li>
                                <a href="#tab_product_cmonth" data-toggle="tab">Huidige maand</a>
                            </li>
                            <li>
                                <a href="#tab_product_14days" data-toggle="tab">Afgelopen 14 dagen</a>
                            </li>
                            <li class="active">
                                <a href="#tab_product_7days" data-toggle="tab"> Afgelopen 7 dagen</a>
                            </li>
                            <li>
                                <a href="#tab_product_yesterday" data-toggle="tab">Gisteren</a>
                            </li>
                            <li>
                                <a href="#tab_product_today" data-toggle="tab">Vandaag</a>
                            </li>
                            <li>
                                <a href="#tab_product_custom" data-toggle="tab">Aangepast </a>

                            </li>

                        </ul>
                        <div  ng-app="productApp"  class="tab-content tabcustom" >



                        <div  class="tab-pane fade  custom_tabwidth" ng-controller="listdata as data"  ng-cloak  id="tab_product_year" style="">

                        <div   class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                        <option>50</option>
                                        <option>100</option>
                                        <option>250</option>
                                        <option>500</option>
                                        <option>1000</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                </div>
                            </div>
                        <hr>


                        <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                        <thead>
                            <tr role="row" class="heading">
                                <th width="1%">Nr.</th><th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret"  ng-class="myClass" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret"  ng-class="myClass" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret"  ng-class="myClass" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret"  ng-class="myClass" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">&nbsp;Netto omzet<span name="cs" id="caret"  ng-class="myClass" ></span></a></th>
                            </tr>

                        </thead>
                        <tbody>

                        <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Laadt gegevens...</td></tr>
                        <div  ng-show="data.users.length  > 0">
                             <div>
                                <tr ng-repeat="x in data.users" total-items="data.total_count">
                                    <td>[[ x.count ]]</td>
                                    <td ><a onclick="showVar(this.id, 'year')"  id="year-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong></td>
                                    <td>[[ x.sales ]]</td>
                                    <td>[[ x.returns ]]</td>
                                    <td>[[ x.revenue ]]</td>
                                    <td>[[ x.net_revenue ]]</td>
                                </tr>

                            </div>
                        </div>
                        </tbody>
                        </table>
                        </div>
                         <div  class="col-md-6"  >
                             <div  class="sticky"  >
                                <div class="chart-container" >

                                 <div id="productBy_year" class="custom_graphwidth" >
                                    <span class="customspan_graph">
                                    Kies product om grafiek te bekijken.
                                    </span>

                                </div>
                            </div>
                                <hr>
                                <div class="row">
                                 <div  class="col-md-12">

                                     <div class="btn-group-vertical custom_buttonwidth">
                                        <button id="pname" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                        <button id="sales" type="button"  class="btn btn-default  highlight_series tips bheight" data-series="0" data-tip="Dit"></button>
                                        <button id="refunds" type="button" class="btn btn-default  highlight_series tips bheight" data-series="1" data-tip="Dit"></button>
                                    </div>
                                </div>
                             </div>
                             </div>
                         </div>
                         </div>
                        <div class="tab-pane fade custom_tabwidth" style=" color: #8a8a8a" ng-controller="listdata_lmonth as data"  id="tab_product_lmonth">


                        <div class="col-md-6">
                            <div class="row">
                        <div class="col-md-6">
                            <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                <option>50</option>
                                <option>100</option>
                                <option>250</option>
                                <option>500</option>
                                <option>1000</option>
                            </select>


                        </div>
                                <div class="col-md-6">
                                    <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                </div>
                        </div>
                        <hr>


                        <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                            <thead>
                                <tr role="row" class="heading">

                                    <th width="1%">Nr.</th>
                                    <th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret_l"  ng-class="myClass_l" ></span></a></th>
                                    <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret_l"  ng-class="myClass_l" ></span></a></th>
                                    <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret_l"  ng-class="myClass_l" ></span></a></th>
                                    <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret_l"  ng-class="myClass_l" ></span></a></th>
                                    <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">Netto Omzet&nbsp;<span name="cs" id="caret_l"  ng-class="myClass_l" ></span></a>
                                    </th>
                                </tr>

                                </thead>
                                <tbody>

                                <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Laadt gegevens...</td></tr>
                                        <div  ng-show="data.users.length  > 0">
                                        <div  >
                                        <tr ng-repeat="x in data.users" total-items="data.total_count">

                                        <td>[[ x.count ]]</td>
                                        <td ><a onclick="showVar(this.id, 'lmonth')"  id="lmonth-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong>

                                        </td>
                                        <td>[[ x.sales ]]</td>
                                        <td>[[ x.returns ]]</td>
                                        <td>[[ x.revenue ]]</td>
                                        <td>[[ x.net_revenue ]]</td>

                                </tr>

                                </div>
                             </div>
                        </tbody>
                        </table>


                        </div>

                            <div  class="col-md-6">
                                <div  class="sticky">
                                    <div class="chart-container">
                                    <div id="productBy_lmonth" class="custom_graphwidth">
                                         <span class="customspan_graph">
                                        Kies product om grafiek te bekijken.
                                        </span>
                                    </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                    <div  class="col-md-12">

                                        <div class="btn-group-vertical custom_buttonwidth">
                                            <button id="pname_product_lmonth" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                            <button id="sales_product_lmonth" type="button"  class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                            <button id="refunds_product_lmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade custom_tabwidth" ng-controller="listdata_cmonth as data" id="tab_product_cmonth">

                            <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                        <option>50</option>
                                        <option>100</option>
                                        <option>250</option>
                                        <option>500</option>
                                        <option>1000</option>
                                    </select>


                                </div>
                                    <div class="col-md-6">
                                        <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                    </div>
                            </div>
                        <hr>


                        <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                        <thead>
                            <tr role="row" class="heading">

                                <th width="1%">Nr.</th>
                                <th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret_c"  ng-class="myClass_c" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret_c"  ng-class="myClass_c" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret_c"  ng-class="myClass_c" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret_c"  ng-class="myClass_c" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">Netto Omzet&nbsp;<span name="cs" id="caret_c"  ng-class="myClass_c" ></span></a></th>
                            </tr>

                        </thead>
                        <tbody>

                        <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Laadt gegevens...</td></tr>
                        <div  ng-show="data.users.length  > 0">
                            <div >
                            <tr ng-repeat="x in data.users" total-items="data.total_count">
                                <td>[[ x.count ]]</td>
                                <td ><a onclick="showVar(this.id, 'cmonth')"  id="cmonth-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong></td>
                                <td>[[ x.sales ]]</td>
                                <td>[[ x.returns ]]</td>
                                <td>[[ x.revenue ]]</td>
                                <td>[[ x.net_revenue ]]</td>
                            </tr>

                            </div>
                        </div>
                        </tbody>
                        </table>


                        </div>

                            <div class="col-md-6">
                                <div class="sticky">
                                    <div class="chart-container">
                                         <div id="productBy_cmonth" class="custom_graphwidth">
                                               <span class="customspan_graph">
                                            Kies product om grafiek te bekijken.
                                            </span>
                                         </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                    <div  class="col-md-12">

                                        <div class="btn-group-vertical custom_buttonwidth">
                                            <button id="pname_product_cmonth" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                            <button id="sales_product_cmonth" type="button"  class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                            <button id="refunds_product_cmonth" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade custom_tabwidth" ng-controller="listdata_14day as data" id="tab_product_14days">
                            <div    class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                            <option>50</option>
                                            <option>100</option>
                                            <option>250</option>
                                            <option>500</option>
                                            <option>1000</option>
                                        </select>
                                    </div>
                                <div class="col-md-6">
                                     <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                </div>
                                </div>
                            <hr>


                            <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                            <thead>
                                <tr role="row" class="heading">
                                    <th width="1%">No.</th>
                                    <th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret_14"  ng-class="myClass_14" ></span></a></th>
                                    <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret_14"  ng-class="myClass_14" ></span></a></th>
                                    <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret_14"  ng-class="myClass_14" ></span></a></th>
                                    <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret_14"  ng-class="myClass_14" ></span></a></th>
                                    <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">Netto Omzet&nbsp;<span name="cs" id="caret_14"  ng-class="myClass_14" ></span></a></th>
                                </tr>

                            </thead>
                            <tbody>

                                <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Laadt gegevens...</td></tr>
                                <div  ng-show="data.users.length  > 0">
                                    <div>
                                        <tr ng-repeat="x in data.users" total-items="data.total_count">
                                            <td>[[ x.count ]]</td>
                                            <td ><a onclick="showVar(this.id, '14days')"  id="14days-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong></td>
                                            <td>[[ x.sales ]]</td>
                                            <td>[[ x.returns ]]</td>
                                            <td>[[ x.revenue ]]</td>
                                            <td>[[ x.net_revenue ]]</td>
                                        </tr>

                                    </div>
                                </div>
                            </tbody>
                            </table>


                            </div>
                            <div   class="col-md-6">
                                <div   class="sticky">
                                    <div class="chart-container">
                                        <div id="productBy_14days" class="custom_graphwidth">
                                              <span class="customspan_graph">Kies product om grafiek te bekijken.</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                    <div  class="col-md-12">

                                        <div class="btn-group-vertical custom_buttonwidth">
                                            <button id="pname_product_14days" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                            <button id="sales_product_14days" type="button"  class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                            <button id="refunds_product_14days" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade active in custom_tabwidth" ng-controller="listdata_7day as data" id="tab_product_7days">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                        <option>50</option>
                                        <option>100</option>
                                        <option>250</option>
                                        <option>500</option>
                                        <option>1000</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                </div>
                                </div>
                            <hr>

                            <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                            <thead>
                            <tr role="row" class="heading">
                                <th width="1%">Nr.</th>
                                <th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret_7"  ng-class="myClass_7" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret_7"  ng-class="myClass_7" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret_7"  ng-class="myClass_7" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret_7"  ng-class="myClass_7" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">Netto Omzet&nbsp;<span name="cs" id="caret_7"  ng-class="myClass_7" ></span></a></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Laadt gegevens...</td></tr>
                                <div  ng-show="data.users.length  > 0">
                                    <div>
                                        <tr ng-repeat="x in data.users" total-items="data.total_count">
                                            <td>[[ x.count ]]</td>
                                            <td ><a onclick="showVar(this.id, '7day')"  id="7day-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong></td>
                                            <td>[[ x.sales ]]</td>
                                            <td>[[ x.returns ]]</td>
                                            <td>[[ x.revenue ]]</td>
                                            <td>[[ x.net_revenue ]]</td>
                                        </tr>
                                    </div>
                                </div>
                            </tbody>
                        </table>


                        </div>
                        <div  class="col-md-6">
                            <div  class="sticky">
                                <div class="chart-container">
                                    <div id="productBy_7days" class="custom_graphwidth">
                                        <span class="customspan_graph">Kies product om grafiek te bekijken.</span></div>
                                </div>
                                <hr>
                                <div class="row">
                                <div  class="col-md-12">

                                    <div class="btn-group-vertical custom_buttonwidth">
                                        <button id="pname_product_7day" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                        <button id="sales_product_7day" type="button"  class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                        <button id="refunds_product_7day" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>

                        </div>
                        <div class="tab-pane fade custom_tabwidth"  ng-controller="listdata_yesterday as data" id="tab_product_yesterday">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                        <option>50</option>
                                        <option>100</option>
                                        <option>250</option>
                                        <option>500</option>
                                        <option>1000</option>
                                    </select>
                                </div>
                            <div class="col-md-6">
                                    <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                            </div>
                            </div>
                        <hr>
                        <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                        <thead>
                            <tr role="row" class="heading">
                                <th width="1%">Nr.</th>
                                <th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret_ys"  ng-class="myClass_ys" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret_ys"  ng-class="myClass_ys" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret_ys"  ng-class="myClass_ys" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret_ys"  ng-class="myClass_ys" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">Netto Omzet&nbsp;<span name="cs" id="caret_ys"  ng-class="myClass_ys" ></span></a></th>
                            </tr>
                        </thead>
                        <tbody>

                        <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Laadt gegevens</td></tr>
                            <div  ng-show="data.users.length  > 0">
                                <div>
                                    <tr ng-repeat="x in data.users" total-items="data.total_count">
                                        <td>[[ x.count ]]</td>
                                        <td ><a onclick="showVar(this.id, 'yesterday')"  id="yesterday-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong></td>
                                        <td>[[ x.sales ]]</td>
                                        <td>[[ x.returns ]]</td>
                                        <td>[[ x.revenue ]]</td>
                                        <td>[[ x.net_revenue ]]</td>
                                    </tr>
                                </div>
                            </div>
                        </tbody>
                        </table>
                        </div>
                            <div  class="col-md-6">
                                <div  class="sticky">
                                    <div class="chart-container">
                                        <div id="productBy_yesterday" class="custom_graphwidth">
                                            <span class="customspan_graph">Kies product om grafiek te bekijken.</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                    <div  class="col-md-12">

                                        <div class="btn-group-vertical custom_buttonwidth">
                                            <button id="pname_product_yesterday" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                            <button id="sales_product_yesterday" type="button"  class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                            <button id="refunds_product_yesterday" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade custom_tabwidth" ng-controller="listdata_today as data" id="tab_product_today">
                            <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                    <option>50</option>
                                    <option>100</option>
                                    <option>250</option>
                                    <option>500</option>
                                    <option>1000</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                            <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                            </div>
                        </div>
                        <hr>


                        <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                        <thead>
                            <tr role="row" class="heading">
                                <th width="1%">Nr.</th>
                                <th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret_td"  ng-class="myClass_td" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret_td"  ng-class="myClass_td" ></span></a></th>
                                <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret_td"  ng-class="myClass_td" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret_td"  ng-class="myClass_td" ></span></a></th>
                                <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">Netto Omzet&nbsp;<span name="cs" id="caret_td"  ng-class="myClass_td" ></span></a></th>
                            </tr>
                        </thead>
                            <tbody>

                                <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Laadt gegevens...</td></tr>
                                <div  ng-show="data.users.length  > 0">
                                    <div>
                                        <tr ng-repeat="x in data.users" total-items="data.total_count">

                                        <td>[[ x.count ]]</td>
                                        <td ><a onclick="showVar(this.id, 'today')"  id="today-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong></td>
                                        <td>[[ x.sales ]]</td>
                                        <td>[[ x.returns ]]</td>
                                        <td>[[ x.revenue ]]</td>
                                        <td>[[ x.net_revenue ]]</td>
                                        </tr>

                                    </div>
                                </div>
                            </tbody>
                        </table>
                        </div>

                            <div class="col-md-6 ">
                                <div class="sticky" >
                                    <div class="chart-container">
                                        <div id="productBy_today" class="custom_graphwidth">
                                            <span class="customspan_graph">Kies product om grafiek te bekijken.</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                    <div  class="col-md-12">

                                        <div class="btn-group-vertical custom_buttonwidth">
                                            <button id="pname_product_today" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                            <button id="sales_product_today" type="button"  class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                            <button id="refunds_product_today" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade custom_tabwidth" ng-controller="listdata_cus as data" id="tab_product_custom">
                            <div class="row">
                                <div  class="col-md-4">
                                    <div class="input-group input-large date-picker input-daterange" data-date="" data-date-format="mm/dd/yyyy">
                                    <input type="text" ng-model="from_date" class="form-control" id="from" name="from">
                                    <span class="input-group-addon"> to </span>
                                    <input type="text" ng-model="to_date"  class="form-control" id="to"  name="to"> </div>
                                </div>
                                <div  class="col-md-8">
                                    <button id="go_custom_product" class="btn btn-default">Toepassen</button>
                                </div>
                            </div>

                        <hr>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <select class="form-control"  ng-model="limitText" ng-change="limit()"  id="pagination">
                                            <option>50</option>
                                            <option>100</option>
                                            <option>250</option>
                                            <option>500</option>
                                            <option>1000</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text"  class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Zoeken">
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="1%">Nr.</th>
                                        <th width="20%"><a href="#" ng-model="parent_name"  ng-click="sort('parent_name')">Parent Product&nbsp;<span name="cs" id="caret_cus"  ng-class="myClass_ys" ></span></a></th>
                                        <th width="10%"><a href="#" ng-model="sales"  ng-click="sort('sales')">Verkopen&nbsp;<span name="cs" id="caret_cus"  ng-class="myClass_ys" ></span></a></th>
                                        <th width="10%"><a href="#" ng-model="returns"  ng-click="sort('returns')">Retouren&nbsp;<span name="cs" id="caret_cus"  ng-class="myClass_ys" ></span></a></th>
                                        <th width="15%"><a href="#" ng-model="revenue"  ng-click="sort('revenue')">Omzet&nbsp;<span name="cs" id="caret_cus"  ng-class="myClass_ys" ></span></a></th>
                                        <th width="15%"><a href="#" ng-model="net_revenue"  ng-click="sort('net_revenue')">Netto Omzet&nbsp;<span name="cs" id="caret_cus"  ng-class="myClass_ys" ></span></a></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr ng-show="data.users.length == 0"><td colspan="7" style="text-align:center;">Selecteer datumbereik om producten te bekijken.</td></tr>
                                    <div  ng-show="data.users.length  > 0">
                                        <div>
                                            <tr ng-repeat="x in data.users" total-items="data.total_count">
                                                <td>[[ x.count ]]</td>
                                                <td ><a onclick="showVar(this.id, 'custom')"  id="custom-[[ x.parent ]]">[[ x.parent_name ]]</a><br> <strong>[[ x.parent_sku ]]</strong></td>
                                                <td>[[ x.sales ]]</td>
                                                <td>[[ x.returns ]]</td>
                                                <td>[[ x.revenue ]]</td>
                                                <td>[[ x.net_revenue ]]</td>
                                            </tr>
                                        </div>
                                    </div>
                                    </tbody>
                                </table>
                            </div>
                            <div   class="col-md-6">
                                <div class="sticky" >
                                    <div class="chart-container ">
                                        <div id="productBy_custom" class="custom_graphwidth">
                                            <span class="customspan_graph">Selecteer datumbereik en product om grafiek te bekijken.</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                    <div  class="col-md-12">

                                        <div class="btn-group-vertical custom_buttonwidth">
                                            <button id="pname_product_custom" type="button"  class="btn btn-default"  data-tip="Dit"></button>

                                            <button id="sales_product_custom" type="button"  class="btn btn-default  highlight_series1 tips bheight" data-series="0" data-tip="Dit"></button>
                                            <button id="refunds_product_custom" type="button" class="btn btn-default  highlight_series1 tips bheight" data-series="1" data-tip="Dit"></button>
                                        </div>
                                    </div>
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
    {!! HTML::script('assets/js/angular_js.js') !!}
    {!! HTML::script('assets/js/reports.js') !!}
    {!! HTML::script('assets/js/report_angular.js') !!}

    <script>
        $('.date-picker').datepicker();

        $(document).ready(function() {

            var stickyNavTop = $('.sticky').offset().top;

            var stickyNav = function(){
                var scrollTop = $(window).scrollTop();

                if (scrollTop > stickyNavTop) {
                    $('.sticky').addClass('sticky_graph');
                } else {
                    $('.sticky').removeClass('sticky_graph');
                }
            };
            stickyNav();
            $(window).scroll(function() {
                stickyNav();
            });
        });
    </script>



@endsection