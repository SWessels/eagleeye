@extends('layouts.master')

@section('css')
@endsection

@section('content')
        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="{!! url('/') !!}">Home</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <span>Product Feeds</span>
        </li>
    </ul>

</div>
<br>

{{-- pae content --}}

<div class="row">
    <div class="col-md-12">
        <div class="portlet-title">
            <div class="caption">
               {{-- <i class="fa fa-user"></i> Add New Users--}}
            </div>
        </div>


        <div class="row">
            @if(Session::has('flash_message'))
                <div class="col-sm-12">
                <div class="alert alert-success">
                    {{ Session::get('flash_message') }}
                </div>
                </div>
            @endif
            <div class="col-sm-12">
                <a href="{{ url('product_feeds/facebookfeed') }}" class="btn btn-primary">Facebook feed</a>
                <a href="{{ url('product_feeds/googlefeed') }}" class="btn btn-primary">Google feed</a>
                <a href="{{ url('product_feeds/zanoxfeed') }}" class="btn btn-primary">Zanox feed</a>

            </div>
            <div class="col-sm-12">



                <style>
                    .typeahead-demo .custom-popup-wrapper {
                        position: absolute;
                        top: 100%;
                        left: 0;
                        z-index: 1000;
                        display: none;
                        background-color: #f9f9f9;
                    }

                    .typeahead-demo .custom-popup-wrapper > .message {
                        padding: 10px 20px;
                        border-bottom: 1px solid #ddd;
                        color: #868686;
                    }

                    .typeahead-demo .custom-popup-wrapper > .dropdown-menu {
                        position: static;
                        float: none;
                        display: block;
                        min-width: 160px;
                        background-color: transparent;
                        border: none;
                        border-radius: 0;
                        box-shadow: none;
                    }
                </style>

                <script type="text/ng-template" id="customTemplate.html">
                    <a>
                        <img ng-src="http://upload.wikimedia.org/wikipedia/commons/thumb/@{{match.model.flag}}" width="16">
                        <span ng-bind-html="match.label | uibTypeaheadHighlight:query"></span>
                    </a>
                </script>

                <script type="text/ng-template" id="customPopupTemplate.html">
                    <div class="custom-popup-wrapper"
                         ng-style="{top: position().top+'px', left: position().left+'px'}"
                         style="display: block;"
                         ng-show="isOpen() && !moveInProgress"
                         aria-hidden="@{{!isOpen()}}">
                        <p class="message">select location from drop down.</p>

                        <ul class="dropdown-menu" role="listbox">
                            <li ng-repeat="match in matches track by $index" ng-class="{active: isActive($index) }"
                                ng-mouseenter="selectActive($index)" ng-click="selectMatch($index)" role="option" id="@{{::match.id}}">
                                <div uib-typeahead-match index="$index" match="match" query="query" template-url="templateUrl"></div>
                            </li>
                        </ul>
                    </div>
                </script>
                <br>
                <hr>
                <div ng-app="product.feeds">
                    <div class='container-fluid typeahead-demo' ng-controller="TypeaheadCtrl">
                    <div class="col-sm-12">
                        <h4>Speciaal veld aan product toevoegen</h4>
                        <form ng-submit="processForm()" class="form-inline" role="form">
                            <div class="form-group" id="name-group"   ng-class="{ 'has-error' : errorproduct_name }">
                                <input type="text" name="product_name" autocomplete="off" ng-model="formData.product_name" placeholder="Search Product" uib-typeahead="name for name in getProducts($viewValue)" typeahead-loading="loadingLocations" typeahead-no-results="noResults" class="form-control">

                            </div>
                            <div id="superhero-group" class="form-group" ng-class="{ 'has-error' : errorspecialfield }">
                                <input type="text" name="specialfield" class="form-control" ng-model="formData.specialfield" >
                            </div>
                            <input type="hidden" value="@{{ selected }}" ng-model="formData.selected" name="selected_product" id="selected_product" >
                            <input type="submit" class="btn btn-default" value="Add Product">
                            <i ng-show="loadingLocations" class="glyphicon glyphicon-refresh"></i>
                            <div ng-show="noResults">
                                <i class="glyphicon glyphicon-remove"></i> Geen resultaten gevonden
                            </div>
                            <span class="help-block" ng-show="errorformError">@{{ errorformError }}</span>
                        </form>
                    </div>
                </div>
                    <div class="col-sm-12" ng-controller="productFeedsCtrl">
                        <ul class="feed_special_fields">
                            <li>
                                <div> <span class="feed_product_name"><strong>Producttitel</strong></span> <span class="feed_field"><strong>Aangepast veld</strong></span><span class="feed_delete"><strong>Actie</strong></span></div>
                            </li>
                            <div  id="feed_special_fields">
                            <li ng-repeat="x in specialFieldsDB">
                                <div class="fee_li_@{{ x.product_id }}"> <span class="feed_product_name">@{{ x.product_name }}</span> <span class="feed_field">@{{ x.field_name }}</span><span class="feed_delete"><a href="javascript:;" ng-click="deleteField(x.product_id);">Delete</a> </span></div>
                            </li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <hr>

    </div>
</div>

{{-- end of page content --}}
@endsection

@section('scripts')
    {!! HTML::script('assets/js/angular_js.js') !!}
    {!! HTML::script('//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-1.3.3.js') !!}
    {!! HTML::script('assets/js/prodcutfeeds.js') !!}

@endsection
