@extends('layouts.master')
@section('css')
    {!! HTML::style('assets/css/products_plugins.css') !!}
@endsection

@section('content')

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="{{url('/')}}">Home</a>
            <i class="fa fa-angle-double-right"></i>
        </li>
        <li>
            <span>Producten</span>
        </li>
    </ul>

</div>
<br>

{{-- page content --}}

<div class="row">
    <div class="col-md-12">

        @if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>
        @endif

            @if(Session::has('flash_warning'))
                <div class="alert alert-warning">
                    {{ Session::get('flash_warning') }}
                </div>
        @endif

        <!-- Begin: life time stats -->
        <div class="portlet ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-Products"></i>Producten</div>
                <div class="actions">
                    <a href="{{ route('products.create') }}" class="btn orders btn-info">
                        <i class="fa fa-plus"></i>
                        <span class="hidden-xs"> Nieuw Product</span>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-container">
                    <div class="row">
                        <div class="col-sm-5 npl">
                            <div class="col-sm-12 product_counts">
                                <a href="{{ url('products') }}"> All <span class="badge badge-success">{{ $counts['all']  }}</span></a>  | <a href="{{ url('products?status=publish') }}">Gepubliceerd</a> <span class="badge badge-primary">{{ $counts['published'] }}</span> | <a href="{{ url('products?status=draft') }}">Concept</a> <span class="badge badge-primary">{{ $counts['draft'] }}</span> | <a href="{{ url('products?status=deleted') }}">In de prullenbak<span class="badge badge-primary"> {{ $counts['deleted'] }}</span> </a>
                            </div>

                        </div>
                        <form class="form-horizental pull-right col-sm-7">
                            <div class="col-sm-12 npall">
                                <div class="col-sm-12 npall">
                                    <div class="form-group pull-right col-sm-6 npall">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" value="{!! isset($_GET['keywords'])?$_GET['keywords']:"" !!}" name="keywords" placeholder="Search">
                                                        <span class="input-group-btn">
                                                            <button class="btn red" type="submit">Zoeken</button>
                                                        </span>
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                </div>
                                <div class="col-sm-12 npall">
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-sm-3">
                                                    <select class="form-control input-sm" name="all-dates">
                                                        <option value="">Alle datums</option>
                                                        @if(count($data['created_date']) > 0)
                                                            @foreach ($data['created_date'] as $dates)
                                                                <option value="{!! $dates !!}"   @if(isset($_GET['all-dates']) && $_GET['all-dates']==$dates ) { selected="selected" } @endif >
                                                                    {{ $dates }}</option>
                                                            @endforeach
                                                        @endif

                                                    </select>
                                                </div>

                                                <div class="col-sm-3 npall">
                                                    <select class="form-control input-sm" name="category">
                                                        <option value="">Alle categorieën</option>
                                                        @if (count($data['categories']) > 0)

                                                            @foreach ($data['categories'] as $category)
                                                                @include('chunks.categoriesoption', $category)
                                                            @endforeach


                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 npr">
                                                    <select class="form-control input-sm" name="product-type">
                                                        <option value="">Product Type</option>
                                                        <option value="composite" {!! (isset($_GET['product-type']) && $_GET['product-type']== "composite" )?"selected":"" !!}>Composite Producten</option>
                                                        <option value="simple" {!! (isset($_GET['product-type']) && $_GET['product-type']== "simple" )?"selected":"" !!}>Simpele Producten</option>
                                                        <option value="variable" {!! (isset($_GET['product-type']) && $_GET['product-type']== "variable" )?"selected":"" !!}>Variabele Producten</option>
                                                    </select>
                                                </div>

                                                <div class="col-sm-3 npr">
                                                    <button class="btn btn-sm  pull-right">Filter</button>
                                                </div>

                                            </div>
                                            {!!  $fields  !!}
                                            <div class="clearfix"></div>
                                        </div>
                                </div>
                            </div>
                        </form>
                     </div>
                    <div class="col-sm-12 npall">
                        <form method="post" action="{{ action('ProductsController@bulkDelete') }}">
                            {{ csrf_field() }}
                            <div class="bulk-delete-div">
                                <div class="col-sm-4 npl">
                                    <select class="form-control input-sm" name="bulk-action">
                                        <option value="">Acties</option>
                                        @if(!isset($_GET['status']) || $_GET['status']!='deleted')
                                            <option value="1">In de prullenbak</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-6 npall">
                                    <button class="btn btn-sm" type="submit" name="move-to-trash" value="move-to-trash">Toepassen</button>
                                </div>
                            </div>
                    <table class="table table-striped table-bordered table-hover table-checkable top10" id="datatable_products">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="5%">
                                <input type="checkbox" class="group-checkable" id="check-all-products">
                            </th>
                            <th width="10%"></th>
                            <th width="20%"> Naam {!! productsSort('name')  !!} </th>
                            <th width="14%"> Voorraad {!! productsSort('stock_status')  !!}</th>
                            <th width="10%"> Prijs  {!! productsSort('price')  !!}</th>
                            <th width="11%"> SKU  {!! productsSort('sku')  !!}</th>
                            <th width="20%"> Categorieën </th>
                            <th width="10%">Wachtlijst</th>
                        </tr>

                        </thead>
                        <tbody>
                        
                        @if(count($data['products']) > 0)

                        @foreach($data['products'] as $product)

                        <tr id="product_row_{{ $product->id }}" class="product_row">
                            <td>
                                <input type="checkbox" class="product-checkbox" name="bulk_products[]" id="bulk_{{ $product->id }}" value="{{ $product->id }}">
                            </td>
                            <td class="{{ Session::get('connection') }}">
                                @if($product->media_featured_image)
                                    {!! HTML::image(listingThumb($product->media_featured_image->path), $product->name, array('class' => 'listing-thumb') ) !!}
                                @else
                                    {!! HTML::image(App::make('url')->to('assets/img/placeholder-110x117.jpg'), $product->name, array('class' => 'listing-thumb') ) !!}
                                @endif

                            </td>
                            <td>
                                <a href="{{ route('products.edit',$product->id) }}" class="product" id="product_name_{{$product->id}}">{!! $product->name !!}</a>
                                <div class="product_menu" id="product_menu_{{ $product->id }}">
                                    <span>ID:{{ $product->id }}</span>
                                    <span><a href="{{ route('products.edit',$product->id) }}">Bewerken</a> </span>
                                    <span><a class="quick_edit" id="quick_edit_btn_{{ $product->id }}">Snel bewerken</a> </span>
                                    <span><a href="{{ route('products.destroy',$product->id) }}"   data-method="delete" data-token="{{csrf_token()}}" data-confirm="Are you sure?">Verwijderen</a></span>
                                    <span>
                                        <div class="btn-group">
                                       <a href="{{ route('products.edit',$product->id) }}" data-hover="dropdown" data-delay="100" data-close-others="true">Kopiëren</a>
                                         <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="javascript:;"><i class="fa fa-file"></i>Kopieer naar themusthaves.nl</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"><i class="fa fa-file"></i>Kopieer naar themusthaves.de</a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"><i class="fa fa-file"></i>Kopieer naar musthaveforreal.com</a>
                                            </li>
                                        </ul>

                            </div>
                                    </span>
                                </div>
                            
                            </td>
                            <td>
                            @if($product->product_type == 'variable')
                                    {!! $product->variations_data  !!}
                                    <strong>Totale Voorraad:</strong>
                                    {{ $product->total_stock }}
                                @endif
                                    <div class="clearfix"></div>
                                    {!!  $product->stock_status_label !!}
                                    @if($product->product_type == 'simple')
                                        {!!  $product->total_stock !!}
                                    @endif
                                    <br>
                                <div class="hide">{{ $product->stock_status  }}</div>
                            </td>

                            @if($product->product_type == 'variable')
                                @if($product->regular_min_price == $product->regular_max_price)
                                    <td> 
                                        <span id="regular_price_{{$product->id}}" class="regular_price @if(validPrice($product->sale_min_price)) on_sale @endif">{!! money($product->regular_min_price) !!}   </span> @if(validPrice($product->sale_min_price) && validPrice($product->sale_max_price)) <span id="sale_price_{{$product->id}}" class="sale_price">{{ money($product->sale_min_price) }}  </span> @endif </td>
                                @else
                                    <td> <span id="regular_price_{{$product->id}}" class="regular_price @if(validPrice($product->sale_min_price)) on_sale @endif">{!! money($product->regular_min_price) !!} - {!! money($product->regular_max_price) !!} </span> @if(validPrice($product->sale_min_price) && validPrice($product->sale_max_price)) <span id="sale_price_{{$product->id}}" class="sale_price">{{ money($product->sale_min_price) }} - {{ money($product->sale_max_price) }}</span> @endif </td>
                                @endif
                            @else
                            <td> <span id="regular_price_{{$product->id}}" class="regular_price @if(validPrice($product->sale_price)) on_sale @endif">{!! money($product->regular_price) !!} </span> @if(validPrice($product->sale_price)) <span class="sale_price" id="sale_price_{{$product->id}}">{{ money($product->sale_price) }}</span> @endif </td>
                            @endif
                            <td>
                                @if($product->product_type == 'variable')
                                    <?php $sku_arr = [] ; ?>
                                    @foreach($product->children as $product_variation)
                                        <?php  $sku_arr[] = $product_variation->sku ; ?>
                                    @endforeach
                                    <?php sort($sku_arr) ?>
                                    @foreach($sku_arr as $product_variation)
                                        <div class="row"><div class="col-sm-12"> {{ $product_variation }} </div></div>
                                        @endforeach
                                @else
                                    {!! $product->sku !!}
                                @endif
                            </td>

                            <td id="qe_categories_{{$product->id}}">
                                <?php  $i = 1 ;  ?>
                                @foreach($product->categories as $category)
                                        <a href="#">{!! $category->name !!}</a>@if($i++ != count($product->categories)),@endif
                                @endforeach

                            </td>
                            <td class="wait_list_td_{{$product->id}}">
                               {!!  $product->wait_list_str  !!}
                                @if($product->product_type == 'composite' &&  $product->wait_list_str > 0)
                                    <button type="button" onclick="processCompositeWaitList({{ $product->id }})" class="btn btn-xs btn-primary pull-right"><i class="fa fa-envelope"></i> </button>
                                @endif

                            </td> 
                        </tr>

                        <tr id="product_quick_edit_form_{{ $product->id }}" class="product_quick_edit_form">
                            <td colspan="8">

                            <div id="quick_edit_html_div_{{ $product->id }}">

                             </div>

                                <table class="table quick_edit_table">
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-default quick_edit_cancle">Annuleren</button>
                                        </td>
                                        <td>
                                            <span class="alert quick-alert" id="quick_msg_{{$product->id}}"></span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary quick_edit_update pull-right" id="quick_edit_update_{{$product->id}}">Bijwerken</button>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="8"><h4>Geen producten gevonden</h4></td>
                        </tr>
                        @endif
                        </tbody>
                    </table>

                        </form>
                    </div>

                    @if(count($data['products']) > 0)
                    <div class="row">
                        <div class="col-md-12">
                            {!! $data['products']->appends(Input::except('page'))->render() !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- End: life time stats -->
    </div>
</div> 

{{-- end of page content --}}
@endsection

@section('scripts')

    {!! HTML::script('assets/js/products_plugins.js') !!}
    <script>
        $(".select2").select2({width:'100%'});
    Number.prototype.formatMoney = function(c, d, t){
        var n = this, 
            c = isNaN(c = Math.abs(c)) ? 2 : c, 
            d = d == undefined ? "." : d, 
            t = t == undefined ? "," : t, 
            s = n < 0 ? "-" : "", 
            i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
            j = (j = i.length) > 3 ? j % 3 : 0;
           return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
     };

        $("#check-all-products").click(function(){
            var check_uncheck =  $(this).prop("checked");
            $(".product-checkbox").each(function(){ $(this).prop('checked', check_uncheck, true);  });
            $.uniform.update();
        });

        $('.quick_edit').on('click', function(){
            var id =$(this).attr('id'); 
            var pid = id.replace( /^\D+/g, '');

            $.ajax({
                url: "/load_quick_edit/"+pid,
                method: 'get',
            }).success(function(response) {
                    $('#quick_edit_html_div_'+pid).html(response);


                //after html loaded
                $('.product_quick_edit_form').hide();
                $('.product_row').show();

                $('#product_quick_edit_form_'+pid).show();
                $('#product_row_'+pid).hide();

                $.ajax({
                    url: "/ajax/build_all_products_dropdown",
                    method: 'POST',
                    data: {type:'linked_products', product_id:pid},
                    dataType: 'json'
                }).success(function(response) {
                    $('#qe_product_upsells_'+pid).html(response['up_sells']);
                    $('#qe_product_crosssells_'+pid).html(response['cross_sells']);

                    $(".select2").select2({ width: '100%' });
                });

                $.ajax({
                    url: "/ajax/products_dropdown_from_json",
                    method: 'GET',
                    dataType: 'json',
                }).success(function (response) {
                    var str = '<option value="" selected="selected">Select Product</option>'
                    $.each(response, function (i, v) {
                        str += '<option value="' + v.id + '">'+ v.name + '</option>'
                    });

                    $('#color_swatches_products').html(str);
                });
            });



        });

        
        $('.quick_edit_cancle').on('click', function(){ 
            $('.product_quick_edit_form').hide();
            $('.product_row').show(); 
        }); 


        $('.quick_edit_update').on('click', function(){
            var id =$(this).attr('id');
            var pid = id.replace( /^\D+/g, '');

            var errors = false;
            // validation 
            if($('#qe_product_name_'+pid).val() == '')
            {
               $('#qe_er_product_name_'+pid).html('Product Name is required.');
               $('#qe_form_group_name').addClass('has-error');
                errors = true;
            }else{
                    $('#qe_er_product_name_'+pid).html('');
                    $('#qe_form_group_name').removeClass('has-error');

            }


            if($('#qe_product_slug_'+pid).val() == '')
            {
               $('#qe_er_product_slug_'+pid).html('Product Slug is required.');
               $('#qe_form_group_slug').addClass('has-error');
                errors = true;
            }else{
                    $('#qe_er_product_slug_'+pid).html('');
                    $('#qe_form_group_slug').removeClass('has-error');

            }


            if($('#mm_'+pid).val() == '' || $('#dd_'+pid).val() == '' || $('#yy_'+pid).val() == '')
            {
               $('#qe_er_product_date_'+pid).html('Date is incorrect.');
               $('#qe_form_group_date').addClass('has-error');
                errors = true;
            }else{
                    $('#qe_er_product_date_'+pid).html('');
                    $('#qe_form_group_date').removeClass('has-error');

            }

            var checksku = checkSkuFunc(pid);

            if(checksku == true)
            {
                errors = true;
            }



            if($('#qe_regular_price_'+pid).val() == '')
            {
               $('#qe_er_regular_price_'+pid).html('Regular Price is required.');      
               $('#qe_form_group_regular_price').addClass('has-error'); 
                errors = true; 
            }else if(validatePrice($('#qe_regular_price_'+pid).val()) == false){
                    $('#qe_er_regular_price_'+pid).html('incorrect value.');      
                   $('#qe_form_group_regular_price').addClass('has-error'); 
                    errors = true; 
            }else{
                    $('#qe_er_regular_price_'+pid).html('');      
                    $('#qe_form_group_regular_price').removeClass('has-error'); 
                     
            }

            var a = $('#qe_regular_price_'+pid).val();
                a = a.replace('.', '');
                a = a.replace(',', '.');

            var b = $('#qe_sale_price_'+pid).val();
                b = b.replace('.', '');
                b = b.replace(',', '.');

            if($('#qe_sale_price_'+pid).val() != '' && validatePrice($('#qe_sale_price_'+pid).val()) == false){
                    $('#qe_er_sale_price_'+pid).html('incorrect value.');      
                   $('#qe_form_group_sale_price').addClass('has-error'); 
                    errors = true; 
            }else if($('#qe_sale_price_'+pid).val() != '' &&   b >= a){
                    $('#qe_er_sale_price_'+pid).html('Sale price must be less then regular price.');      
                    $('#qe_form_group_sale_price').addClass('has-error'); 
                      errors = true;
            }else{
                   $('#qe_er_sale_price_'+pid).html('');      
                    $('#qe_form_group_sale_price').removeClass('has-error');  
            }

            
            if(errors == true)
            {
                return false
            }
                


             var categories = []; 

             var cat_str = ''; 
             $('.qe_category_'+pid).each(function(){
                if($(this).is(':checked'))
                {
                    var cat_val = $(this).val().split('_'); 
                    //var cat_id = cat_val.replace( /^\D+/g, '');
                    var cat_id = cat_val[0]; 
                    var cat_name = cat_val[1];  
                    categories.push(cat_id); 
                    cat_str += '<a href="#">'+cat_name+'</a>, '; 
                }
             }) ;      
 
              

            $.ajax({
                url: "/productquickupdate",
                method: 'POST',
                data: {
                        pid:pid, 
                        pname: $('#qe_product_name_'+pid).val(),
                        pslug: $('#qe_product_slug_'+pid).val(),
                        pdate: $('#dd_'+pid).val() + '-' + $('#mm_'+pid).val() + '-' + $('#yy_'+pid).val() + ' ' + $('#hr_'+pid).val() + ':' + $('#min_'+pid).val(),
                        pprice: $('#qe_regular_price_'+pid).val(),
                        psale: $('#qe_sale_price_'+pid).val(),
                        pvisible: $('#qe_visible_'+pid).val(),
                        pstockstatus: $('#qe_stock_status_'+pid).val(), 
                        pstatus: $('#qe_product_status_'+pid).val(),
                          ptags: $('#qe_tags_'+pid).val(),
                        pcategories: categories,
                        pupsells: $('#qe_product_upsells_'+pid).val(),
                        pcrosssells: $('#qe_product_crosssells_'+pid).val()
                    },
                dataType: 'json',
            }).success(function(response){ 
                if(response.success == true)
                {
                    $('#quick_msg_'+pid).html(response.msg).show().removeClass('alert-danger').addClass('alert-success');
                    setTimeout(function(){
                        $('.product_quick_edit_form').hide();
                        $('.product_row').show(); 

                        //update current html of product
                        $('#product_name_'+pid).html($('#qe_product_name_'+pid).val());
                        var regular_p = parseFloat($('#qe_regular_price_'+pid).val());
                        var sale_p = parseFloat($('#qe_sale_price_'+pid).val());
                        $('#regular_price_'+pid).html((regular_p).formatMoney(2, ',', '.'));
                        $('#sale_price_'+pid).html((sale_p).formatMoney(2, ',', '.'));
                        $('#qe_categories_'+pid).html(cat_str); 

                    }, 2000);
                    
                }else
                    {
                            $('#quick_msg_'+pid).html(response.msg).show().removeClass('alert-success').addClass('alert-danger'); 
                    }                

                }) 
        }); 


        // make slug
        function makeSlug(pid){  
                if($('#qe_product_slug_'+pid).val() != '')
                {
                    $.post("/ajax/save_slug",{id:pid, new_slug:$('#qe_product_slug_'+pid).val() }, function(response){ 
                        $('#qe_product_slug_'+pid).val(response);            
                    }); 
                }
                
        }

        function  checkSkuFunc(id)
        {
            if($('#qe_product_sku_'+id).length > 0 ) {

                var errors = false;
                $.ajax({
                    url: "/check_sku",
                    method: 'POST',
                    async:false,
                    data: {id: id, sku: $('#qe_product_sku_' + id).val()},
                    dataType: 'json',
                }).success(function (response) {
                    if (response.action === false) {
                        $('#qe_er_product_sku_' + id).html(response.msg);
                        $('#qe_form_group_sku').addClass('has-error');
                        errors = true;
                    } else {
                        $('#qe_er_product_sku_' + id).html('');
                        $('#qe_form_group_sku').removeClass('has-error');
                    }
                });
            }else{
                var errors = false;
            }
            return errors;
        }


        function  checkSkuOnChange(id)
            {
                $.ajax({
                    url: "/check_sku",
                    method: 'POST',
                    data: {id:id, sku:$('#qe_product_sku_'+id).val() },
                    dataType: 'json',
                    async:false,
                }).success(function(response){
                    console.log(response);
                    if(response.action === false)
                    {
                        $('#qe_er_product_sku_'+id).html(response.msg);
                        $('#qe_form_group_sku').addClass('has-error');
                    }else{
                        $('#qe_er_product_sku_'+id).html('');
                        $('#qe_form_group_sku').removeClass('has-error');
                    }

                });
            }


        function validatePrice(price) {
          var re = /^\$?[\d,]+(\.\d*)?$/;
          return re.test(price);
        }


        function deleteSwatch(id, pid)
        {


            if(!id || !pid)
                return false;

            $.ajax({
                url: '/ajax/remove_color_swatch',
                data: {product_id:pid,swatch_id:id},
                type: 'POST',
                dataType: 'json',
                success: function(response){

                    if(response['action'] == 'true')
                    {

                        $('#color_swatches_products').append($("<option></option>")
                                .attr("value",id)
                                .text($('.swatch_txt_'+id).text()));

                        $('#color_swatches_products').trigger('change.select2');
                        $('#rm_swatch_'+id).remove();

                    }else if(response['action'] == 'false' && response['msg']){
                        alert(response['msg']);
                    }else{
                        alert('Error occured');
                    }

                }

            });


        }



        function addSwatch(pid) {

            var swatch = $('#color_swatches_products').val();
            if(swatch=='')
                return false;

            $.ajax({
                url: '/ajax/add_color_swatch',
                data: {product_id:pid,swatch:swatch},
                type: 'POST',
                dataType: 'json',
                success: function(response){

                    if(response['action'] == 'true')
                    {
                        var swatch_id = response['swatch_id'];

                        $('#no_color_swatch').remove();
                        var txt = $("#color_swatches_products option:selected").text();
                        var val = $("#color_swatches_products option:selected").val();
                        if(val=='')
                        {
                            return false;
                        }
                        $("#color_swatches_products option:selected").remove();

                        var str = '<div class="col-md-12" id="rm_swatch_'+val+'"><span class="swatch_txt_'+val+'">'+txt+'</span><a href="javascript:;" class="pull-right" onclick="deleteSwatch('+swatch_id+')">Delete</a></div>';
                        $('.color_swatches_div').append(str);
                        $("#color_swatches_products option:selected").val('');
                        $('#color_swatches_products').trigger('change.select2');

                    }else if(response['action'] == 'false' && response['msg']){
                        alert(response['msg']);
                    }else{
                        alert('Error occured');
                    }

                }

            });


        }


    </script>
    {!! HTML::script('assets/js/laravel-delete.js') !!}
@endsection
