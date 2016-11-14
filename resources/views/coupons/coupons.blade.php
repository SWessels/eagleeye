@extends('layouts.master')

@section('css')

@endsection

@section('content')

        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">Home</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <span>Coupon</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}

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
                    <!-- Begin: life time stats -->
            <div class="portlet ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-Products"></i>Coupon </div>
                    <div class="actions">


                        <a href="{{ route('coupons.create') }}" class="btn orders btn-info">
                            <i class="fa fa-plus"></i>
                            <span class="hidden-xs">Add Coupon</span>
                        </a>
                    </div>
                </div>
                <div ng-app="angularTable" ng-controller="listdata as data" class="portlet-body">
                    <div class="table-container">

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-6 npl">
                                    <a href="{{ url('coupons') }}"> All <span class="badge badge-success">{{$coupons_counts['all']}}</span></a>  | <a href="{{ url('coupons?status=publish') }}">Published</a> <span class="badge badge-primary">{{ $coupons_counts['published'] }}</span> | <a href="{{ url('coupons?status=draft') }}">Draft</a> <span class="badge badge-primary">{{$coupons_counts['draft']}}</span>


                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-12 npl">

                                    <form  {{action('CouponsController@index')}} class="form-horizental">
                                        <div class="col-sm-3 pull-right npr">

                                            <div class="form-group">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" ng-model="keywords" ng-change="filter()" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Search here..">
                                                    {{--<span class="input-group-btn">
                                                        <button class="btn red" type="submit">Search</button>
                                                    </span>--}}
                                                </div>
                                                <!-- /input-group -->
                                            </div>

                                        </div>
                                    </form>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <form role="form" name="coupon_delform" id="coupon_delform" action=" {{action('CouponsController@delete')}}" method="post">
                                <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-sm-2 npl">
                                            <select  name="remove" id="remove" class="form-control input-sm">
                                                <option value="">Actions</option>
                                                <option value="rm">In trash</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-5 npall">
                                            <button class="btn btn-sm">Apply</button>
                                        </div>

                                        <div class="col-sm-2">

                                        </div>

                                        <div class="col-sm-3">

                                        </div>


                                    </div>

                                    <div class="clearfix"></div>

                                </div>


                                <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width="5%">
                                            <input type="checkbox" class="group-checkable" id="ck">
                                        </th>

                                        <th width="15%">Code </th>
                                        <th width="15%">Type</th>
                                        <th width="15%">Amount</th>
                                        <th width="15%">Description</th>
                                        <th width="15%">Usage/Limit</th>
                                        <th width="15%">Expiry Date</th>
                                    </tr>

                                    </thead>
                                    <tbody>

                                   <tr ng-show="data.users.length <= 0"><td colspan="7" style="text-align:center;">Loading data!!</td></tr>
                                   <div  ng-show="data.users.length  > 0">

                                        <tr dir-paginate="x in data.users|itemsPerPage:data.itemsPerPage|filter:keywords" total-items="data.total_count" >
                                            <td class="check_resolve" id="[[x.id]]"></td>
                                             <td>[[ x.code ]]
                                                <div class="product_menu" id="">
                                                    <span><a href="/coupons/[[x.id]]/edit">Edit</a> </span>
                                                    <span><a href="/coupons/[[x.id]]/del" >Trash</a></span>
                                                    <span><a href="/coupons/[[x.id]]/copy">Copy</a></span>
                                                </div></td>
                                            <td>[[ x.type ]]</td>
                                            <td>[[ x.amount ]]</td>
                                            <td>[[ x.description ]]</td>
                                            <td>0/[[ x.usage_limit_user ]]</td>
                                            <td> [[x.expiry_date | date: 'dd MMMM yyyy']]</td>



                                    </tr>
                                    </div>
                       </tbody>
                                </table>
                                <div class="col-md-12" ng-show="data.users.length == 0">

                                    <td>No Coupons Found</td>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="pull-right">
                                            <dir-pagination-controls
                                                    max-size="8"
                                                    direction-links="true"
                                                    boundary-links="true"
                                                    on-page-change="data.getData(newPageNumber)" >
                                            </dir-pagination-controls>
                                         </div>
                                     </div>
                                </div>


                            </form>
                        </div>

                    </div>
                </div>
                <!-- End: life time stats -->
            </div>
    </div>



{{-- end of page content --}}
@endsection

@section('scripts')
    {!! HTML::script('assets/js/coupon_plugins.js') !!}
    {!! HTML::script('assets/js/coupon_js.js') !!}
    {!! HTML::script('assets/js/angular_js.js') !!}


    <script>
    var app = angular.module('angularTable', ['angularUtils.directives.dirPagination'], function($interpolateProvider) {
       $interpolateProvider.startSymbol('[[');
       $interpolateProvider.endSymbol(']]');

    });

   app.controller('listdata',function($http,$timeout){
       var vm = this;
       vm.users = []; //declare an empty array
       vm.pageno = 1; // initialize page no to 1
       vm.total_count = 0;
       vm.itemsPerPage = 60; //this could be a dynamic value from a drop down

       vm.getData = function(pageno){ // This would fetch the data on page change.
        vm.users = [];
           $http.get("/coupons/get_all_coupons/"+vm.itemsPerPage+"/"+pageno).success(function(response){
               console.log(response);
               vm.users = response.data;  //ajax request to fetch data into vm.data
               vm.total_count = response.total_count;
           }).complete;
       };
       vm.getData(vm.pageno); // Call the function to fetch initial data on page load.

    setTimeout(function(){
         $( ".check_resolve" ).each(function() {
             var id = this.id;
             var a =  $("#"+id).html();
             $("#"+id).html('<input  name="del[]" id="'+id+'" value="'+id+'" type="checkbox" class="product-checkbox">');

         }); $("input:checkbox").uniform();
       },5000)

   });

   $(document).ready(function () {
       $( ".check_resolve" ).each(function() {
           var id = this.id;
           var a =  $("#"+id).html();
           $("#"+id).html('<input  name="del[]" id="'+id+'" value="'+id+'" type="checkbox" class="product-checkbox">');

       });
       $("input:checkbox").uniform();

       $("#ck").click(function(){
           $(".product-checkbox").prop('checked', $(this).prop("checked"), true);
           $.uniform.update();
       });
   });


    </script>
@endsection
