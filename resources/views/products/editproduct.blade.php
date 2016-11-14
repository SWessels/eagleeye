@extends('layouts.master_angular')

@section('css')
    {!! HTML::style('assets/css/products_plugins.css') !!}
@endsection

@section('content')


        <!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><a href={{ route('home') }}>Home</a></li>
        <li> <i class="fa fa-angle-double-right"></i> <span>Product</span></li>
    </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> Product
    <small>Product bewerken</small>
</h3>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                {{ Session::get('flash_message') }}
            </div>
        @endif
    </div>
    <div class="col-md-12 error_div">

    </div>
</div>
{!! Form::open(array('method' => 'patch' , 'id' => 'product-form', 'action' => array('ProductsController@update', $product->id))) !!}
    <input type="hidden" name="submitorm" value="1" id="submitorm">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <input type="text" class="form-control" name="product_name" value="{!! $product->name !!}">
                <input type="hidden" name="product_id" value="{!! $product->id !!}" id="product_id">
                <p id="perma-link-section">
                    <strong>Permalink:</strong> {{ url('/') }}/product/<span
                            id="product-slug">{!! $product->slug !!}</span>
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
                                    <option value="simple" {{ $product->product_type=="simple"?"selected":"" }} >Simpel product</option>
                                    <option value="variable" {{ $product->product_type=="variable"?"selected":"" }}>
                                        Variabel product
                                    </option>
                                    <option value="composite" {{ $product->product_type=="composite"?"selected":"" }}>
                                        Composite product
                                    </option>
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
                                    <a href="#inventory" data-toggle="tab"> <i class="fa fa-line-chart"></i>Voorraad
                                    </a>
                                </li>
                                <li id="linked_products_tab">
                                    <a href="#linked_product" data-toggle="tab"> <i class="fa fa-anchor"></i> Verwante producten</a>
                                </li>
                                <li id="components_tab">
                                    <a href="#components" data-toggle="tab"> <i class="fa fa-bars"></i> Componenten</a>
                                </li>
                                <li id="attributes_tab">
                                    <a href="#attribute" data-toggle="tab"> <i class="fa fa-bookmark"></i>Attributen
                                    </a>
                                </li>
                                <li id="variations_tab">
                                    <a href="#variations" data-toggle="tab"> <i class="fa fa-puzzle-piece"></i>
                                        Variaties</a>
                                </li>
                                <li id="switches_tab">
                                    <a href="#swtiches" data-toggle="tab"> <i class="fa fa-recycle"></i> Color
                                        Swatches </a>
                                </li>
                                <li id="product_color_tab">
                                    <a href="#product_color" data-toggle="tab"> <i class="fa fa-recycle"></i>Kleuren
                               </a>
                                </li>
                                <li id="waitlist_tab">
                                    <a href="#wait_list" data-toggle="tab"> <i class="fa fa-bars"></i>Wachtlijst</a>
                                </li>
                                <li id="tabs_tab">
                                    <a href="#product_tabs" data-toggle="tab"> <i class="fa fa-bars"></i>Tabs</a>
                                </li>

                            </ul>
                        </div>
                        <div class="col-md-9 npall">
                            <div class="tab-content">
                                <div class="tab-pane active" id="general">

                                    <div class="form-group" id="main_sku">
                                        <div class="col-md-3 control-label">SKU</div>
                                        <div class="col-md-6">
                                            <input type="text" name="product_sku" id="parent_product_sku" value="{!! $product->sku !!}" class="parent_product_sku form-control input-sm">
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group" id="discount_value">
                                        <div class="col-md-3 control-label">Korting</div>
                                        <div class="col-md-6">
                                            <input type="text" name="discount_value_fld" id="discount_value_fld" value="{{ moneyForField($product->regular_price) }}" class="discount_value_fld form-control input-sm">
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="simple">
                                        <hr>

                                        <div class="form-group">
                                            <div class="col-md-3 control-label">Reguliere prijs</div>
                                            <div class="col-md-6">
                                                <input type="text" name="regular_price" value="{!! moneyForField($product->regular_price) !!}" class="regular_price form-control input-sm">
                                            </div>
                                            <div class="col-md-3"></div>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-3 control-label">Kortingsprijs</div>
                                            <div class="col-md-6">
                                                <input type="text" name="sale_price" value="@if($product->sale_price!='' && $product->sale_price!='0.00'){!!  moneyForField($product->sale_price) !!}@endif" class="sale_price form-control input-sm">
                                            </div>
                                            <div class="col-md-3"><a href="javascript:;" class="toggle-date-range">
                                                    @if($product->sale_price !='' && $product->sale_price!='0.00') Cancel @else Schedule @endif</a></div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="sale_schedule"  @if($product->sale_price!='' && $product->sale_price!='0.00') style="display:block" @endif>
                                            <div class="form-group">
                                                <div class="col-md-3 control-label">Sale periode</div>
                                                <div class="col-md-6">
                                                    <div class="input-group input date-picker input-daterange"
                                                         data-date="{!! date('m/d/Y') !!}" data-date-format="mm/dd/yyyy">
                                                        <input type="text" class="form-control" name="sale_from" value="@if(isDateTime($product->sale_from)){{ date('m/d/Y', strtotime($product->sale_from)) }}@endif">
                                                        <span class="input-group-addon"> to </span>
                                                        <input type="text" class="form-control" name="sale_to" value="@if(isDateTime($product->sale_to)){{ date('m/d/Y', strtotime($product->sale_to)) }}@endif"></div>
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
                                                <option>Belastbaar</option>
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
                                                <option>Standaard belastingtarief</option>
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
                                            <input type="checkbox" class="custom" name="inventory_level" id="inventory_level" @if($product->inventories && $product->inventories->manage_stock == 'yes') checked="checked" @endif > <span
                                                    class="inv_manage_option_label">Voorraadbeheer inschakelen</span>
                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group stock_qty" @if($product->inventories && $product->inventories->manage_stock == 'no') style="display:none !important;" @endif>
                                        <div class="col-md-3 control-label">Voorraadaantal</div>
                                        <div class="col-md-6"><input type="text" name="stock_qty" class="stock_qty form-control input-sm" value="@if($product->inventories && $product->inventories->stock_qty){{ $product->inventories->stock_qty }}@endif"></div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group allow_back_orders" @if($product->inventories && $product->inventories->manage_stock == 'no') style="display:none !important;" @endif>
                                        <div class="col-md-3 control-label">Backorders toelaten?</div>
                                        <div class="col-md-6">
                                            <select class="form-control input-sm" name="allow_back_orders">
                                                <option value="yes" @if($product->inventories && $product->inventories->allow_back_orders == 'yes') selected="selected" @endif >Toelaten</option>
                                                <option value="yesd" @if($product->inventories && $product->inventories->allow_back_orders == 'yesd') selected="selected" @endif>Toelaten en klant informeren</option>
                                                <option value="no" @if($product->inventories && $product->inventories->allow_back_orders == 'no') selected="selected" @endif>Niet toelaten</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group back_order_limit" @if($product->inventories && $product->inventories->manage_stock == 'no') style="display:none !important;" @endif>
                                        <div class="col-md-3 control-label">Backorder limiet</div>
                                        <div class="col-md-6"><input type="text" name="back_order_limit" maxlength="3" class="back_order_limit form-control input-sm" value="@if($product->inventories && $product->inventories->back_order_limit){{ $product->inventories->back_order_limit }}@endif"></div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group stock_status">
                                        <div class="col-md-3 control-label">Voorraadstatus</div>
                                        <div class="col-md-6">
                                            <select class="form-control input-sm"  name="stock_status">
                                                <option value="in" @if($product->inventories && $product->inventories->stock_status == 'in') selected="selected" @endif>In voorraad</option>
                                                <option value="out" @if($product->inventories && $product->inventories->stock_status == 'out') selected="selected" @endif>Niet in voorraad</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="form-group days_for_delivery">
                                        <div class="col-md-3 control-label">Levertijd</div>
                                        <div class="col-md-6">
                                            <input type="number" maxlength="2" min="1" name="days_for_delivery" class="form-control input-sm" @if($product->inventories && $product->inventories->days_for_delivery != 0 && $product->inventories->days_for_delivery != '') value="{{  $product->inventories->days_for_delivery }}" @endif>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="form-group sold_individually">
                                        <div class="col-md-3 control-label">Individueel verkocht</div>
                                        <div class="col-md-9">
                                            <input type="checkbox" class="custom" name="sold_individual" value="1" @if($product->inventories && $product->inventories->sold_individually == 'yes') checked="checked" @endif>
                                            <small><em>Aanzetten zodat dit product slechts eenmaal per order verkocht kan worden</em></small>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>

                                </div>

                                <div class="tab-pane fade ptabs" id="linked_product">

                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Upsells</div>
                                        <div class="col-md-6">
                                            <select class="form-control select2" multiple name="up_sells[]" id="up_sells">

                                            </select>
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-3 control-label">Cross-sells</div>
                                        <div class="col-md-6">
                                            <select class="form-control select2" multiple name="cross_sells[]" id="cross_sells">
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
                                                     <span class="variation_count"> {{ count($components) }} </span> Componenten (<a class="com_expend_all">Uitklappen</a>/<a class="com_collapse_all">Inklappen</a> )
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            <div class="components_div">


                                            @foreach($components as $component)

                                                <div class="col-md-12 col-sm-12">
                                                    <div class="portlet box default">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <div class="row">
                                                                    <div class="col-md-9 col-sm-9">
                                                                        <span class="component_caption_{{ $component->id }}"> {{ $component->title }} </span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="tools pull-right">
                                                                <a href="javascript:;" class="expand"> </a>
                                                                <a href="javascript:;" id="rm_component_{{ $component->id }}" class="remove rm_component_{{ $component->id }} rm_component"
                                                                   data-original-title="" title=""> </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body  portlet-collapsed form-horizontal component_div" id="component_{{ $component->id }}">
                                                            <div class="row form-body">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label">Titel</label>
                                                                        <div class="col-md-7">
                                                                            <input type="text" value="{{ $component->title }} " name="component_title_{{ $component->id }}" id="component_title_{{ $component->id }}"  class="form-control input-sm component_title" placeholder="Title">
                                                                            <span class="help-block">  </span>
                                                                        </div>
                                                                        <div class="col-md-2"></div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label">Beschrijving</label>
                                                                        <div class="col-md-7">
                                                                            <textarea class="form-control" rows="7" name="component_desc_{{ $component->id }}" id="component_desc_{{ $component->id }}" >{{ $component->description }}</textarea>
                                                                            <span class="help-block">  </span>
                                                                        </div>
                                                                        <div class="col-md-2"></div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label">Component</label>
                                                                        <div class="col-md-7">
                                                                            <select class="form-control select2" id="product_component_{{ $component->id }}" name="component_product_{{ $component->id }}"  @if($component->type == 1) multiple="multiple" @endif>
                                                                                <option>Kies Product</option>
                                                                                @$components_prod = null
                                                                                @if($component->type == 1)
                                                                                   <?php $components_prod = explode('|', $component->default_id); ?>
                                                                                @else
                                                                                    <?php $components_prod[] = $component->default_id; ?>
                                                                                @endif
                                                                                @foreach($products as $c_product)
                                                                                    @if(@in_array($c_product->id, $components_prod ))
                                                                                        <option value="{{ $c_product->id }}" selected="selected"> @if($c_product->sku !='') {{ $c_product->sku }} @else #{{  $c_product->id }} @endif - {{$c_product->name }}</option>
                                                                                    @else
                                                                                        <option value="{{ $c_product->id }}"> @if($c_product->sku !='') {{ $c_product->sku }} @else #{{  $c_product->id }} @endif - {{$c_product->name }}</option>
                                                                                    @endif
                                                                                @endforeach

                                                                            </select>
                                                                            <span class="help-block">  </span>
                                                                        </div>
                                                                        <div class="col-md-2"></div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-md-3 control-label">Meerdere opties:</label>
                                                                        <div class="col-md-7">
                                                                            <input type="checkbox" class="chkprodtype" name="prod_opt_{{ $component->id }}" value="" id="prod_opt_{{ $component->id }}"  @if($component->type == 1) checked="checked" @endif/>
                                                                        </div>
                                                                        <div class="col-md-2"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="col-sm-12">
                                                <button name="save_components_btn" id="save_components_btn" class="btn btn-primary btn-sm">Componenten opslaan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                if (isset($product->Attributes)) {
                                    //dd($product->Attributes->attributes) ;
                                    $product_atts = unserialize($product->Attributes->attributes);
                                    foreach ($product_atts as $key => $value) {
                                        $p_atts[] = $key;
                                    }

                                } else {
                                    $product_atts = array();
                                    $p_atts = array();
                                }


                                ?>

                                {{--{{ dd($product_atts) }}--}}

                                <div class="tab-pane fade ptabs" id="attribute">
                                    <div class="col-md-6">
                                        <select class="form-control input-sm" id="attributes">
                                            @foreach($attributes as $value)
                                                @if(@in_array($value->id, $p_atts))
                                                    <option value="{!! $value->id !!}"
                                                            disabled="disabled">{{ $value->name }}</option>
                                                @else
                                                    <option value="{!! $value->id !!}">{{ $value->name }}</option>
                                                @endif
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
                                        <?php
                                        $custom_attributes = array();
                                        ?>

                                        {{--{{ dump($product_atts) }}--}}
                                        @if(count($product_atts) > 0 )

                                            @foreach($product_atts as $attr => $terms)
                                                <?php
                                                    $attribute_obj = new  \App\ProductAttributes;
                                                    $attributes_terms = $attribute_obj->fGetProductsAttributesTerms($attr);
                                                    $att_db =  $attribute_obj->getAttributeNameByID($attr);
                                                    if($att_db)
                                                        {
                                                            $att_name = $att_db->name;
                                                            $att_type = $att_db->type;
                                                        }else{
                                                        $att_name = '';
                                                        $att_type = '';
                                                    }

                                                    if($att_type == 'custom')
                                                    {
                                                        $custom_attributes[$attr]  = $terms;
                                                        continue;
                                                    }
                                                ?>
                                                    {{--{{ dump($attributes_terms) }}--}}
                                                <div class="col-sm-12">
                                                    <div class="portlet box default">
                                                        <div class="portlet-title">
                                                            <div class="caption">
                                                                <span> {{ $att_name  }}</span>
                                                            </div>
                                                            <div class="tools">
                                                                <a href="javascript:;" class="collapse"> </a>
                                                                <a href="javascript:;" id="rm_{{ $attr }}"
                                                                   class="remove rm_attribute"
                                                                   data-original-title="Delete Attribute"
                                                                   title="Delete Attribute"> </a>
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    Naam: <strong><br>{{ $att_name  }} </strong>
                                                                </div> {{--// col 4--}}
                                                                <div class="col-md-8">
                                                                    Waarden:<br>

                                                                    <select name="atributes_value[]" multiple
                                                                            id="attributes_{{ $attr }}"
                                                                            class="form-control attribute-select2 product_attribute ">
                                                                        @foreach($attributes_terms as $term)
                                                                            @if(@in_array(strtolower($term->slug), $terms))
                                                                                <option value="{{ $term->slug }}" selected="selected">{{  $term->name }}</option>
                                                                            @else
                                                                                <option value="{{ $term->slug }}" >{{  $term->name }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>


                                                                    <input type="hidden" class="attribute-id-hidden"
                                                                           value="{{ $attr }}">
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-default select_all_attr"
                                                                            onclick="selectAllAtt('{{ $attr }}')">Alles selecteren
                                                                    </button>
                                                                    <button type="button"
                                                                            class="btn btn-sm btn-default select_none_attr"
                                                                            onclick="selectNoneAtt({{ $attr }})">Niks selecteren
                                                                    </button>
                                                                </div> {{--// col 8--}}
                                                            </div> {{--// row--}}
                                                            <div class="clearfix"></div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        @endif


                                       @if(!empty($custom_attributes))
                                           <?php $count_c = 0; ?>
                                           @foreach($custom_attributes as $att_id => $custom_attribute)
                                               <?php
                                                   $attribute_obj = new  \App\ProductAttributes;
                                                   $att_db =  $attribute_obj->getAttributeNameByID($att_id);
                                                   $att_name = $att_db->name;


                                                   ?>
                                                    <div class="col-sm-12 custom_att ">
                                                        <div class="portlet box default custom_att_div">
                                                            <div class="portlet-title">
                                                                <div class="caption">
                                                                    <span class="custom_{{ $count_c }}"> {{ $att_name }} </span>
                                                                </div>
                                                                <div class="tools">
                                                                    <a href="javascript:;" class="collapse"> </a>
                                                                    <a href="javascript:;" id="rm_custom_{{ $count_c }}" class="remove rm_attribute_custom" data-original-title="Delete Attribute" title="Delete Attribute">  </a>
                                                                </div>
                                                            </div>
                                                            <div class="portlet-body">
                                                                <div class="row">
                                                                    <div class="col-md-4">Naam: <br> <input type="text" class="form-controle input-sm custom_att_name" name="custom_att_{{ $count_c }}" id="custom_att_{{ $count_c }}" value="{{ $att_name }}"> </div>
                                                                    <div class="col-md-8">Waarden:<br><textarea name="atributes_value[]"  id="custom_attributes_{{ $count_c }}" class="form-control custom_att_name" placeholder="Enter | separated values">{{ implode('|',$custom_attribute) }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                               <?php $count_c++; ?>
                                           @endforeach
                                       @endif


                                        <div class="col-sm-12">
                                            <button type="button" @if(count($product_atts) < 1 ) style="display:none" @endif class="btn btn-sm btn-primary save_attributes_btn">Attributen opslaan</button>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade ptabs" id="variations">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">

                                            <div class="col-md-12 col-sm-12">
                                                <h3>Variaties</h3>
                                                <button type="button" class="btn btn-sm btn-primary add_variation_btn">Variatie toevoegen
                                                </button>

                                                <div class="pull-right">
                                                    <span class="variation_count"> {{ count($variations) }} </span> Variaties (<a class="var_expend_all">Uitklappen</a>/<a class="var_collapse_all">Inklappen</a> )
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                            <hr>
                                            <input type="hidden" name="sku_update" id="sku_update" value="0">
                                            <input type="hidden" name="price_update" id="price_update" value="0">
                                            <div class="variations_div"></div>
                                            <div class="col-sm-12">
                                               {{-- <button type="button" style="display:none" class="btn btn-sm btn-primary update_variations_btn disabled">Wijzigingen opslaan</button>--}}
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
                                                @if(count($color_swatches)>0)
                                                @foreach($color_swatches as $color)
                                                    <div class="col-md-9" id="rm_swatch_{{ $color['id'] }}">{{ $color['name'] }}
                                                        <a href="javascript:;" class="pull-right" onclick="deleteSwatch({{ $color['id'] }})">Delete</a>
                                                    </div>
                                                @endforeach
                                                @else
                                                <div class="col-md-9" id="no_color_swatch">
                                                    <strong>Geen color swatches gevonden</strong>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="clearfix"></div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade ptabs" id="product_color" style="line-height:40px">
                                    @foreach($all_colors as $color)
                                        <div class="col-sm-4">
                                            @if(@in_array($color, $product_colors))
                                                <label><input type="checkbox" class="custom" checked="checked" name="product_color[]" value="{{ $color }}">{{ $color }}</label>
                                            @else
                                                <label><input type="checkbox" class="custom" name="product_color[]" value="{{ $color }}">{{ $color }}</label>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>

                                <div class="tab-pane fade ptabs" id="wait_list">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="col-md-12 col-sm-12">
                                                <h3>Wachtlijst</h3>
                                            </div>

                                            @if($product->product_type == 'variable')

                                                @foreach($variations as $variation)
                                                    <div class="col-md-12 col-sm-12">
                                                        <div class="portlet box default">
                                                            <div class="portlet-title">
                                                                <div class="caption">

                                                                    <div class="row">
                                                                        <div class="col-sm-10">
                                                                            <span class="waitlist_no"> Wachtlijst voor variatie - #{{ $variation->id }} : {{ count($variation->wait_list) }}</span>
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
                                                                        <?php $wait_list = $variation->wait_list;  ?>
                                                                        <table class="table" >
                                                                            <tbody id="wait_list_table_{{ $variation->id }}">
                                                                            @if(empty($wait_list))
                                                                                    <tr id="no_wait_{{ $variation->id }}">
                                                                                        <td colspan="4"> Er staan geen mensen op deze wachtlijst.</td>
                                                                                    </tr>
                                                                            @else

                                                                                @foreach($wait_list as $waiting)
                                                                                    @if(isset($waiting->email))
                                                                                        <tr id="rm_user_{{  $waiting->id }}" class="wait_row_{{ $variation->id }}">
                                                                                            <td>{{  $waiting->email }}</td>
                                                                                            <td><a href="javascript:;" onclick="removeFromWaitList({{ $variation->id }},  {{  $waiting->id }}  )" class="rm_user_{{  $waiting->id }}">Verwijderen</a></td>
                                                                                            <td><a href="javascript:;" onclick="sendWaitLitEmail( {{ $variation->id }},  {{  $waiting->id }}  )" class="rm_user_{{  $waiting->id }}"><i class="fa fa-envelope"></i> </a></td>
                                                                                        </tr>
                                                                                    @endif
                                                                                @endforeach
                                                                              @endif
                                                                            </tbody>
                                                                        </table>

                                                                            </div>
                                                                    <div class="col-md-12">
                                                                            <a href="javascript:;"
                                                                               onclick="showWaitlistForm({{ $variation->id }})">E-mailadres toevoegen</a>
                                                                            <br><br>
                                                                            <div class="form-group waitlist_form waitlist_form_{{ $variation->id }}">
                                                                                <div class="col-md-6 npl">
                                                                                    <input type="text" value=""
                                                                                           id="add_user_{{ $variation->id }}"
                                                                                           class="form-control input-sm">
                                                                                </div>
                                                                                <div class="col-md-3">
                                                                                    <button type="button"
                                                                                            class="btn btn-sm btn-default"
                                                                                            onclick="addToWaitList({{ $variation->id }})">
                                                                                        Toevoegen
                                                                                    </button>
                                                                                </div>
                                                                                <div class="clearfix"></div>
                                                                            </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @elseif($product->product_type == 'simple' || $product->product_type == 'composite')
                                                 {{--{{ dd($product) }}--}}
                                                    <div class="col-sm-12">
                                                        <?php $wait_list = $product->wait_list;  ?>
                                                        <table class="table" >
                                                            <tbody id="wait_list_table_{{ $product->id }}">
                                                            @if(empty($wait_list))
                                                                <tr id="no_wait_{{ $product->id }}">
                                                                    <td colspan="4"> Er staan geen mensen op deze wachtlijst.</td>
                                                                </tr>
                                                            @else

                                                                @foreach($wait_list as $waiting)
                                                                    @if(isset($waiting->email))
                                                                        <tr id="rm_user_{{  $waiting->id }}" class="wait_row_{{ $product->id }}">
                                                                            <td>{{  $waiting->email }}</td>
                                                                            <td><a href="javascript:;" onclick="removeFromWaitList({{ $product->id }},  {{  $waiting->id }})" class="rm_user_{{  $waiting->id }}">Verwijderen</a></td>
                                                                            <td><a href="javascript:;" onclick="sendWaitLitEmail( {{ $product->id }},  {{  $waiting->id }}  )" class="rm_user_{{  $waiting->id }}"><i class="fa fa-envelope"></i> </a></td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                    <div class="col-md-12">
                                                        <a href="javascript:;"
                                                           onclick="showWaitlistForm({{ $product->id }})">E-mailadres toevoegen</a>
                                                        <br><br>
                                                        <div class="form-group waitlist_form waitlist_form_{{ $product->id }}">
                                                            <div class="col-md-6 npl">
                                                                <input type="text" value=""
                                                                       id="add_user_{{ $product->id }}"
                                                                       class="form-control input-sm">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="button"
                                                                        class="btn btn-sm btn-default"
                                                                        onclick="addToWaitList({{ $product->id }})">
                                                                    Toevoegen
                                                                </button>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>

                                            @endif
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
                                                                    <?php
                                                                        if($details_tab)
                                                                        {
                                                                            $details_tab_id = $details_tab->id;
                                                                            $details_tab_name = $details_tab->name;
                                                                            $details_tab_desc = $details_tab->description;
                                                                        }else{
                                                                            $details_tab_id = '';
                                                                            $details_tab_name = '';
                                                                            $details_tab_desc = '';
                                                                        }

                                                                    ?>
                                                                    <input type="text" class="form-control details_tab_title " name="details_tab_title_{{ $details_tab_id }}" id="details_tab_title_{{ $details_tab_id }}"  value="{{ $details_tab_name }}">
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <hr>
                                                            <hr>
                                                            <div class="col-sm-12">
                                                                <textarea name="details_tab" id="details_tab">{{ $details_tab_desc }}</textarea>
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
                                                                        <span class="tab_caption_{{ $tab['id'] }}"> {{ $tab['name'] }}</span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="tools pull-right">
                                                                <a href="javascript:;" class="expand"> </a>
                                                                @if($tab['parent_id'] != 0)
                                                                    <a href="javascript:;" id="tab_{{ $tab['id'] }}" class="remove rm_tab rm_tab_{{ $tab['id'] }}" data-original-title="" title=""> </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="portlet-body  portlet-collapsed">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="col-md-12"><strong>Title</strong></div>
                                                                    <div class="col-md-12">
                                                                        @if($tab['parent_id'] == 0)
                                                                            {{ $tab['name'] }}
                                                                        @else
                                                                            <input type="text" class="form-control tab_title custom_tab" name="tab_title_{{ $tab['id'] }}" id="tab_title_{{ $tab['id'] }}"  value="{{ $tab['name'] }}">
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <hr>
                                                                <div class="col-sm-12">
                                                                    <div class="col-md-12"><strong>Description</strong></div>
                                                                    @if($tab['parent_id'] == 0)
                                                                        <div class="col-md-12">{{ substr(strip_tags($tab['description']), 0 , 100) }} ... <a href="{{ route('tabs.edit',$tab['id']) }}">Algemene tab aanpassen</a></div>
                                                                    @else
                                                                        <div class="col-md-12"><textarea name="tab_description_{{ $tab['id'] }}" id="tab_description_{{ $tab['id'] }}" class="tab_description">{{ $tab['description'] }}</textarea></div>
                                                                    @endif
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>
                                            <div class="col-sm-12">
                                                <button type="button" class="btn btn-sm btn-primary add_tab_btn">Custom Tab aanpassen</button>
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
                    <textarea class="form-control" name="product-editor" id="product-editor">{{ $product->description }}</textarea>
                </div>
            </div>

            <div class="portlet box default">
                <div class="portlet-title">
                    <div class="caption">
                        </i>SEO Afdeling
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
                            <?php if(Session::get('connection') == 'themusthaves'){
                                $con_title = 'The Musthaves';
                           } else if(Session::get('connection') == 'musthavesforreal' ){
                                $con_title = 'Musthaves For Real';
                            } ?>
                                <input name="con_title" type="hidden" id="con_title" value="{{$con_title}}">
                            <div class="form-group">
                                <div class="col-md-3 control-label">Voorbeeld zoekresultaat</div>
                                <div class="col-md-6">
                                    <span id="example_title" class="exmp_title"><span id="link_title">{{$product->name}}</span> | <?php echo $con_title; ?></span><br>
                                    <span id="example_url" class="exmp_url">{{ url('/') }}/product/<span id="link_slug">{{ $product->slug}}/</span></span><br>
                                    <span id="example_desc" class="exmp_desc" >{{limit_paragraph($product->description, 200)}}</span>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Titel</div>
                                <div class="col-md-6">
                                    <input type="text" id="seo_title" name="seo_title" value="{{$product['seo_details']['title']}}"  class=" form-control input-sm">
                                    <span id="title_count" class="count_alert"></span>
                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Beschrijving</div>
                                <div class="col-md-6">
                                        <textarea  id="seo_desc"  name="seo_desc" rows="5" class=" form-control input-sm">{{$product['seo_details']['description']}}
                                            </textarea><span id="desc_count"  class="count_alert"></span>
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
                                                <input type="radio" name="is_index" value="index" <?php if($product['seo_details']['is_index'] == '1' || $product['seo_details']['is_index'] == ''){ echo "checked";} ?> ></div>Index</label>
                                        <label class="radio-inline">
                                            <div class="radio">
                                                <input type="radio" name="is_index"  value="non-index" <?php if($product['seo_details']['is_index'] == '0'){ echo "checked";} ?> ></div>No Index</label>

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
                                                <input type="radio" name="is_follow"  value="follow" <?php if($product['seo_details']['is_follow'] == '1'  || $product['seo_details']['is_follow'] == ''){ echo "checked";} ?>></div>Follow</label>
                                        <label class="radio-inline">
                                            <div class="radio">
                                                <input type="radio" name="is_follow"  value="no-follow" <?php if($product['seo_details']['is_follow'] == '0'){ echo "checked";} ?>></div>No Follow</label>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Canonieke URL</div>
                                <div class="col-md-9">
                                    <input type="text" id="can_url" name="can_url" value="<?php if($product['seo_details']['canonical_url'] == ''){ echo url('/').'/product/'.$product->slug ; } else{ echo $product['seo_details']['canonical_url'] ;}?>"  class="form-control input-sm">

                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 control-label">Redirect URL</div>
                                <div class="col-md-9">
                                    <input type="text" id="red_url" name="red_url" value="{{$product['seo_details']['redirect']}}"  class="form-control input-sm">

                                </div>
                                <div class="col-md-3"></div>
                                <div class="clearfix"></div>
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
                    @if($product->status == 'publish' )
                        <button id="udpate" class="btn btn-danger pull-left btn-sm publish-product" >Bijwerken</button>
                    @elseif($product->status != 'publish')
                        <button id="publish" class="btn btn-danger pull-left btn-sm publish-product" >Publiceren</button>
                    @endif

                    @if($product->status != 'publish')
                        <button id="save_changes" class="btn btn-primary pull-right btn-sm save-changes">Wijzigingen opslaan</button>
                    @endif



                    <div class="clearfix"></div>
                    <hr>

                    <ul class="published">
                        <li><i class="fa fa-key"></i> Status: <strong class="showpub">{{ucfirst($product->status) }}</strong> <a class="editpublish" href="javascript:;">Wijzigen</a>
                            <div id="available-status" class="row" style="display:none">
                                <div class="col-md-12">
                                    <select name="status" id="status" class="form-control" >
                                        <option value="Gepubliceerd"    @if($product->status == 'publish' ) selected = "selected"  @endif>Gepubliceerd</option>
                                        <option value="Verwijderd"  @if($product->status == 'deleted' ) selected = "selected" @endif >Verwijderd</option>
                                        <option value="Concept" @if($product->status == 'draft' ) selected = "selected" @endif >Concept</option>

                                    </select>
                                </div>
                                <div class="clearfix"></div>

                                <div class="col-md-12">

                                    <button class="btn btn-default btn-sm addstatus">OK</button><a  class="btn btn-default btn-sm cancelpublish"  href="javascript:;">Annuleren</a>
                                </div> </div>
                        </li>
                        <li><i class="fa fa-eye"></i> Zichtbaarheid: <strong class="showvis">{{  ucfirst($product->visibility) }}</strong> <a class="editvisible" href="javascript:;">Wijzigen</a>
                            <div id="available-visible" class="row" style="display:none">
                                <div class="col-md-12">
                                    <select name="visible" id="visible" class="form-control" >
                                        <option value="visible"   @if($product->visibilty == 'visible' ) selected = "selected" @else  @endif>Openbaar</option>
                                        <option value="hidden"   @if($product->visibilty == 'hidden' ) selected = "selected" @else  @endif>Verborgen</option>

                                    </select>
                                </div><div class="clearfix"></div>
                                <div class="col-md-12"><button class="btn btn-default btn-sm addvisible">OK</button><a class="btn btn-default btn-sm cancelvisible"  href="javascript:;">Annuleren</a></div>
                            </div></li>
                        <li><i class="icon-calendar"></i> Geplaatst op: <strong class="showdate">{{ date("d F,Y H:i" ,strtotime($product->published_at)) }}</strong> <a class="editcal"
                                                                                                                                                                      href="javascript:;">Wijzigen</a>
                            <div id="available-calender" class="row" style="display:none">
                                <div class="col-md-12">
                                    <?php
                                    $dateExp = explode(' ',$product->published_at);

                                    $datePart = $dateExp[0];
                                    $datePartExp = explode('-',$datePart);
                                    $yy = $datePartExp[0];
                                    $mm = $datePartExp[1];
                                    $dd = $datePartExp[2];

                                    $timePart = $dateExp[1];
                                    $timePartExp = explode(':',$timePart);
                                    $hr = $timePartExp[0];
                                    $min = $timePartExp[1];
                                    ?>

                                    <select style="width:80px; padding:0px" name="mm" id="mm"  >
                                        @for($i = 1 ; $i<= 12; $i++)
                                            {{ $i = sprintf("%02d", $i) }}
                                            <option value="{{ $i }}" <?php if($i == $mm)  { echo "selected" ;} ?>   >{{ $i }}- {{  date("M", mktime(0, 0, 0, $i, 10)) }}</option>
                                        @endfor
                                    </select>
                                        <input type="hidden" name="publish_on" id="publish_on" value="0">
                                    <input style="width:20px; padding:0px" size="2" type="text" name="dd" id="dd" value="{{$dd}}" >,
                                    <input style="width:40px; padding:0px" size="4" type="text" name="yy" id="yy" value="{{$yy}}" >@
                                    <input style="width:20px; padding:0px"   size="2" type="text" name="hr" id="hr" value="{{$hr}}" >:
                                    <input style="width:20px; padding:0px" size="2" type="text" name="min" id="min" value="{{$min}}" >
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-12"><button class="btn btn-default btn-sm addcal">OK</button>
                                    <a class="btn btn-default btn-sm cancelcal"  href="javascript:;">Annuleren</a></div>
                            </div></li>
                    </ul>

                    <hr>
                    @if($product->status!= 'deleted')
                     <button type="button" class="btn btn-default pull-right" id="move_to_trash" >Verwijderen</button>
                        <div class="clearfix"></div>
                        <hr>
                    @endif



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
                                {{--{{ dump($product->categories->toArray()) }}--}}
                                <ul class="all-product-categories">

                                    @if (count($categories) > 0)
                                        <ul>
                                            @foreach ($categories  as $category)
                                                @include('chunks.categories', array('category' => $category))
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
                        <div class="col-md-4 npall">
                            <button class="btn btn-default btn-sm addtag">Toevoegen</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <small><em>Tags met komma scheiden</em></small>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <ul class="tags">
                                @foreach ($product->tags as $tag)

                                    <li><a href="javascript:;" class="remove-tag" data-tag-id={!! $tag->id !!}><i
                                                    class="fa fa-remove"></i></a> {{ $tag->name }} </li>
                                @endforeach
                            </ul>
                        </div>


                        <div class="col-md-12 col-sm-12">
                            <hr>
                            <a href="javascript:;" class="available-tags">Kies uit beschikbare tags</a>
                            <div class="clearfix"></div>
                            <div id="available-tags" style="display:none">
                                <?php $t = 0 ; ?>
                                @foreach($tags as $tag)
                                    <?php
                                        if($t++ == 10)
                                            break;
                                        ?>
                                    <div class="pull-left padding5"><a href="javascript:;" class="add-available-tag"
                                                                       data-tag-id="{!!$tag->id!!}">{{$tag->name}}</a></div>
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
                            @if($product['media_featured_image']  && featuredThumb($product['media_featured_image']['path']) !== false)
                                {!! HTML::image(featuredThumb($product['media_featured_image']['path']),$product['media_featured_image']['alt_text'],  array('class' => 'featured-image')) !!}
                                <input type="hidden" name="old_image_id" id="old_image_id" value="{{$product['media_featured_image']['id']}}"  >
                                <a class="remove-images-btn-product" data-img-id='{{$product['media_featured_image']['id']}}'>Remove featured image</a>
                            @else
                                    <input type="hidden" name="old_image_id" id="old_image_id" value="{{$product['media_featured_image']['id']}}"  >
                            @endif


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
                    <div class="tiles show_gallery_thumbnails">
                        @foreach($product['media'] as $selectedMedia)
                            <div style=" display: none; ">{{ $selectedMedia['path'] }}</div>
                            @if(galleryThumb($selectedMedia['path']))
                            <div class="tile image showremove imagesize"   id="remove{{$selectedMedia['id']}}">
                                <div class="tile-body customsize" >
                                    <a href="javascript:;"  class="remove-img" data-img-id="{{$selectedMedia['id']}}"><i class="fa fa-remove prd-remove"></i></a>
                                     {!! HTML::image(galleryThumb($selectedMedia['path']),$selectedMedia['alt_text'], array('class' => 'gallery-thumb')) !!} </div>

                            </div>
                            @endif
                        @endforeach
                    </div>

                </div>

            </div>




        </div>

    </div>

{!! Form::close()  !!}

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