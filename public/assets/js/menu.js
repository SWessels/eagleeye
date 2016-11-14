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

        // hidesaveButton();
        //hideUpdateButton();


    }

});
function loader(cls)
{
    if(cls=='show')
    {
        $('.page-spinner-bar').removeClass('hide');
    }else{
        $('.page-spinner-bar').addClass('hide');
    }

}

$(document).ready(function() {

    //$('#nestable').nestable();
    var menu_text= jQuery("#mainmenu option:selected").text();
    $('#menu_name').val(menu_text);

    $('#nestable3').nestable({
            group: 1
        })
        .on('change', updateOutput);

    updateOutput($('#nestable3').data('output', $('#nestable-output')));

    $('#add_pages_to_menu , #add_pages_to_menuSearch').click(function (e) {
        e.preventDefault();

        var checkPage = new Array();
        $.each($("input[name='checkpg[]']:checked"), function() {
            checkPage.push($(this).val());

        });
        var menu_id= jQuery("#mainmenu option:selected").val();

      //  var str ='<ol class="dd-list">';
        var str ='';
        $.each(checkPage , function(i , v){
            var title = $.trim($('#page'+v).text());
            var slug = $.trim($('#page'+v).data("url") );
            var sub_menu_id = v;
            var type = 'Page';

            str += '<li class="dd-item dd3-item"  data-id="'+v+'" data-type ="page" data-title ="'+title+'" data-url ="'+slug+'">';
            str +=   '<div class="dd-handle dd3-handle" style="height: 41px">Drag</div>' +
                '<div class="portlet box default " style="width:500px" >';
            str +=   '<div class="portlet-title ">';
            str +=   '<div class="caption" style="padding-left:29px">';
            str +=  '</i><div class="new_pageTitle">'+title+'</div>';
            str +=   '</div>';
            str +=    '<div class="tools">'+type;
            str +=    '<a href="javascript:;" class="expand" data-original-title="" title=""> </a>';
            str +=    '</div>';
            str +=   '</div>';
            str +=   '<div class="portlet-body  collapse"><div class="row">Navigation Label<input data-menu_id ="'+menu_id+'" value="'+title+'" type="text" class="form-control edit_pageTitle"  name="edit_page" id="'+v+'">' +
                '</div><div class="row"><a data-menu_id ="'+menu_id+'" id="'+v+'" class="remove_menu" href="#">Remove</a> | <a id="'+v+'" href="javascript:;"  class="collapse_cancel"> Cancel</a> </div></div> </div></li>';

        });
        // str += '</ol>';
        $('.dd-list').first().append(str);
        $('#nestable3').trigger( "change" );

        $(".select_pages").prop('checked',false);
        $(".select_pages_search").prop('checked',false);
        $.uniform.update();
    });


    $('#add_custom_to_menu').click(function (e) {
        e.preventDefault();
         var custom_url = $('#custom_url').val();
         var custom_title =$('#custom_title').val();
        var menu_id= jQuery("#mainmenu option:selected").val();
       // var count = 1;
         // return false;
         var str ='';


            var type = 'Custom';
            str += '<li class="dd-item dd3-item"  data-id="0" data-type ="custom" data-title ="'+custom_title+'" data-url ="'+custom_url+'">';
            str +=   '<div class="dd-handle dd3-handle" style="height: 41px">Drag</div>' +
                '<div class="portlet box default " style="width:500px" >';
            str +=   '<div class="portlet-title ">';
            str +=   '<div class="caption" style="padding-left:29px">';
            str +=  '</i><div class="new_customTitle">'+custom_title+'</div>';
            str +=   '</div>';
            str +=    '<div class="tools">'+type;
            str +=    '<a href="javascript:;" class="expand" data-original-title="" title=""> </a>';
            str +=    '</div>';
            str +=   '</div>';
            str +=   '<div class="portlet-body  collapse"><div class="row">URL<input name="edit_URL" class="form-control edit_customURL" value="'+custom_url+'" type="text" ></div>' +
                '<div class="row">Navigation Label<input data-menu_id ="'+menu_id+'" value="'+custom_title+'" type="text" class="form-control edit_customTitle"  name="edit_custom" id="'+0+'">' +
                '</div><div class="row"><a data-menu_id ="'+menu_id+'" id="'+0+'" class="remove_menu" href="#">Remove</a> | <a id="'+0+'" href="#" class="collapse_cancel"> Cancel</a> </div></div> </div></li>';

        // str += '</ol>';
        $('.dd-list').first().append(str);

        $('#nestable3').trigger( "change" );


    });

    $('#add_posts_to_menu, #add_posts_to_menuSearch').click(function (e) {
        e.preventDefault();
        var checkPosts = new Array();
        $.each($("input[name='checkposts[]']:checked"), function() {
            checkPosts.push($(this).val());
        });
        var menu_id= jQuery("#mainmenu option:selected").val();

        var str ='';
        $.each(checkPosts , function(i , v){

            var title = $.trim($('#post'+v).text());
            var slug = $.trim($('#post'+v).data("url") );
            var type = 'Post';
            str += '<li class="dd-item dd3-item"  data-id="'+v+'" data-type ="post" data-title ="'+title+'"  data-url ="'+slug+'">';
            str +=   '<div class="dd-handle dd3-handle"  style="height: 41px">Drag</div><div class="portlet box default"  style="width:500px" >';
            str +=   '<div class="portlet-title ">';
            str +=   '<div class="caption " style="padding-left:29px">';
            str +=  '</i><div class="new_postTitle">'+title+'</div>';
            str +=   '</div>';
            str +=    '<div class="tools">'+type;
            str +=    '<a href="javascript:;" class="expand" data-original-title="" title=""> </a>';
            str +=    '</div>';
            str +=   '</div>';
            str +=   '<div class="portlet-body  collapse"><div class="row">Navigation Label<input data-menu_id ="'+menu_id+'" value="'+title+'" type="text" class="form-control edit_postTitle"  name="edit_post" id="'+v+'">' +
                '</div><div class="row"><a data-menu_id ="'+menu_id+'" id="'+v+'" class="remove_menu" href="#">Remove</a> | <a id="'+v+'" href="#" class="collapse_cancel"> Cancel</a> </div></div></div></li>';
        });
        $('.dd-list').first().append(str);
        $('#nestable3').trigger( "change" );

        $(".select_posts").prop('checked',false);
        $(".select_posts_search").prop('checked',false);
        $.uniform.update();


    });

    $('#add_cats_to_menu ,#add_cats_to_menuSearch ').click(function (e) {

        e.preventDefault();
        var checkcat = new Array();
        $.each($("input[name='categories[]']:checked"), function() {
            checkcat.push($(this).val());

        });
        var menu_id= jQuery("#mainmenu option:selected").val();
        var str ='';
        $.each(checkcat , function(i , v){

            var title = $.trim($('#cat'+v).text());
            var slug = $.trim($('#cat'+v).data("url"));
            var type = 'Category';
            str += '<li class="dd-item dd3-item"  data-id="'+v+'" data-type ="category" data-title ="'+title+'"  data-url="'+slug+'">';
            str +=   '<div class="dd-handle dd3-handle"  style="height: 41px">Drag</div><div class="portlet box default"  style="width:500px" >';
            str +=   '<div class="portlet-title ">';
            str +=   '<div class="caption " style="padding-left:29px">';
            str +=  '</i><div class="new_catTitle">'+title+'</div>';
            str +=   '</div>';
            str +=    '<div class="tools">'+type;
            str +=    '<a href="javascript:;" class="expand" data-original-title="" title=""> </a>';
            str +=    '</div>';
            str +=   '</div>';
            str +=   '<div class="portlet-body  collapse"><div class="row">Navigation Label<input data-menu_id ="'+menu_id+'" value="'+title+'" type="text" class="form-control edit_catTitle"  name="edit_cat" id="'+v+'">' +
                '</div><div class="row"><a data-menu_id ="'+menu_id+'" id="'+v+'" class="remove_menu" href="#">Remove</a> | <a id="'+v+'" href="#" class="collapse_cancel"> Cancel</a> </div></div></div></li>';
        });
        $('.dd-list').first().append(str);
        $('#nestable3').trigger( "change" );
        $(".select_categories").prop('checked',false);
        $(".select_categories_search").prop('checked',false);
        $.uniform.update();


    });

    $('#search_page').keyup(function(){

        var keyword = $(this).val();
        $.ajax({
            url: '/menu/search_page',
            data: {keyword:keyword},
            dataType: 'json',
            type: 'POST',
            success: function (response) {
               
                var str = '<ul  class="all-post-categories">';
                $.each(response , function(i , v){
                    
                    str += ' <li class="parent" id="page'+v.id+'" data-url="'+v.slug+'"  data-parent-value="'+v.id+'">';
                    str += '<input class="product-category select_pages_search"   name="checkpg[]"  type="checkbox" value="'+v.id+'" >'+v.title+'</li>';
                    
                });

                str += '</ul>';

                $('.show_search_page').html(str);

            },
            error: function (response) {
                alert('Error: There is something wrong while searching pages.');
            }

        });

    });
    ////////////////////////////////////////////////////////////////////////////////////

    $('#search_post').keyup(function(){

        var keyword = $(this).val();
        $.ajax({
            url: '/menu/search_postcats',
            data: {keyword:keyword},
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                var str = '<ul  class="all-post-categories">';
                $.each(response , function(i , v){

                    str += ' <li class="parent" id="post'+v.id+'" data-url="'+v.slug+'"  data-parent-value="'+v.id+'">';
                    str += '<input class="product-category select_posts_search"   name="checkposts[]"  type="checkbox" value="'+v.id+'" >'+v.name+'</li>';

                });

                str += '</ul>';

                $('.show_search_post').html(str);

            },
            error: function (response) {
                alert('Error: There is something wrong while searching posts.');
            }

        });

    });

    ////////////////////////////////////////////////////////////////////////////////////

    $('#search_category').keyup(function(){

        var keyword = $(this).val();

        $.ajax({
            url: '/menu/search_category',
            data: {keyword:keyword},
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                var str = '<ul  class="all-post-categories">';
                $.each(response , function(i , v){

                    str += ' <li class="parent" id="cat'+v.id+'" data-url="'+v.slug+'"  data-parent-value="'+v.id+'">';
                    str += '<input class="product-category select_categories_search"   name="categories[]"  type="checkbox" value="'+v.id+'" >'+v.name+'</li>';

                });

                str += '</ul>';

                $('.show_search_category').html(str);

            },
            error: function (response) {
                alert('Error: There is something wrong while searching category.');
            }

        });

    });


    $('body').on('keyup', '.edit_pageTitle', function () {

        var new_title = $(this).val();
        $(this).parent().parent().parent().children().first().children().children().first().html(new_title);
        $(this).parent().parent().parent().parent().attr('data-title',new_title );

       });
    $('body').on('keyup', '.edit_postTitle', function () {

        var new_title = $(this).val();
        $(this).parent().parent().parent().children().first().children().children().first().html(new_title);
        $(this).parent().parent().parent().parent().attr('data-title',new_title );
    });

    $('body').on('keyup', '.edit_catTitle', function () {

        var new_title = $(this).val();
        $(this).parent().parent().parent().children().first().children().children().first().html(new_title);
        $(this).parent().parent().parent().parent().attr('data-title',new_title );

    });

    $('body').on('keyup', '.edit_customTitle', function () {

        var new_title = $(this).val();

        $(this).parent().parent().parent().children().first().children().children().first().html(new_title);
        $(this).parent().parent().parent().parent().attr('data-title',new_title );



    });
    $('body').on('keyup', '.edit_customURL', function () {

        var new_url = $(this).val();

        $(this).parent().parent().parent().parent().attr('data-url',new_url );



    });

    $('body').on('click', '.remove_menu', function (e) {
        e.preventDefault();
        e.stopPropagation();

    var parent_html =  $(this).parent().parent().parent().parent().html();
    //alert(parent_html);
    var first_ol =  $(this).parent().parent().parent().parent().children().last().html();
    //alert(first_ol);

    if (first_ol.match("^<li")) {
    $(this).parent().parent().parent().parent().replaceWith(first_ol);
    }
    else{
    $(this).parent().parent().parent().parent().remove();
    }


    $('#nestable3').trigger( "change" );
    });

    $('body').on('click', '.collapse_cancel', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $(this).parent().parent().css('display', 'none');
        $(this).parent().parent().parent().parent().children().last().children().first().children().last().children().removeClass('collapse');
        $(this).parent().parent().parent().parent().children().last().children().first().children().last().children().addClass('expand');

    });


    $('#save_menu').click(function (e) {

        e.preventDefault();
        e.stopPropagation();
        var a = $('#nestable-output').val();

        if(a == '[]'){
            alert('Select menu details before saving menu.');
            return false;
        }
        
        if ($('#is_sticky').is(':checked')) {
            var is_sticky = 'sticky';
        }
        else{
            var is_sticky = 0;
        }

        if ($('#is_primary').is(':checked')) {
            var is_primary = 'yes';
        }
        else{
            var is_primary = 'no';
        }

        var unpackArr = JSON.parse(a);
        //alert(unpackArr);
        var menu_Id= jQuery("#mainmenu option:selected").val();
       // alert(menu_Id);
        $.ajax({
            url: '/menu/save_menu',
            data: {menu_id: menu_Id ,is_sticky: is_sticky, is_primary:is_primary, menu_array:unpackArr },
            type: 'POST',
            success: function (response) {
                if(response != ''){
                    alert('Error: something wrong while saving menu details.');
                }
                else{
                    $('.showmsg').html('<div class="alert alert-success">Menu Saved Successfully..</div>');}

            },
            error: function (response) {
                alert('Error: something wrong while saving menu details.');
            }

        });

    });
    $('#save_menu2').click(function (e) {
        
        e.preventDefault();
        e.stopPropagation();
        var a = $('#nestable-output').val();

        if(a == '[]'){
            alert('Select menu details before saving menu.');
            return false;
        }
        var unpackArr = JSON.parse(a);
        //alert(unpackArr);
        var menu_Id= jQuery("#mainmenu option:selected").val();
        var menu_text= jQuery("#mainmenu option:selected").text();
         if ($('#is_sticky').is(':checked')) {
            var is_sticky = 'sticky';
        }
        else{
            var is_sticky = 0;
        }

        if ($('#is_primary').is(':checked')) {
            var is_primary = 'yes';
        }
        else{
            var is_primary = 'no';
        }

        $.ajax({
            url: '/menu/save_menu',
            data: {menu_id: menu_Id ,is_sticky:is_sticky, is_primary:is_primary, menu_array:unpackArr },
            type: 'POST',
            success: function (response) {
                if(response != ''){
                    alert('Error: something wrong while saving menu details.');
                }
                else{
                $('.showmsg').html('<div class="alert alert-success">Menu Saved Successfully..</div>');}

            },
            error: function (response) {
                alert('Error: something wrong while saving menu details.');
            }

        });

    });
    /////////////////////////////////////////////////////File upload/Save in DB/show Content//////////////
    $('body').on('click', '.selectMenu', function () {

         var menu_id= jQuery("#mainmenu option:selected").val();

        if($(this).val() != ''){

            $('#menu-form').submit();
        }
    });



    $('#select_all_pages').on('click',function(){

          if ($(this).is(':checked')) {
                $(".select_pages").prop('checked', $(this).prop("checked"), true);
                $.uniform.update();
            }else{
                $(".select_pages").prop('checked', $(this).prop("checked"), false);
                $.uniform.update();
            }
    });

    $('#select_all_pages_search').on('click',function(){

        if ($(this).is(':checked')) {
            $(".select_pages_search").prop('checked', $(this).prop("checked"), true);
            $.uniform.update();
        }else{
            $(".select_pages_search").prop('checked', $(this).prop("checked"), false);
            $.uniform.update();
        }
    });

    $('#select_all_posts').on('click',function(){

        if ($(this).is(':checked')) {

            $(".select_posts").prop('checked', $(this).prop("checked"), true);
            $.uniform.update();
        }else{
            $(".select_posts").prop('checked', $(this).prop("checked"), false);
            $.uniform.update();
        }
    });
    $('#select_all_posts_search').on('click',function(){

        if ($(this).is(':checked')) {

            $(".select_posts_search").prop('checked', $(this).prop("checked"), true);
            $.uniform.update();
        }else{
            $(".select_posts_search").prop('checked', $(this).prop("checked"), false);
            $.uniform.update();
        }
    });

    $('#select_all_categories').on('click',function(){

        if ($(this).is(':checked')) {

            $(".select_categories").prop('checked', $(this).prop("checked"), true);
            $.uniform.update();
        }else{
            $(".select_categories").prop('checked', $(this).prop("checked"), false);
            $.uniform.update();
        }
    });

    $('#select_all_categories_search').on('click',function(){

        if ($(this).is(':checked')) {

            $(".select_categories_search").prop('checked', $(this).prop("checked"), true);
            $.uniform.update();
        }else{
            $(".select_categories_search").prop('checked', $(this).prop("checked"), false);
            $.uniform.update();
        }
    });

    $('#del_menu').click(function (e) {

        e.preventDefault();
        e.stopPropagation();
        var a = $('#nestable-output').val();

        if(a == '[]'){
            alert('Select menu details before removing menu.');
            return false;
        }
        var menu_Id= jQuery("#mainmenu option:selected").val();
        var confirmId = confirm('Are you sure you want to delete this menu? "Cancel" to stop, "OK" to delete');
        if(confirmId == true) {

            // alert(menu_Id);
            $.ajax({
                url: '/menu/del_menu',
                data: {menu_id: menu_Id},
                type: 'POST',
                success: function (response) {

                    $('.alert-success').html('Menu delete successfully..');

                },
                error: function (response) {
                    alert('Error: There is something wrong while deleting menu.');
                }

            });
        }
    });
    
});

var updateOutput = function(e)
{
    var list   = e.length ? e : $(e.target),
        output = list.data('output');
   //  alert(list);
    if (window.JSON) {
        var a= window.JSON.stringify(list.nestable('serialize'));
      
        output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
    } else {
        output.val('JSON browser support required for this demo.');
    }
};