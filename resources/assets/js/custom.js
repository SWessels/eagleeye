// JavaScript Document

$.ajaxSetup({
    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
    error: function(xhr){
        alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText );
        loader('show');
    },
    beforeSend: function( response ) {
        loader('show');
    },
    success: function (data) {
        if(data['response']  == 'Unauthorized')
        {
            location.reload();
        }
        if(data['action'] == 'false')
        {
            alert('Error Occurred');
        }
    },
    complete: function(response) {
        loader('hide');
        if($('.date-picker').length)
        {
            $('.date-picker').datepicker();
        }
        $( "input:checkbox").uniform();

        hidesaveButton();
        //hideUpdateButton();


    }

});

var formValid   = false;
var submitForm  = false;
var current_type    = $('#product_type').val();
var tab_editors     =  new Array();


$(document).ready(function() {
    $('.tab_description').each(function () {
        var desc_id = $(this).attr('id');
        var ed = CKEDITOR.replace( desc_id, {
            toolbar: [
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] }
            ]
        } );

        tab_editors[desc_id] = ed;
    });


});

var details_tab ;

$(function(){
    CKEDITOR.replace( 'product-editor' );

if($('#details_tab').length > 0) {
    details_tab = CKEDITOR.replace('details_tab', {
        toolbar: [
            {
                name: 'document',
                groups: ['mode', 'document', 'doctools'],
                items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']
            },
            {
                name: 'clipboard',
                groups: ['clipboard', 'undo'],
                items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
            },
            {
                name: 'editing',
                groups: ['find', 'selection', 'spellchecker'],
                items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']
            }
        ]
    });
}




    $(".quick-edit-modal-open").on('click', function(){
        var product_id = $(this).attr('data-product-id');
        alert(product_id);
        e.stopPropagation();
    });

    if($('.date-picker').length)
    {
        $('.date-picker').datepicker();
    }


    $('body').on('click','.toggle-date-range', function (e) {
        if($('.sale_schedule').is(':visible'))
        {
            $('.sale_schedule').hide();
            $('.toggle-date-range').text('Schedule');
        }else{
            $('.sale_schedule').show();
            $('.toggle-date-range').text('Cancel');
        }

        e.preventDefault();
    });

    //$(".btn-group").on("click", ".quick-edit-modal-open", function(e){ >e.preventDefault(); alert("bob");});

    $(".add-attribute").click(function(e){

        var default_att = $("#attributes option:disabled").length;
        var custom_att  = $('.custom_att_div').length;

        var atts_all    = parseInt(custom_att)+parseInt(default_att);
        console.log(atts_all + 'all');
        if( atts_all >=2 )
        {
            $('.att_msg').html('Two attributes are already added.');
            console.log(1);
            return false;
        }else{
            $('.att_msg').html('');
        }

        if($('#attributes').val() == 'custom')
        {

            var count =  $('.custom_att_div').length;
            var str = '';
                str += '<div class="col-sm-12 custom_att "><div class="portlet box default custom_att_div"><div class="portlet-title"><div class="caption"><span class="custom_'+count+'"> Custom Attribute </span></div><div class="tools"><a href="javascript:;" class="collapse"> </a><a href="javascript:;" id="rm_custom_'+count+'" class="remove rm_attribute_custom" data-original-title="Delete Attribute" title="Delete Attribute"> </a></div></div><div class="portlet-body"><div class="row"><div class="col-md-4">Name: <br> <input type="text" class="form-controle input-sm custom_att_name" name="custom_att_'+count+'" id="custom_att_'+count+'"></div><div class="col-md-8">Values:<br><textarea name="atributes_value[]"  id="custom_attributes_'+count+'" class="form-control custom_att_name" placeholder="Enter | separated values"></textarea></div></div><div class="clearfix"></div></div></div></div>';

            $('#attribute-add').prepend(str);

            hidesaveButton();
            return false;
        }


        if($("#attributes option:selected").is(':disabled'))
        {
            return false;
        }
        $("#attributes option:selected").attr('disabled', 'disabled');
        var attributes = $("#attributes option:selected").val();

        var attributes_name = $("#attributes option:selected").text();
        var attributes_id = $("#attributes option:selected").val();

        $.ajax({
            url: "/ajax/get_attribute_terms_by_id",
            method: 'POST',
            data: {id:attributes},
            dataType: 'json',
        }).success(function(response){

                var str = '<div class="col-sm-12">';

                str += '<div class="portlet box default">';
                str += '<div class="portlet-title">';
                str += '<div class="caption">';
                str += '<span> '+attributes_name+' </span>';
                str += '</div>';
                str += '<div class="tools">';
                str += '<a href="javascript:;" class="collapse"> </a>';
                str += '<a href="javascript:;" id="rm_'+attributes+'" class="remove rm_attribute" data-original-title="Delete Attribute" title="Delete Attribute"> </a>';
                str += '</div>';
                str += '</div>';
                str += '<div class="portlet-body">';
                str += '<div class="row">';
                str += '<div class="col-md-4">';
                str += 'Name: <strong><br>'+attributes_name+'</strong>';
                /*str += '<label><input type="checkbox" class="attribute-chechbox" name="'+attributes_name.toLowerCase().replace(' ','-')+'[]" checked> Show on product page</label>';
                 str += '<label class="use_as_variation"><input type="checkbox" class="attribute-chechbox" name="'+attributes_name.toLowerCase().replace(' ','-')+'[]" checked> Use as variation</label>';*/
                str += '</div>'; // col 4
                str += '<div class="col-md-8">';
                str += 'Values:<br >';

                str += '<select name="atributes_value[]"   multiple id="attributes_'+attributes_id+'" class="form-control attribute-select2 product_attribute">';
                $.each(response, function(i, v){
                    str+='<option value="'+v.slug+'">'+v.name+'</option>'
                });

                str += '</select>'

                str += '<input type="hidden" class="attribute-id-hidden" value="'+attributes+'" >';
                str += '<button type="button" class="btn btn-sm btn-default select_all_attr" onclick="selectAllAtt(\''+attributes_id+'\')">Select All</button>';
                str += '<button type="button" class="btn btn-sm btn-default select_none_attr" onclick="selectNoneAtt(\''+attributes_id+'\')">Select None</button>';
                str += '</div>'; // col 8
                str += '</div>'; // row
                str += '<div class="clearfix"></div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';

                $("#attribute-add").prepend(str);
                $(".attribute-select2").select2({ width: '200px' });
                hidesaveButton();


            })

        e.preventDefault();

    });


    // add component

    $(".add_component_btn").click(function(e){

        $.ajax({
            url: "/ajax/create_component",
            method: 'POST',
            data: {product_id:$('#product_id').val()},
            dataType: 'json'
        }).success(function(response){

            if(response['action'] !='false') {

                $('#product_id').val(response['product_id']);
                var cid = response['component_id'];

                var str = '<div class="col-md-12 col-sm-12" >';
                str += '<div class="portlet box default">';
                str += '<div class="portlet-title">';
                str += '<div class="caption">';

                str += '<div class="row">';
                str += '<div class="col-md-9 col-sm-9">';
                str += '<span class="component_caption_'+cid+'"> Title </span>';
                str += '</div>';

                str += '</div>';

                str += '</div>';
                str += '<div class="tools pull-right">';
                str += '<a href="javascript:;" class="collapse" data-original-title="" title=""> </a>';
                str += '<a href="javascript:;" id="rm_component_'+cid+'" class="remove rm_component_'+cid+' rm_component" data-original-title="" title=""> </a>';
                str += '</div>';
                str += '</div>';
                str += '<div class="portlet-body  portlet-collapsed form-horizontal component_div" id="component_'+cid+'" style="display: block;">';
                str += '<div class="row form-body">';
                str += '<div class="col-md-12">';
                str += '<div class="form-group">';
                str += '<label class="col-md-3 control-label">Title</label>';
                str += '<div class="col-md-7">';
                str += '<input type="text" value="" name="component_title_'+cid+'" id="component_title_'+cid+'" class="form-control input-sm component_title" placeholder="Title">';
                str += '<span class="help-block">  </span>';
                str += '</div>';
                str += '<div class="col-md-2"></div>';
                str += '</div>';
                str += ' </div>';

                str += '<div class="col-md-12">';
                str += '<div class="form-group">';
                str += '<label class="col-md-3 control-label">Description</label>';
                str += '<div class="col-md-7">';
                str += '<textarea class="form-control" rows="7" name="component_desc_'+cid+'" id="component_desc_'+cid+'" ></textarea>';
                str += '<span class="help-block">  </span>';
                str += '</div>';
                str += '<div class="col-md-2"></div>';
                str += '</div>';
                str += '</div>';


                str += '<div class="col-md-12">';
                str += '<div class="form-group">';
                str += '<label class="col-md-3 control-label">Component</label>';
                str += '<div class="col-md-7">';
                str += '<select class="form-control select2"  id="product_component_'+cid+'" name="component_product_'+cid+'">';
                str += '<option>Select Product</option>';

                var all_products = JSON.parse(response['all_products']);

                $.each(all_products, function (i, v) {
                    if (v.sku != '') {
                        var sym = v.sku;
                    } else {
                        var sym = '#' + v.id
                    }
                    str += '<option value="' + v.id + '">' + sym + ' - ' + v.name + '</option>'
                });


                str += '</select> ';
                str += '<span class="help-block">  </span>';
                str += '</div>';
                str += '<div class="col-md-2"></div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';
                str += '</div>';

                $(".components_div").prepend(str);
                $("#product_component_"+cid).select2({width: '335px'});

                $('.component_count').html($('.component_div').length);

                if($('.component_div').length == 0)
                {
                    $('#save_components_btn').hide();
                }else{
                    $('#save_components_btn').show();
                }

            }

        })

        e.preventDefault();

    });

    // end

    // add custom tab

    $(".add_tab_btn").click(function(e){

        if($('#product_id').val() == '')
        {
            return false;
        }
        $.ajax({
            url: "/ajax/create_custom_tab",
            method: 'POST',
            data: {product_id:$('#product_id').val()},
            dataType: 'json'
        }).success(function(response){

            if(response['action'] !='false') {
                var cid = response['tab_id'];

                var str = '<div class="col-md-12 col-sm-12" >';
                str += '<div class="portlet box default">';
                str += '<div class="portlet-title">';
                str += '<div class="caption col-sm-10">';
                str += '<div class="row">';
                str += '<div class="col-sm-10">';
                str += '<span class="tab_caption_'+cid+'">Title</span>';
                str += '</div> </div> </div>';
                str += '<div class="tools pull-right">';
                str += '<a href="javascript:;" class="expand"> </a>';
                str += '<a href="javascript:;" id="tab_'+cid+'" class="remove rm_tab rm_tab_'+cid+'" data-original-title="" title=""> </a>';
                str += '</div> </div>';
                str += '<div class="portlet-body  portlet-collapsed">';
                str += '<div class="row">';
                str += '<div class="col-md-12">';
                str += '<div class="form-group">';
                str += '<label class="col-md-3 control-label"><strong>Title</strong></label>';
                str += '<div class="col-md-9">';
                str += '<input type="text" class="form-control tab_title custom_tab" name="tab_title_'+cid+'" id="tab_title_'+cid+'" >';
                str += '</div>';
                str += '</div>';
                str += '</div><hr><hr>';
                str += '<div class="col-sm-12">';
                str += '<textarea name="tab_description_'+cid+'" id="tab_description_'+cid+'" class="tab_description_"></textarea>';
                str += '</div> <hr> </div> </div> </div> </div>';

                $(".tabs_div").append(str);

               var ed  =  CKEDITOR.replace( 'tab_description_'+cid, {
                    toolbar: [
                        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] }
                    ]
                } );

                tab_editors[cid] = ed;

            }

        })

        e.preventDefault();

    });

    // end

    //  save tabs

    $(document).on('click', '.save_tabs', function (e) {
        if($('#product_id').val() == '')
        {
            return false;
        }
        updateTabs();
        e.preventDefault();
    });



    // end

    $(document).on('change', '.tab_title', function (e) {
        var id = $(this).attr('id');
        id = getId(id);
        $('.tab_caption_'+id).html($('#tab_title_'+id).val());

        e.preventDefault();
    });

    $(document).on('change', '.component_title', function (e) {
        var id = $(this).attr('id');
        id = getId(id);
        $('.component_caption_'+id).html($('#component_title_'+id).val());

        e.preventDefault();
    });

    // update components

    $(document).on('click', '#save_components_btn', function (e) {


        var all_components = new Array();
        var ids = new Array();

        $('.component_div').each(function(){
            var components_data = {};

                var cid = $(this).attr('id');

                var component_id = getId(cid);

                components_data             = { component_id: component_id };

                // component title
                components_data.title       = $('input[name=component_title_'+component_id+']').val();

                // component description
                components_data.desc        = $('#component_desc_'+component_id).val();

                // component id
                components_data.product     = $('#product_component_'+component_id).val();

                // parent_id
                components_data.parent_id   = $('#product_id').val();


            all_components.push(components_data);

        });


        $.ajax({
            url: "/ajax/update_components",
            method: 'POST',
            data: {product_id:$('#product_id').val(), all_components:all_components},
            dataType: 'json'
        }).success(function(response) {

        });
        e.preventDefault();
    });

    // end

    // delete component

        $(document).on('click','.rm_component', function (e) {

            var id = $(this).attr('id');
            id = getId(id);
            $.ajax({
                url: "/ajax/delete_component",
                method: 'POST',
                data: {id:id},
                dataType: 'json'
            }).success(function(response) {
                 $('.component_count').html($('.component_div').length);
                if($('.component_div').length == 0)
                {
                    $('#save_components_btn').hide();
                }else{
                    $('#save_components_btn').show();
                }
            });
            e.preventDefault();
        });

    // end


    // remove attribute
    $(document).on('click','.rm_attribute',function(){
        $('.att_msg').html('');
        var id = getId($(this).attr('id'));
        //id = id.replace('rm_','');
        $("#attributes option[value='"+id+"']").removeAttr("disabled");

        // save attributes
        saveAttributes();
        hidesaveButton();

    });


    $(document).on('click','.rm_attribute_custom',function(){
        $('.att_msg').html('');
        var id = getId($(this).attr('id'));
        // save attributes
        saveAttributes();
        hidesaveButton();

    });
    // end


    // add variation
    $(document).on('click','.add_variation_btn',function(){

        if($('#product_id').val()=='')
        {
            return false;
        }
        //array('title' => $request->input('title'), 'parent_id' => $product_id)
        $.ajax({
            url: "/ajax/save_variation",
            method: 'POST',
            data: {title:$('#product_name').val(),parent_id:$('#product_id').val()},
            dataType: 'json',
        }).success(function(response){
            $('.variations_div').prepend(response['variation']);
            var var_count = $('.variation_no').length;
            $('.variation_count').html(var_count);
        }).complete(function () {
            $('.variation_sku').each(function () {
                $(this).rules("add", {
                    required: true,
                    digits:true
                });
            });

            $('.variation_price').each(function () {
                $(this).rules("add", {
                    required: true,
                    currency:true
                });
            });
            $('.sale_price').each(function () {
                $(this).rules("add", {
                    currency:true
                });
            });
        })

    });
    // end


    // remove variation

    $(document).on('click','.remove_variation',function(){

        var id = $(this).attr('id');
        id = id.replace('variation_','');
        // delete variation form database and related data from other tables
        $.ajax({
            url: "/ajax/delete_variation",
            method: 'POST',
            data: {id:id},
            dataType: 'json',
        }).success(function(response){
                var var_count = $('.variation_no').length;
                $('.variation_count').html(var_count);

            })

    });

    // end

    // remove tab

    $(document).on('click','.rm_tab ',function(){

        var id = $(this).attr('id');
        id = getId(id);
        // delete tab
        $.ajax({
            url: "/ajax/delete_custom_tab",
            method: 'POST',
            data: {id:id},
            dataType: 'json',
        }).success(function(response){
            var var_count = $('.variation_no').length;
            $('.variation_count').html(var_count);

        })

    });

    // end

    // save attributes and create variation for each

    $(".save_attributes_btn").click(function(e){
        saveAttributes();
        e.preventDefault();
    });

    $(".update_variations_btn").click(function(e){
        updateVariations();
        e.preventDefault();
    });


    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href").replace('#','');  // activated tab

        if(target == 'variations')
        {

            var pid = $('#product_id').val();
            $.ajax({
                url: "/ajax/get_variations",
                method: 'POST',
                data: {id:pid},
                dataType: 'json',

            }).success(function(response){

                    //if(response['variations']!='' && response['count']!='' )
                    //{
                        $('.variations_div').html(response['variations']);
                        $('.variation_count').html(response['count']);
                    //}


                    if($('#product_id').val()=='')
                    {
                        $('#product_id').val(response['product_id']);
                    }


                }).complete(function () {
                $('.variation_sku').each(function () {
                    $(this).rules("add", {
                        required: true,
                        digits:true
                    });
                });

                $('.variation_price').each(function () {
                    $(this).rules("add", {
                        required: true,
                        currency:true
                    });
                });
                $('.sale_price').each(function () {
                    $(this).rules("add", {
                        currency:true
                    });
                });


                })
        }
    });





    function saveAttributes()
    {

        // to do: need to build terms array form database
        var terms = { 1:[], 2:[], 3:[], 4:[] };

        var selected = false;

        if($('.product_attribute').length>0) {


            $('.product_attribute').each(function () {
                var id = $(this).attr('id');
                id = id.replace('attributes_', '');

                if ($(this).val() !== null) {
                    selected = true;
                    terms[id] = $(this).val();
                } else if ($(this).val() === null) {
                    selected = false;
                }

            });
        }


        var custom_terms = { };
        var ci = 0 ;
        if($('.custom_att_div').length>0) {

            $('.custom_att_div').each(function () {

                if ($('#custom_att_' + ci).val() == '') {
                    scrollToId('attribute');
                    $('.att_msg').html('Choose attribute value');
                    selected = false;
                    //return false;
                } else {
                    $('.att_msg').html('');
                    var cs_attribute = $('#custom_att_' + ci).val();
                    var att_terms = $('#custom_attributes_' + ci).val();
                    var term = {};
                    term.att_name = cs_attribute;
                    term.att_terms = att_terms;
                    custom_terms[ci] = term;

                    selected = true;
                }

                ci++;
            });
        }

        console.log('b'+ selected);
        if($('.custom_att_div').length==0 && $('.product_attribute').length==0)
        {
            console.log('g'+ selected);
            selected = true;
        }
        console.log('d'+ selected);
        if(selected === false)
        {
            scrollToId('attribute');
            $('.att_msg').html('Choose attribute value');
            return false;
        }

        console.log('c'+ selected);

        console.log('custom terms' + custom_terms);
        var pid = $('#product_id').val();
        var title = $('#product_name').val();

        $.ajax({
            url: "/ajax/save_attributes",
            method: 'POST',
            data: {id:pid, title:title, terms:terms, custom_terms:custom_terms },
            dataType: 'json',
        }).success(function(response){

                if(response['variations']!='' && response['count']!='' )
                {
                    $('.variations_div').html(response['variations']);
                    $('.variation_count').html(response['count']);
                }

                if($('#product_id').val()=='')
                {
                    $('#product_id').val(response['product_id']);
                }


            })
    }




    /*$(document).ready(function () {
        $( ".variation_sku" ).eq( 0 ).change(function(){ alert($(this).val()); }) ;
    })*/


    // end


    $(".save-attribute-features").click(function(e){

        var attributes_features = [];

        $(".attribute-id-hidden").each(function(){
            //console.log($(this).val());
            var attribute_id = $(this).val();
            var attributes_terms = ($(this).parent().siblings('.col-md-6').find('.attribute-select2').val());
            var product_id = $("input[name=product_id]").val();
            var is_show_on_product = null;

            if($(this).parent().siblings('.col-md-6').find(".attribute-chechbox").is(":checked")){
                is_show_on_product = "Yes";
            }else{
                is_show_on_product = "No";
            }

            attributes_features.push([product_id,attribute_id,attributes_terms,is_show_on_product]);

        });

        $.post("/ajax/save_attribute_features",{attributes_features:attributes_features}, function(response){
            console.log(response);
        });

        e.preventDefault();
    });

    $(document).on('change', '.variation_item', function() {
        var id = $(this).attr('id').replace(/\D/g,'');

        $('#updated_'+id).val('1');
        $('.update_variations_btn').removeClass('disabled');
    });


    $(document).on('change', '.sku_0', function() {
        if($('#sku_update').val() == 0) {

            var fval = parseInt($(this).val());
            var i = 0;
            $('.variation_sku').each(function () {
                $(this).val(fval + parseInt(i++));
            });

            $('#sku_update').val(1);
            $('.update_ids').each(function () {
                $(this).val(1);
            })
        }
    });


    $(document).on('change', '.variation_price_0', function() {
        if($('#price_update').val() == 0) {
            var fval = parseInt($(this).val());
            $('.variation_price').each(function () {
                $(this).val(fval);
                $('.update_ids').val('1');
            });
            $('#price_update').val(1);
            $('.update_ids').each(function () {
                $(this).val(1);
            })
        }
    });

    $(document).on('change', '.variation_price', function() {
        $('.variation_price').val($(this).val()); 
    });






    $(".select2").select2({ width: '300px' });

    $(".attribute-select2").select2({ width: '200px' });
    function inventoryEnable(){
        $('#uniform-inventory_level').show();
        $("#inventory_level").show();
        $('.inv_manage_option_label').text('Enable product inventory');
        $("#inventory_level").removeAttr('disabled');
    }


    function tabManage(){
        var product_type = $('#product_type').val();
        activeDefaultTab();

        if(product_type == 'simple') {

            if($('product_id').val()=='')
            {
                $('#inventory_level').attr('checked', 'checked');
                $('#uniform-inventory_level span').addClass('checked');
            }


            $('#variations_tab').hide();
            $('.use_as_variation').hide();
            $('#attributes_tab').hide();
            $('#components_tab').hide();

            $('.stock_mng').show();
            $('.stock_status').show();
            $('.sold_individually').show();
            //$('.sale_schedule').show();

            inventoryEnable();
            $('.simple').show();
            //$('.stock_qty').show();

        }else if(product_type == 'variable') {

            $('.allow_back_orders').show();
            $('.stock_mng').hide();
            $('.stock_status').hide();
            $('.sold_individually').hide();
            $('#components_tab').hide();
            //$('.sale_schedule').hide();

            $('#attributes_tab').show();
            $('#variations_tab').show();
            $('.simple').hide();
            $('.stock_qty').hide();
            $('.use_as_variation').show();
            inventoryEnable();

        }else if(product_type == 'composite'){

            //$('#inventory_level').removeAttr('checked');
            $('.stock_mng').show();
            $('#components_tab').show();
            //$('.sale_schedule').show();

            $('.stock_status').hide();
            $('.sold_individually').hide();
            $('#attributes_tab').hide();
            $('.simple').hide();

            $('.stock_qty').hide();
            $('.allow_back_orders').hide();
            $('#variations_tab').hide();
            $('.use_as_variation').hide();
            //$('#inventory_level').removeAttr('checked').attr('disabled', 'disabled').hide();
            //$('#uniform-inventory_level').hide();
            $('.inv_manage_option_label').text('Manage inventory for each component');
        }
    }


    $("#inventory_level").on('change',function(){

        if($(this).is(':checked'))
        {
            $('.stock_qty').show();
            $('.back_order_limit').show();
            $('.allow_back_orders').show();
        }else{
            $('.stock_qty').hide();
            $('.back_order_limit').hide();
            $('.allow_back_orders').hide();
        }
    });
    $("#product_type").on('focus', function () {
        // Store the current value on focus and on change
        current_type = this.value;
    }).on('change',function(){
        if($('#product_type').val() == 'variable' || $('#product_type').val() == 'composite')
        {
            $(".parent_product_sku").rules("remove", "required");
            var tooltip_id = getId($('#parent_product_sku').attr('aria-describedby'));
            $('#tooltip'+tooltip_id).hide();
        }else{
            $(".parent_product_sku").rules("add", "required");
        }


        if($('#product_id').val()!='')
        {
            bootbox.confirm("Are you sure to change product type?", function(result) {
                if(result)
                {
                    tabManage();
                    $(".regular_price ").rules("remove", "required");
                }

            });
        }else{
            tabManage();
            $(".regular_price ").rules("remove", "required");
        }


    });

    $(document).ready(function () {
        var product_type = $("#product_type").val();
        tabManage(product_type);
    });





    $(".new-user").click(function(){
        $("#new-user").toggle();
    });

    $(".tile.image").click(function(){
        var ths = $(this);
        $(".tile").each(function(){
            if($(this).hasClass('selected')){
                $(this).removeClass('selected');
            }
        });

        ths.addClass('selected');

    });

    $(".addnewcategory").click(function(){
        $("#new-product-section").slideToggle();
    });

    $(".remove-tag").click(function(){
        var product_id = $("#product_id").val();
        var tag_id = $(this).attr('data-tag-id');

        var result = delete_tag_with_product(tag_id, product_id);
        //alert(result);
        //if(result)
        $(this).parent().remove();
    });

   


    $(".addtag").click(function(e){

        var newTag = $("#new-tag").val();
        var product_id = $("#product_id").val();

        if(product_id==""){
            $("input[name=product_name]").focus();
            return false;
        }

        if(newTag==""){
            $("#new-tag").focus();
            return false;
        }

        $.post("/ajax/save_product_tag",{name:newTag, product_id:product_id}, function(response){

            $(".tags").prepend('<li><a href="javascript:;" data-tag-id="'+response+'" class="remove-tag"><i class="fa fa-remove"></i></a> '+newTag+' </li>');

            //response = jQuery.parseJSON(response);

            $(".remove-tag").click(function(){
                var tag_id = $(this).attr('data-tag-id');
                var result = delete_tag_with_product(tag_id, product_id);
                $(this).parent().remove();
            });

        });



        e.preventDefault();
    });

    $(".publish-product").click(function(e){

        e.preventDefault();

    });

    $(".add-new-category-btn").click(function(e){

        var category_name = $("#add-new-category").val();

        if(category_name==""){
            $("#add-new-category").focus();
            return false;
        }

        var parent_category_id = $("#parent-category").val();
        var level			   = $("#parent-category option:selected").attr('data-level');
        var inserted_category_id = 0;
        $.post("/ajax/save_category",{parent_category_id:parent_category_id,category_name:category_name}, function(response){
            inserted_category_id = response;



            if(parent_category_id==""){
                $(".all-product-categories").prepend('<li class="parent custom" data-parent-value="'+parent_category_id+'"><input class="custom" type="checkbox" value="'+inserted_category_id+'" checked> '+category_name+'</li>');
                $("#parent-category").append('<option class="level" data-level="0" value="'+inserted_category_id+'">'+category_name+'</option>');
            }
            else{
                var space = (parseInt(level)+1)*2;
                var str = "";
                for(var i=1;i<=space;i+=2){
                    str+="&nbsp";
                }

                $('.all-product-categories li').each(function(){
                    if($(this).attr('data-parent-value')==parent_category_id){
                        $(this).after('<li style="padding-left:15px"  class="child" data-parent-value="'+category_name+'"><input class="custom" type="checkbox" value="'+inserted_category_id+'" checked> '+category_name+'</li>');
                    }
                });


                $("#parent-category option:selected").after('<option class="level" data-level="'+(parseInt(level)+1)+'" value="'+inserted_category_id+'">'+str+category_name+'</option>');
            }



            $("#add-new-category").val('');
            $("#parent-category option:first").attr('selected','selected');



        });
        //return false;

        $.uniform.update();


        e.preventDefault();
    });


    $(".product-category").change(function(){
        /*var ids_string = "";
         $(".product-category").each(function(){
         if($(this).is(":checked")){
         ids_string+=($(this).val())+",";
         }
         });

         $("#product-choose-category").val(ids_string);*/
        var checkedValues = $('.product-category:checked').map(function() {
            return this.value;
        }).get();

        $("#product-choose-category").val(checkedValues);

    });



    $("input[name=product_name]").focusout(function(e) {

        var title   = $(this).val();
        var type    = $('#product_type').val();

        if(title=="")
            return false;

        var product_id = $("#product_id").val();
        if(product_id=="" || typeof(product_id)=="undefined"){
            $.ajax({
                url: '/ajax/save_product',
                data: {title:title, type:type},
                type: 'POST',
                success: function(response){
                    response = jQuery.parseJSON(response);
                    $("#product-slug").html(response.product_slug);
                    $("#product_id").val(response.last_inserted_product_id);
                    $("#perma-link-section").fadeIn();

                }
            });
        }else{

            $.ajax({
                url: '/ajax/update_product',
                data: {title:title,id:product_id,type:type},
                type: 'POST',
                success: function(response){
                    response = jQuery.parseJSON(response);
                    $("#product-slug").html(response.product_slug);
                    $("#perma-link-section").fadeIn();

                }

            });

        }
    });
    

    $(".edit-slug").click(function(e){
        $("#product-slug").html('<input type="text" name="product_slug" id="new-slug" value="'+$("#product-slug").html()+'" />  <span id="save-cancel-action-button"> <a href="javascript:;" class="save-new-slug">Save</a> | <a href="javascript:;" class="cancel-new-slug">Cancel</a></span>');
        $(this).hide();

        $(".save-new-slug").click(function(){
            var new_slug 			= $("#new-slug").val();
            var product_id 	= $("#product_id").val();
            $.post("/ajax/save_slug",{id:product_id, new_slug:new_slug }, function(response){

                $("#product-slug").html($("#new-slug").val());
                $("save-cancel-action-button").remove();

            });

            $("#product-slug").html();
            $(".edit-slug").show();
        });

        e.preventDefault();
    });

    $('body').on('click', '.cancel-new-slug', function () {
        $("#product-slug").html($("#new-slug").val());
        $("save-cancel-action-button").remove();
        $('.edit-slug').show();
    });

    $(".editpublish").click(function(){
        $("#available-status").toggle('slow');
    });
    $('.cancelpublish').click(function(){
        $('#available-status').toggle('slow');
    });
    $(".editvisible").click(function(){
        $("#available-visible").toggle('slow');
    });
    $(".cancelvisible").click(function(){
        $("#available-visible").toggle('slow');
    });

    $(".addstatus").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var status = $('#status').val();
        status = status.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
        $(".showpub").text(status);
        $("#available-status").toggle('slow');
    });
    $(".addvisible").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var visible = $('#visible').val();
        visible = visible.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
        $(".showvis").text(visible);
        $("#available-visible").toggle('slow');
    });
    $(".editcal").click(function(){
        $("#available-calender").toggle('slow');
    });
    $(".cancelcal  ").click(function(){
        $("#available-calender").toggle('slow');
    });
    $(".addcal").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        var month = $('#mm').val();
        var month_index = month-1;
        getMonthName = function (v) {
            var n = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            return n[v]
        }
        var month_name = (getMonthName(month_index));


        var date = $('#dd').val();
        var year = $('#yy').val();
        var hour = $('#hr').val();
        var min = $('#min').val();
        var date_text = date+' '+month_name+','+year+' '+hour+':'+min;

        $(".showdate").text(date_text);
        $('#publish_on').val('1');
        $("#available-calender").toggle('slow');
    });


    var tags  = new Bloodhound({
        datumTokenizer: function (datum) {
            return Bloodhound.tokenizers.whitespace(datum.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/ajax/get_tags?query=',
            filter: function (movies) {
                // Map the remote source JSON array to a JavaScript object array
                return $.map(movies.results, function (movie) {
                    return {
                        value: movie.original_title
                    };
                });
            }
        }
    });

    tags.initialize();

    /*$('.typeahead').typeahead(null, {
     displayKey: 'value',
     source: tags.ttAdapter()
     });*/

    $("#new-tag").keyup(function(){


    });

    $(".available-tags").click(function(){
        $("#available-tags").toggle();
    });

    $(".add-available-tag").click(function(){
        var tag_id = $(this).attr('data-tag-id');
        var product_id = $("#product_id").val();
        var ths = $(this);

        if(product_id==""){
            $("input[name=product_name]").focus();
            return false;
        }


        $.post("/ajax/save_product_tag",{tag_id:tag_id, product_id:product_id}, function(response){

            $(".tags").prepend('<li><a href="javascript:;" class="remove-tag" data-tag-id="'+response+'"><i class="fa fa-remove"></i></a> '+ths.text()+' </li>');

            response = jQuery.parseJSON(response);
            ths.remove();
            $(".remove-tag").click(function(){
                var tag_id = $(this).attr('data-tag-id');
                var result = delete_tag_with_product(tag_id, product_id);
                $(this).parent().remove();
            });

        });
        //alert(tag_id);
    });



});
//
function delete_tag_with_product(tag_id, product_id){

    $.post("/ajax/delete_tag_with_product", {
        tag_id:tag_id,
        product_id :product_id
    }, function(response){

        //alert(response);
        //return true;

    });
    //return false;
}


function selectAllAtt(id){
    var id = "attributes_"+id ;

    $("#"+id+" > option").prop("selected","selected");// Select All Options
    $("#"+id).trigger("change");// Trigger change to select 2

}


function selectNoneAtt(id){
    var id = "attributes_"+id ;
    $("#"+id+" > option").removeAttr("selected");// Select All Options
    $("#"+id).trigger("change");// Trigger change to select 2
}

function activeDefaultTab(){
    $('.ptabs').each(function () {
        $(this).removeClass('active').removeClass('in');
    });

    $('.tabs-left li').each(function () {
        $(this).removeClass('active');
    });

    $('#general').addClass('active in');
    $('#general_tab').addClass('active');
}

function showWaitlistForm(fid)
{
    $('.waitlist_form').hide();
    $('.waitlist_form_'+fid).slideDown();
}

function addToWaitList(pid)
{
    var email = $('#add_user_'+pid).val();

    if(email=='' || validateEmail(email)===false)
        return false;

    $.ajax({
        url: '/ajax/save_to_waitlist',
        data: {product_id:pid,email:email},
        type: 'POST',
        dataType: 'json',
        success: function(response){
             console.log(response['action']);
            if(response['action'] == 'true')
            {
                var user_id =  response['user_id'];
                var str = '';
                str += '<tr id="rm_user_'+user_id+'">';
                str += '<td>'+email+'</td>';
                str += '<td><a href="javascript:;" onclick="removeFromWaitList( '+pid+',  '+user_id+'  )" class="rm_user_'+user_id+'">Remove</a></td>';
                str += '</tr>';
                $('#wait_list_table_'+pid).append(str);
                $('#add_user_'+pid).val('');

                if($('.wait_row_'+pid).length<1)
                {
                    $('#no_wait_'+pid).hide();
                }else{
                    $('#no_wait_'+pid).hide();
                }

            }else if(response['action'] == 'false' && response['msg']){
                alert(response['msg']);
                $('#add_user_'+user_id).val('');
            }else{
                alert('Error occured');
            }

        }

    });

}

function removeFromWaitList(pid, user_id) {

    $.ajax({
        url: '/ajax/remove_form_waitlist',
        data: {product_id:pid,user_id:user_id},
        type: 'POST',
        dataType: 'json',
        success: function(response){
            console.log(response['action']);
            if(response['action'] == 'true')
            {
                $('#rm_user_'+user_id).remove();
            }else if(response['action'] == 'false' && response['msg']){
                alert(response['msg']);
            }else{
                alert('Error occured');
            } 

        }

    });
}

function addSwatch() {

    var swatch = $('#color_swatches_products').val();
    if(swatch=='')
        return false;

    var pid = $('#product_id').val();

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

                var str = '<div class="col-md-9" id="rm_swatch_'+val+'"><span class="swatch_txt_'+val+'">'+txt+'</span><a href="javascript:;" class="pull-right" onclick="deleteSwatch('+swatch_id+')">Delete</a></div>';
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

function deleteSwatch(id)
{

    var pid  = $('#product_id').val();

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

function updateTabs()
{
    var all_tabs = new Array();
    var ids = new Array();

    $('.custom_tab').each(function(){
        var tabs_data = {};

        var rcid = $(this).attr('id');

        var tab_id = getId(rcid);

        tabs_data             = { tab_id: tab_id };

        // tab  title
        tabs_data.title       = $('input[name=tab_title_'+tab_id+']').val();

        // tab  description
        var tab_description =  CKEDITOR.instances[ 'tab_description_'+tab_id ].getData();
        tabs_data.desc        = tab_description;

        all_tabs.push(tabs_data);

    });
    if(all_tabs!='')
    {
        $.ajax({
            url: "/ajax/update_tabs",
            method: 'POST',
            data: {product_id:$('#product_id').val(), tabs_data:all_tabs},
            dataType: 'json'
        }).success(function(response) {
        });

    }
    var data = CKEDITOR.instances.details_tab.getData();
    var details_tab  = { title:$('.details_tab_title').val(), desc:data};

    $.ajax({
        url: "/ajax/update_details_tab",
        method: 'POST',
        data: {product_id:$('#product_id').val(),details_tab:details_tab},
        dataType: 'json'
    }).success(function(response) {
    });
}




/*"
 * product variations
 *
 * */


$('.var_inventory_level').on('change',function() {

    var id = $(this).attr('id').replace('var_inventory_level_','');

    if ($('#var_inventory_level_'+id).is(':checked')) {
        $('#var_inventory_level_'+id).attr("checked", true);
        $.uniform.update();
        $('.var_stock_manage_' + id).show();

    } else {
        $('#var_inventory_level_'+id).attr("checked", false);
        $.uniform.update();

        $('.var_stock_manage_' + id).hide();
    }
});

$('body').on('click', '.var_expend_all', function(){ $('#variations .expand').click(); })
$('body').on('click', '.var_collapse_all', function(){ $('#variations .collapse').click(); })

$('body').on('click', '.com_expend_all', function(){ $('#components .expand').click(); })
$('body').on('click', '.com_collapse_all', function(){ $('#components .collapse').click(); })

/* end */


/**
 *
 * general functions
 *
 *
 * */


function getId(str)
{
    if(str !== undefined)
    {
        return str.replace(/\D/g,'');
    }

}

function hidesaveButton(){

    var disabled_length = $('.product_attribute').length;

    var custom_atts = $('.custom_att_name').length;

    var tot_length = custom_atts+disabled_length;


    if(tot_length == 0)
    {
        $('.save_attributes_btn').hide();
        $('.add_variation_btn').hide();
    }else{
        $('.add_variation_btn').show();
        $('.save_attributes_btn').show();
    }
}


function hideUpdateButton(){

    var length = $(".variation_no").length;
    if(length == 0)
    {
        $('.update_variations_btn').hide();
    }else{
        $('.update_variations_btn').show();
    }
}


function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function loader(cls)
{
    if(cls=='show')
    {
        $('.page-spinner-bar').removeClass('hide');
    }else{
        $('.page-spinner-bar').addClass('hide');
    }

}

function scrollToId(id)
{
        $('html, body').animate({
            scrollTop: ($("#"+id).offset().top)-40
        }, 700);

}

/* end */


///////////////////////// fields validation ///////////////
$(document).ready(function () {
    $.validator.addMethod("currency", function (value, element) {
        return this.optional(element) || /^(\d{1,3}(\,\d{3})*|(\d+))(\.\d{2})?$/.test(value);
    }, "Please specify a valid amount");

    $("#product-form").validate({
        ignore: [],
        rules: {
            product_name:{ required: true },
            product_sku: { digits:true, required: true },
            variation_sku:{ digits:true, required: true  },
            regular_price: { currency: ["$", false], required: true },
            sale_price: { currency:[["$", false]] }
        },
        tooltip_options: {
            product_sku: { placement: 'top' },
            variation_sku: { placement: 'top' },
            regular_price: { placement: 'top' }

        },
        invalidHandler: function(form, validator) {
            console.log(validator.numberOfInvalids());
            if(validator.numberOfInvalids() > 0)
            {
                var error_list = validator.errorList;

                $.each(error_list, function (i, v) {
                     var pid = getId(v.element.name);
                     if(pid!='')
                     {
                         if($('#collapsed_'+pid).hasClass('expand'))
                         {
                             $('#collapsed_'+pid).click();
                         }

                         $('html, body').animate({
                             scrollTop: $('#collapsed_'+pid).offset().top
                         }, 100);
                     }else{

                     }
                });
            }

            $(".error_div").html('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' + validator.numberOfInvalids() + ' error' + (validator.numberOfInvalids() > 1 ? 's' : '') + ' found.  please fill the fields properly </div>');
        },
        submitHandler: function(form) {
             if($('#submitorm').val() == 1 )
             {
                 if($('#product_type').val() == 'variable' )
                 {
                     updateVariations();
                 }

                 updateTabs();
                 form.submit();
             }
        }
    })


    if($('#product_id').val() !='') {

        if ($('#product_type').val() == 'variable' || $('#product_type').val() == 'composite') {
            $(".parent_product_sku").rules("remove", "required");
            console.log($('#parent_product_sku').attr('aria-describedby'));
        } else {
            $(".parent_product_sku").rules("add", "required");
        }
    }



});


$( document.body )

    .on('click', '#move_to_trash', function () {
        $('#status').val('deleted');
    })
    .on('click', '#publish, #save_changes, #udpate, #save_draft, #move_to_trash', function(){
         $('#form-action').val($(this).attr('id'));
        if( $('#form-action').val()!='')
        {
            $('#product-form').submit();
        }
    })

////////////////////////////////////////////////////////




function updateVariations()
{

    if($("#product-form").valid() == true)
    {
        formValid = true;
    }

    $('#submitform').val(0);
    console.log(formValid + ' updatevariation');

    if(formValid === false)
        return false

    var all_variations = new Array();
    var ids = new Array();

    $('.update_ids').each(function(){
        var variations_data = {};

            var cid = $(this).attr('id');

            var variation_id = getId(cid);

            variations_data = { variation_id: variation_id };

            // get terms values
            var vattributes = new Array();
            $('.attributes_in_variation').each(function () {
                //console.log($(this).attr('id'));
                var attid = getId($(this).attr('id'));
                if(variation_id === attid)
                {
                    vattributes.push($(this).val());
                }
            });

            variations_data.attributes = vattributes;
            // end

            // variation sku
            variations_data.sku = $('input[name=variation_sku_'+variation_id+']').val();

            // variation_status
            variations_data.status = $('input[name=variation_status_'+variation_id+']').val();

            // manage_stock_
            variations_data.manage_stock = $('input[name=manage_stock_'+variation_id+']').val();

            // regular_price
            variations_data.regular_price = $('input[name=price_'+variation_id+']').val();

            // sale_price

            variations_data.sale_price = $('input[name=sale_price_'+variation_id+']').val();

            //stock_qty
            variations_data.stock_qty = $('input[name=stock_qty_'+variation_id+']').val();

            //allow_backorders
            variations_data.allow_backorders = $('select[name=allow_backorder_'+variation_id+']').val();

            //sale_form
            $('input[name=from]').each(function () {
                var id = getId($(this).attr('id'));
                if(variation_id === id)
                {
                    variations_data.sale_form = $(this).val();
                }
            }) ;


            //sale_to
            $('input[name=to]').each(function () {
                var id = getId($(this).attr('id'));
                if(variation_id === id)
                {
                    variations_data.sale_to = $(this).val();
                }
            }) ;


            //stock_status
            variations_data.stock_status = $('select[name=stock_status_'+variation_id+']').val();

            //tax_status
            variations_data.tax_status = $('select[name=tax_status_'+variation_id+']').val();

            //tax_rate
            variations_data.tax_rate = $('select[name=tax_rate_'+variation_id+']').val();

            //expected_date_delivery_
            variations_data.expected_date_delivery = $('input[name=expected_date_delivery_'+variation_id+']').val();

            // parent_id
            variations_data.parent_id= $('#product_id').val();


        all_variations.push(variations_data);

    });




    $.ajax({
        url: "/ajax/update_variations",
        method: 'POST',
        data: { all_variations: all_variations },
        dataType: 'json',
    }).success(function(response){

        if(response['variations']!='' && response['count']!='' )
        {
            $('.variations_div').html(response['variations']);
            $('.variation_count').html(response['count']);
        }

        if($('#product_id').val()=='')
        {
            $('#product_id').val(response['product_id']);
        }


    })
}