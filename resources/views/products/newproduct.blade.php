@extends('layouts.master_angular')

@section('css')
    {!! HTML::style('assets/css/products_plugins.css') !!}
@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li>
        <li><span>Product</span></li>
    </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Product
    <small>Product toevoegen</small>
</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
        <form id="product-form" action="{{action('ProductsController@store')}}"  method="post">
            <input type="hidden" name="submitorm" value="1" id="submitorm">
            <input type="hidden" name="_token"  value="<?php echo csrf_token(); ?>"  />

        <div class="row">
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
        <div class="col-md-9">
            <div class="form-group">

                <input type="text" class="form-control" name="product_name" id="product_name" value="">
                <input type="hidden" name="product_id" value="" id="product_id">
                <p id="perma-link-section">
                    <strong>Permalink:</strong> {{ url('/') }}/product/<span
                            id="product-slug"></span>
                    <button class="btn btn-sm blue-soft btn-outline edit-slug"><i class="fa fa-pencil"></i></button>
                </p>
            </div>


            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <div class="pull-left col-md-12 col-sm-12">
                            <div class="pull-left padding-top">
                                <i class="fa fa-cart-plus"></i> Productgegevens
                            </div>
                            <div class="col-md-7">
                                <select class="form-control input-sm" name="product_type" id="product_type">
                                    <option value="simple" >Simpel product </option>
                                    <option value="variable" > Variabel product </option>
                                    <option value="composite"> Composite product </option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-3 npall">
                            <ul class="nav nav-tabs tabs-left">
                                <li class="active" id="general_tab">
                                    <a href="#general" data-toggle="tab"> <i class="fa fa-certificate"></i>Algemeen</a>
                                </li>
                                <li id="inventory_tab">
                                    <a href="#inventory" data-toggle="tab"> <i class="fa fa-line-chart"></i> Voorraadbeheer
                                    </a>
                                </li>
                                <li id="linked_products_tab">
                                    <a href="#linked_product" data-toggle="tab"> <i class="fa fa-anchor"></i> Verwante producten </a>
                                </li>
                                <li id="components_tab">
                                    <a href="#components" data-toggle="tab"> <i class="fa fa-bars"></i> Componenten </a>
                                </li>
                                <li id="attributes_tab">
                                    <a href="#attribute" data-toggle="tab"> <i class="fa fa-bookmark"></i> Attributen
                                    </a>
                                </li>
                                <li id="variations_tab">
                                    <a href="#variations" data-toggle="tab"> <i class="fa fa-puzzle-piece"></i>
                                        Variaties </a>
                                </li>
                                <li id="switches_tab">
                                    <a href="#swtiches" data-toggle="tab"> <i class="fa fa-recycle"></i> Color swatches </a>
                                </li>
                                <li id="product_color_tab">
                                    <a href="#product_color" data-toggle="tab"> <i class="fa fa-recycle"></i> Kleuren</a>
                                </li>
                                <li id="waitlist_tab">
                                    <a href="#wait_list" data-toggle="tab"> <i class="fa fa-bars"></i> Wachtlijst </a>
                                </li>
                                <li id="tabs_tab">
                                    <a href="#product_tabs" data-toggle="tab"> <i class="fa fa-bars"></i> Tabs </a>
                                </li>

                            </ul>
                        </div>
                        <div class="col-md-9 npall">
                            <div class="tab-content">
                                <div class="tab-pane active" id="general">

                                    <div class="form-group" id="main_sku">
                                        <div class="col-md-3 control-label">SKU</div>
                                        <div class="col-md-6">
                                            <input type="text" name="product_sku" id="parent_product_sku" value="" class="parent_product_sku form-control input-sm">
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group" id="discount_value">
                                        <div class="col-md-3 control-label">Korting</div>
                                        <div class="col-md-6">
                                            <input type="text" name="discount_value_fld" id="discount_value_fld" value="" class="discount_value_fld form-control input-sm">
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="simple">
                                        <hr>

                                        <div class="form-group">
                                            <div class="col-md-3 control-label">Reguliere prijs</div>
                                            <div class="col-md-6">
                                                <input type="text" name="regular_price" value="" class=" regular_price form-control input-sm">
                                            </div>
                                            <div class="col-md-3"></div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-3 control-label">Kortingsprijs</div>
                                            <div class="col-md-6">
                                                <input type="text" name="sale_price" value="" class="form-control input-sm">
                                            </div>
                                            <div class="col-md-3"><a href="javascript:;" class="toggle-date-range">
                                                    Inplannen </a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="sale_schedule">
                                            <div class="form-group">
                                                <div class="col-md-3 control-label">Sale periode</div>
                                                <div class="col-md-6">
                                                    <div class="input-group input date-picker input-daterange"
                                                         data-date="{!! date('m/d/Y') !!}" data-date-format="mm/dd/yyyy">
                                                        <input type="text" class="form-control" name="sale_from" value="">
                                                        <span class="input-group-addon"> to </span>
                                                        <input type="text" class="form-control" name="sale_to" value=""></div>
                                                    <!-- /input-group -->
                                                    <span class="help-block"></span>
                                                </div>
                                                <div class="col-md-3"></div>
                                                <div class="clearfix"></div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Belastingstatus</div>
                                        <div class="col-md-6">
                                            <select class="form-control input-sm">
                                                <option> Belastbaar</option>
                                                <option>Niet belastbaar</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Belastingtarief</div>
                                        <div class="col-md-6">
                                            <select class="form-control input-sm">
                                                <option>Standaard Belastingtarief</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <hr>

                                </div>
                                <div class="tab-pane fade ptabs" id="inventory">
                                    <div class="form-group stock_mng">
                                        <div class="col-md-3 control-label">Voorraadbeheer</div>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="custom" name="inventory_level" id="inventory_level" checked="checked" >
                                             <span class="inv_manage_option_label">Voorraadbeheer inschakelen</span>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group stock_qty">
                                        <div class="col-md-3 control-label">Voorraadaantal</div>
                                        <div class="col-md-6"><input type="text" name="stock_qty" class="stock_qty form-control input-sm"></div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group allow_back_orders">
                                        <div class="col-md-3 control-label">Backorders toelaten?</div>
                                        <div class="col-md-6">
                                            <select class="form-control input-sm" name="allow_back_orders">
                                                <option value="yes">Toelaten</option>
                                                <option value="yesd">Toelaten en klant informeren</option>
                                                <option value="no" selected="selected">Niet toelaten</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group back_order_limit">
                                        <div class="col-md-3 control-label">Backorder limiet</div>
                                        <div class="col-md-6"><input type="text" name="back_order_limit" maxlength="3" class="back_order_limit form-control input-sm" value=""></div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group stock_status">
                                        <div class="col-md-3 control-label">Voorraadstatus</div>
                                        <div class="col-md-6">
                                            <select class="form-control input-sm"   name="stock_status">
                                                <option value="in">In voorraad</option>
                                                <option value="out">Niet in voorraad</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group days_for_delivery">
                                        <div class="col-md-3 control-label">Levertijd</div>
                                        <div class="col-md-6">
                                            <input type="number" maxlength="2" min="1" name="days_for_delivery" class="form-control input-sm">
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="form-group sold_individually">
                                        <div class="col-md-3 control-label">Individueel verkocht</div>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="custom"  name="sold_individual" value="1">
                                            <small><em>Aanzetten zodat dit product slechts eenmaal per order verkocht kan worden</em></small>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                </div>

                                <div class="tab-pane fade ptabs" id="linked_product">

                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Up sells</div>
                                        <div class="col-md-6">
                                            <select class="form-control select2" multiple name="up_sells[]"  id="up_sells">

                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Cross-sells</div>
                                        <div class="col-md-6">
                                            <select class="form-control select2" multiple  name="cross_sells[]">
                                                @foreach($products as $single_product)


                                                    @if($single_product->sku!='')
                                                        <option value="{{ $single_product->id }}" >{{ $single_product->sku }}
                                                            - {{$single_product->name }}</option>
                                                    @else
                                                        <option value="{{ $single_product->id }}" >
                                                            # {{ $single_product->id }} {{$single_product->name }}</option>
                                                    @endif

                                                @endforeach


                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                </div>

                                <div class="tab-pane fade ptabs" id="components">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="col-md-12 col-sm-12">
                                                <h3>Componenten</h3>
                                                <button type="button" class="add_component_btn btn btn-primary btn-sm">Component toevoegen</button>
                                                <div class="pull-right">
                                                    <span class="components_count"> 0 </span> Componenten
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            <div class="components_div">

                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <button name="save_components_btn" id="save_components_btn" class="btn btn-primary btn-sm" style="display:none;">Component opslaan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade ptabs" id="attribute">
                                    <div class="col-md-6">
                                        <select class="form-control input-sm" id="attributes">
                                            @foreach($attributes as $value)
                                                    <option value="{!! $value->id !!}">{{ $value->name }}</option>
                                            @endforeach
                                            <option value="custom">Custom Attribuut</option>
                                        </select>
                                        <span class="label label-danger att_msg"></span>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-default btn-sm add-attribute">Toevoegen</button>
                                    </div>
                                    <div class="clearfix"></div>

                                    <br>

                                    <div id="attribute-add" style="line-height:30px">


                                        <div class="col-sm-12">
                                            <button type="button" style="display:none" class="btn btn-sm btn-primary save_attributes_btn">Attribuut opslaan</button>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade ptabs" id="variations">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">

                                            <div class="col-md-12 col-sm-12">
                                                <h3>Variations</h3>
                                                <button type="button" class="btn btn-sm btn-primary add_variation_btn">Variatie toevoegen
                                                </button>

                                                <div class="pull-right">
                                                    <span class="variation_count"> 0 </span> Variaties (<a class="var_expend_all">Uitklappen</a>/<a class="var_collapse_all">Inklappen</a> )
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            <div class="variations_div">

                                            </div>
                                            <div class="col-sm-12">
                                                {{--<button type="button" style="display:none" class="btn btn-sm btn-primary update_variations_btn">Wijzigingen opslaan</button>--}}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade ptabs" id="swtiches" style="line-height:30px">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">

                                            <h3 class="col-md-12 col-sm-12">Color swatches</h3>
                                            <hr>
                                            <div class="col-md-6">
                                                <select class="form-control select2" id="color_swatches_products">
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-default btn-sm" type="button"
                                                        onclick="addSwatch()">Toevoegen
                                                </button>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            {{--{{ dd($color_swatches) }}--}}
                                            <div class="color_swatches_div">

                                            </div>
                                            <div class="clearfix"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade ptabs" id="product_color" style="line-height:40px">

                                    @foreach($all_colors as $color)
                                        <div class="col-sm-4">
                                                <label><input type="checkbox" class="custom" name="product_color[]">{{ $color }}</label>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="tab-pane fade ptabs" id="wait_list">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="col-md-12 col-sm-12">
                                                <h3>Wachtlijst</h3>
                                            </div>

                                            <div class="add_waitlist_div">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade ptabs" id="product_tabs">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="col-md-12 col-sm-12">
                                                <h3>Product Tabs</h3>
                                            </div>
                                            <div class="tabs_div">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="portlet box default">
                                                        <div class="portlet-title">
                                                            <div class="caption col-sm-10">

                                                                <div class="row">
                                                                    <div class="col-sm-10">
                                                                        <span class="">Details</span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="tools pull-right">
                                                                <a href="javascript:;" class="expand"> </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body  portlet-collapsed">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="col-md-12"><strong>Titel</strong></div>
                                                                    <div class="col-md-12">
                                                                        <input type="text" class="form-control details_tab_title " name="details_tab_title_" id="details_tab_title_"  value="Details">
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <hr>
                                                                <hr>
                                                                <div class="col-sm-12">
                                                                    <textarea name="details_tab" id="details_tab"></textarea>
                                                                </div>
                                                                <hr>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                @foreach($tabs as $tab)
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="portlet box default">
                                                            <div class="portlet-title">
                                                                <div class="caption col-sm-10">

                                                                    <div class="row">
                                                                        <div class="col-sm-10">
                                                                            <span class="tab_caption_{{ $tab->id }}"> {{ $tab->name }}</span>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="tools pull-right">
                                                                    <a href="javascript:;" class="expand"> </a>
                                                                    @if($tab->parent_id != 0)
                                                                        <a href="javascript:;" id="tab_{{ $tab->id }}" class="remove rm_tab rm_tab_{{ $tab->id }}" data-original-title="" title=""> </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body  portlet-collapsed">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="col-md-12"><strong>Title</strong></div>
                                                                        <div class="col-md-12">
                                                                            @if($tab->parent_id == 0)
                                                                                {{ $tab->name }}
                                                                            @else
                                                                                <input type="text" class="form-control tab_title custom_tab" name="tab_title_{{ $tab->id }}" id="tab_title_{{ $tab->id }}"  value="{{ $tab->name }}">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <hr>
                                                                    <div class="col-sm-12">
                                                                        <div class="col-md-12"><strong>Beschrijving</strong></div>
                                                                        @if($tab->parent_id == 0)
                                                                            <div class="col-md-12">{{ substr(strip_tags($tab->description), 0 , 100) }} ... <a href="{{ route('tabs.edit',$tab->id) }}">Inhoud algemene tab wijzigen</a></div>
                                                                        @else
                                                                            <div class="col-md-12"><textarea name="tab_description_{{ $tab->id }}" id="tab_description_{{ $tab->id }}" class="tab_description">{{ $tab->description }}</textarea></div>
                                                                        @endif
                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-sm btn-primary add_tab_btn">Custom tab toevoegen</button>
                                                <button type="button" class="btn btn-sm btn-primary pull-right save_tabs">Tabs opslaan</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>Productbeschrijving
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <textarea class="form-control" name="product-editor" id="product-editor"></textarea>
                </div>
            </div>
            <div class="portlet box default"><div class="portlet box default">
                    <div class="portlet-title">
                        <div class="caption">
                            </i>SEO afdeling
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#generalSeo" data-toggle="tab">Algemeen</a>
                            </li>
                            <li>
                                <a href="#advancedSeo" data-toggle="tab">Geavanceerd</a>
                            </li>

                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane active" id="generalSeo">
                                <?php

                                if(Session::get('connection') == 'themusthaves'){
                                    $con_title = 'The Musthaves';
                                }
                                else if(Session::get('connection') == 'musthavesforreal' ){
                                    $con_title = 'Musthaves For Real';
                                }?>
                                    <input name="con_title" type="hidden" id="con_title" value="{{$con_title}}">
                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Voorbeeld zoekresultaat</div>
                                            <div class="col-md-6">
                                                <span id="example_title" class="exmp_title"><span id="link_title">Titel</span> | <?php echo $con_title; ?></span><br>
                                                <span id="example_url" class="exmp_url">{{ url('/') }}/product/<span id="link_slug"></span></span><br>
                                                <span id="example_desc" class="exmp_desc" ></span>
                                            </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Titel</div>
                                    <div class="col-md-6">
                                        <input type="text" id="seo_title" name="seo_title" value=""  class=" form-control input-sm">
                                        <span id="title_count" class="count_alert"></span>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Beschrijving</div>
                                    <div class="col-md-6">
                                        <textarea  id="seo_desc"  name="seo_desc" rows="3" class=" form-control input-sm">
                                            </textarea><span id="desc_count" class="count_alert"></span>
                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                    <hr>
                                </div>

                            </div>
                            <div class="tab-pane" id="advancedSeo"  >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Index/No Index</label>
                                    <div class="col-md-9">
                                        <div class="radio-list">
                                            <label class="radio-inline">
                                                <div class="radio">
                                                    <input type="radio" name="is_index" value="index" checked></div>Index</label>
                                            <label class="radio-inline">
                                                <div class="radio">
                                                        <input type="radio" name="is_index"  value="non-index" ></div>No Index</label>

                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Follow/No Follow</label>
                                    <div class="col-md-9">
                                        <div class="radio-list">
                                            <label class="radio-inline">
                                                <div class="radio">
                                                        <input type="radio" name="is_follow"  value="follow" checked></div>Follow</label>
                                            <label class="radio-inline">
                                                <div class="radio">
                                                        <input type="radio" name="is_follow"  value="no-follow" ></div>No Follow</label>

                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Canonieke URL</div>
                                    <div class="col-md-9">
                                        <input type="text" id="can_url" name="can_url" value="{{ url('/') }}"  class="form-control input-sm">

                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 control-label">Redirect URL</div>
                                    <div class="col-md-9">
                                        <input type="text" id="red_url" name="red_url" value=""  class="form-control input-sm">

                                    </div>
                                    <div class="col-md-3"></div>
                                    <div class="clearfix"></div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
        <div class="col-md-3">
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gear"></i>Publiceren
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <input type="hidden" name="action" id="form-action" value="">
                    <button id="publish" class="btn btn-danger pull-left btn-sm publish-product" >Publiceren</button>
                    <button id="save_draft" class="btn btn-default pull-right btn-sm publish-product" >Opslaan als concept</button>

                    <div class="clearfix"></div>
                    <hr>
                    <ul class="published">
                        <li><i class="fa fa-key"></i> Status: <strong class="showpub">Publiceren</strong> <a class="editpublish" href="javascript:;">Wijzigen</a>
                            <div id="available-status" class="row" style="display:none">
                                <div class="col-md-12">
                                    <select name="status" id="status" class="form-control" >
                                        <option value="publish">Gepubliceerd</option>
                                        <option value="deleted">Verwijderd</option>
                                        <option value="draft">Concept</option>

                                    </select>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-12">

                                    <button class="btn btn-default btn-sm addstatus">OK</button><a  class="btn btn-default btn-sm cancelpublish"  href="javascript:;">Annuleren</a>
                                </div> </div>
                        </li>
                        <li><i class="fa fa-eye"></i> Zichtbaarheid: <strong class="showvis">Openbaar</strong> <a class="editvisible" href="javascript:;">Wijzigen</a>
                            <div id="available-visible" class="row" style="display:none">
                                <div class="col-md-12">
                                    <select name="visible" id="visible" class="form-control" >
                                        <option value="visible">Openbaar</option>
                                        <option value="hidden">Verborgen</option>

                                    </select>
                                </div><div class="clearfix"></div>
                                <div class="col-md-12"><button class="btn btn-default btn-sm addvisible">OK</button><a class="btn btn-default btn-sm cancelvisible"  href="javascript:;">Annuleren</a></div>
                            </div></li>
                        <li><i class="icon-calendar"></i> Geplaatst op: <strong class="showdate">Meteen</strong> <a class="editcal" href="javascript:;">Wijzigen</a>
                            <div id="available-calender" class="row" style="display:none">
                                <div class="col-md-12">


                                    <select style="width:80px; padding:0px" name="mm" id="mm"  >
                                        @for($i = 1 ; $i<= 12; $i++)
                                            {{ $i = sprintf("%02d", $i) }}
                                            <option value="{{ $i }}" <?php if($i == date('m') ) { echo "selected" ;} ?>   >{{ $i }}- {{  date("M", mktime(0, 0, 0, $i, 10)) }}</option>
                                        @endfor
                                    </select>


                                    <input type="hidden" name="publish_on" id="publish_on" value="0">
                                    <input style="width:20px; padding:0px" size="2" type="text" name="dd" id="dd" value="{{date('d')}}" >,
                                    <input style="width:40px; padding:0px" size="4" type="text" name="yy" id="yy" value="{{date('Y')}}" >@
                                    <input style="width:20px; padding:0px"   size="2" type="text" name="hr" id="hr" value="{{date('H')}}" >:
                                    <input style="width:20px; padding:0px" size="2" type="text" name="min" id="min" value="{{date('i')}}" >




                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><button class="btn btn-default btn-sm addcal">OK</button>
                                    <a class="btn btn-default btn-sm cancelcal"  href="javascript:;">Annuleren</a></div>
                            </div></li>
                    </ul>

                    <hr>


                </div>

            </div>
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-bars"></i>Categorieën
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row category-checkbox-list">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1_1" data-toggle="tab"> Alle categorieën </a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade active in" id="tab_1_1">

                                <ul class="all-product-categories">

                                    @if (count($categories) > 0)
                                        <ul>
                                            @foreach ($categories  as $category)
                                                @include('chunks.categories', array('category' => $category, 'selected'=> []))
                                            @endforeach
                                        </ul>

                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                    <a href="javascript:;" class="btn btn-sm btn-default addnewcategory"> <i class="fa fa-plus"></i> Productcategorie toevoegen</a>
                    <div class="top10"></div>
                    <div id="new-product-section" style="display:none">
                        <input type="hidden" name="product-choose-category" id="product-choose-category">
                        <div class="col-md-12 col-sm-12">

                            <input type="text" class="form-control input-sm" id="add-new-category">
                        </div>
                        <div class="clearfix"></div>
                        <div class="top5"></div>
                        <div class="col-md-12 col-sm-12">

                            <select class="form-control input-sm" id="parent-category">
                                <option></option>
                                @if (count($categories) > 0)

                                    @foreach ($categories as $category)
                                        @include('chunks.categoriesoption', $category)
                                    @endforeach


                                @endif
                            </select>

                        </div>
                        <div class="clearfix"></div>
                        <div class="top5"></div>
                        <div class="col-md-12 col-sm-12">
                            <button class="btn btn-primary btn-sm add-new-category-btn">Productcategorie toevoegen
                            </button>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-tags"></i>Product Tags
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body" style="line-height:30px">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" class="form-control input-sm typeahead" name="tag" id="new-tag">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-default btn-sm addtag">Toevoegen</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <small><em>Tags met komma scheiden</em></small>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <ul class="tags">

                            </ul>
                        </div>


                        <div class="col-md-12 col-sm-12">
                            <hr>
                            <a href="javascript:;" class="available-tags">Kies uit beschikbare tags</a>
                            <div class="clearfix"></div>
                            <div id="available-tags" style="display:none">
                                @foreach($tags as $t)
                                    <a href="javascript:;" class="add-available-tag" data-tag-id="{!!$t->id!!}">{{$t->name}}</a>
                                @endforeach
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </div>

                </div>

            </div>
          <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>Uitgelichte afbeelding
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="top20"></div>
                    <a class="show-images-btn"   data-title="featured" data-toggle="modal" href="#full">Kies uitgelichte afbeelding</a>
                    <div class="show-image-section">
                        <div class="display-image" >


                        </div>
                    </div>
                </div>

            </div>
            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-gift"></i>Afbeeldingsgalerij
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                    </div>
                </div>
                <div class="portlet-body">


                    <a class="show-images-btn" data-title="gallery" data-toggle="modal" href="#full">Afbeeldingen toevoegen</a>

                    <div class="top20"></div>
                    <div class="tiles">

                    </div>

                </div>

            </div>

        </div>

    </div>
     </form>

<div class="modal fade" id="tag-already-available" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Tags kiezen</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Sluiten</button>
                <button type="button" class="btn green">Wijzigingen opslaan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


@extends('includes.gallery')

@endsection

@section('scripts')
    {!! HTML::script('assets/js/products_plugins.js') !!}
    {!! HTML::script('assets/plugins/ckeditor/ckeditor.js') !!}
    {!! HTML::script('assets/js/products_js.js') !!}
    {!! HTML::script('assets/js/custom.js') !!}
    {!! HTML::script('assets/js/seo.js') !!}
    {!! HTML::script('assets/js/angular_js.js') !!}
    {!! HTML::script('assets/js/media_angularjs.js') !!}
@endsection