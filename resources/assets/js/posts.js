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
    },
    complete: function() {
        loader('hide');

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

$(function() {
    CKEDITOR.replace('post-editor');

    $(".addnewpostcategory").click(function(){
        $("#new-post-section").slideToggle();
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
        $.post("/ajax/save_post_category",{parent_category_id:parent_category_id,category_name:category_name}, function(response){
            inserted_category_id = response;

             if(parent_category_id==""){
                $(".all-post-categories").prepend('<li class="parent" data-parent-value="'+parent_category_id+'"><input name="categories[]" id="categories[]"   type="checkbox" value="'+inserted_category_id+'" checked> '+category_name+'</li>');

                 $("#parent-category").append('<option class="level" data-level="0" value="'+inserted_category_id+'">'+category_name+'</option>');
             
                 }
            else{

                    var space = (parseInt(level)+1)*2;
                    var str = "";
                    for(var i=1;i<=space;i+=2){
                    str+="&nbsp";
                }

                $('.all-post-categories li').each(function(){
                    if($(this).attr('data-parent-value')==parent_category_id){
                        $(this).after('<li style="padding-left:15px"  class="child" data-parent-value="'+category_name+'"><input  name="categories[]" id="categories[]" type="checkbox" value="'+inserted_category_id+'" checked> '+category_name+'</li>');
                    }
                });
             $("#parent-category option:selected").after('<option class="level" data-level="'+(parseInt(level)+1)+'" value="'+inserted_category_id+'">'+str+category_name+'</option>');
            }

             $("#add-new-category").val('');
            $("#parent-category option:first").attr('selected','selected');


            $.uniform.update();
        });

        $( "input:checkbox").uniform();

         e.preventDefault();
    });
    $("#ck").click(function(){
        // alert('ghjk');
        $(".checkboxes").prop('checked', $(this).prop("checked"), true);
        $.uniform.update();
    });

    $(".show-images-btn").click(function(e){

       $.ajax({
            url: '/media/show_image_library',
            data: {post:1},
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                 var l = window.location;
                var base_url = l.protocol + "//" + l.host;


                var str = '';
                $.each(response, function(i, v){
                    var id = v.id

                    str +=   '<label for="'+v.id+'"   id="label'+v.id+'" class="label-img">';
                    str +=    '<input type="checkbox" name="checkimg" id="'+v.id+'" class="checkimg" value="'+v.id+'" />';
                    str +=    '<div class="tile image" >';
                    str +=     '<div class="tile-body">';
                    str +=     '<img src= '+base_url+'/'+v.path+' alt="'+v.alt_text+'"  width = "100%"> ';
                    str +=     '</div>';
                    str +=     '<div class="tile-object">';
                    str +=     '<div class="name">'+v.alt_text+'</div>';
                    str +=     '</div>';
                    str +=     '</div></label>';

                        $('body').on('change', '#'+id, function () {
                            var $others = $('input[name="checkimg"]').not('#'+id);
                            if ($(this).prop('checked')) {

                                $others.prop('checked', false);
                               $('.label-img').removeClass('check_heighlight');

                                $('#label'+id).toggleClass('check_heighlight');
                                $.ajax({
                                    url: '/media/show_image_detail',
                                    data: {img_id:id},
                                    dataType: 'json',
                                    type: 'POST',
                                    success: function (response) {
                                        // console.log(response);
                                        var l = window.location;
                                        var base_url = l.protocol + "//" + l.host;

                                        var str = '<h4>'+response.alt_text+'</h4><hr>';
                                        str  += '<div class="row col-md-6">';
                                        str  +='<img src= '+base_url+'/'+response.path+' alt="'+response.alt_text+'" width = "100%"></div>';
                                        str  += '<div class="col-md-6">';
                                        str  += '<p class="top10">';
                                        str  += '<strong>'+response.alt_text+'</strong></p>';
                                        str  += '<p class="top3">';
                                        str  += '<a href="javascript:;">Image Editing</a></p>';
                                        str  += '<p class="top3 red">';
                                        str  += '<a href="javascript:;">Permenant delete</a></p></div><hr>';
                                        $(".detail-section").html(str);

                                        $('#path').val(response.path);
                                        $('#alt_text').val(response.alt_text);
                                        $('#description').val(response.description);

                                    },
                                    error: function (response) {
                                        alert('There is something wrong with ajax call.');
                                    }

                                });

                            }
                            else {

                                $('#label'+id).removeClass('check_heighlight');
                            }
                        });

                });
              $(".show_media_img").html(str);
             },
            error: function (response) {
                alert('There is something wrong with ajax call.');
            }

        });

    });

    $('.attach_image').click(function (e) {

        var parent_id = $('#post_id').val();
        var imageDetail_Id=  $('input[name="checkimg"]:checked').val();
        var old_image_id =  $('#old_image_id').val();

        if(parent_id == ''){
            alert('Post is requied before image saving.');
            return false;
        }
        if(imageDetail_Id == ''){
            alert('Select image from gallery.');
            return false;
        }
        $.ajax({
            url: '/media/save_images_for_posts',
            data: {parent_id: parent_id , image_detail:imageDetail_Id, old_image_id: old_image_id },
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                var l = window.location;
                var base_url = l.protocol + "//" + l.host;
                var str = '';
                str += ' <div class="display-image" >';
                str += '<img src= '+base_url+'/'+response.path+' alt="'+response.alt_text+'" width = "100%"> ';
                str += '  <a class="remove-images-btn" data-img-id='+response.id+'>Remove featured image</a></div>';
                str += '<input type="hidden" name="old_image_id" id="old_image_id" value="'+response.id+'"  >';
                $(".show-image-section").html(str);

                 },
            error: function (response) {
                alert('There is something wrong with ajax call.');
            }

        });





    });

    $(".search_image").click(function(e){
        var keyword      = $('.input-sm').val();
       // alert(keyword);

                $.ajax({
                    url: '/media/show_image_library',
                    data: {keyword: keyword },
                    dataType: 'json',
                    type: 'POST',
                    success: function (response) {
                        // console.log(response);
                        var l = window.location;
                        var base_url = l.protocol + "//" + l.host;


                        var str = '';
                        $.each(response, function(i, v){
                            var id = v.id

                            str +=   '<label for="'+v.id+'"   id="label'+v.id+'" class="label-img">';
                            str +=    '<input type="checkbox" name="checkimg" id="'+v.id+'" class="checkimg" value="'+v.id+'" />';
                            str +=    '<div class="tile image" >';
                            str +=     '<div class="tile-body">';
                            str +=     '<img src= '+base_url+'/'+v.path+' alt="'+v.alt_text+'"  width = "100%"> ';
                            str +=     '</div>';
                            str +=     '<div class="tile-object">';
                            str +=     '<div class="name">'+v.alt_text+'</div>';
                            str +=     '</div>';
                            str +=     '</div></label>';


                            $('body').on('change', '#'+id, function () {
                                var $others = $('input[name="checkimg"]').not('#'+id);
                                if ($(this).prop('checked')) {
                                    $others.prop('checked', false);
                                    $('.label-img').removeClass('check_heighlight');
                                    $('.label-img').removeClass('checkimg');
                                    $('#label'+id).toggleClass('check_heighlight');
                                    $.ajax({
                                        url: '/media/show_image_detail',
                                        data: {img_id:id},
                                        dataType: 'json',
                                        type: 'POST',
                                        success: function (response) {
                                            // console.log(response);
                                            var l = window.location;
                                            var base_url = l.protocol + "//" + l.host;

                                            var str = '<h4>'+response.alt_text+'</h4><hr>';
                                            str  += '<div class="row col-md-6">';
                                            str  +='<img src= '+base_url+'/'+response.path+' alt="'+response.alt_text+'" width = "100%"></div>';
                                            str  += '<div class="col-md-6">';
                                            str  += '<p class="top10">';
                                            str  += '<strong>'+response.alt_text+'</strong></p>';
                                            str  += '<p class="top3">';
                                            str  += '<a href="javascript:;">Image Editing</a></p>';
                                            str  += '<p class="top3 red">';
                                            str  += '<a href="javascript:;">Permenant delete</a></p></div><hr>';
                                            $(".detail-section").html(str);

                                            $('#path').val(response.path);
                                            $('#alt_text').val(response.alt_text);
                                            $('#description').val(response.description);

                                        },
                                        error: function (response) {
                                            alert('There is something wrong with ajax call.');
                                        }

                                    });

                                }
                                else {

                                    $('#label'+id).removeClass('check_heighlight');
                                }
                            });
                        });
                        $(".show_media_img").html(str);

                       

                    },
                    error: function (response) {
                        alert('There is something wrong with ajax call.');
                    }

                });

        e.preventDefault();
    });

    /*$(".remove-img .fa-remove").click(function(){

        //var id = $(this).val();
        var img_id = $(this).parent().attr("data-img-id");
        var parent_id = $('#post_id').val();
        
        $.ajax({
            url: '/media/remove_image_by_id',
            data: {img_id: img_id ,parent_id: parent_id},
            type: 'POST',
            success: function (response) {
                /!* alert('true');
                 console.log(response);

                $(".showremove"+$(this).attr('data-img-id')).remove();
                $(this).parent().remove();
               var l = window.location;
                var base_url = l.protocol + "//" + l.host;


                var str = '';
                $.each(response, function(i, v){

                    str +=   '<div class="tile image">';
                    str +=     '<div class="tile-body">';
                    str +=     '<img src= '+base_url+'/'+v.path+' alt="'+v.alt_text+'" width = "100%"> ';
                    str +=     '</div>';
                    str +=     '<div class="tile-object">';
                    str +=     '<div class="name">'+v.alt_text+'</div>';
                    str +=     '</div>';
                    str +=     '</div>';



                });
                $(".show_media_img").html(str);*!/



            },
            error: function (response) {
                alert('There is something wrong with ajax call.');
            }

        });
    });*/


    $('body').on('click', '.remove-images-btn', function () {
        //var id = $(this).val();
        var img_id = $(this).attr("data-img-id");
        var parent_id = $('#post_id').val();

        $.ajax({
            url: '/media/remove_image_by_id',
            data: {img_id: img_id ,parent_id: parent_id},
            type: 'POST',
            success: function (response) {


                // $(this).attr('data-img-id').remove();
                $(".display-image").remove();
                var str = '';
                str += '<input type="hidden" name="old_image_id" id="old_image_id" value=""  >';
                $(".show-image-section").html(str);

            },
            error: function (response) {
                alert('There is something wrong with ajax call.');
            }

        });

    });
    
    





    $(".available-tags").click(function(){
        $("#available-tags").toggle();
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
        $("#available-calender").toggle('slow');
    });

    $(".add-available-tag").click(function(){
        var tag_id = $(this).attr('data-tag-id');
        var post_id = $("#post_id").val();
        var ths = $(this);

            $(".tags").prepend('<li><a href="javascript:;" class="remove-tag" data-tag-id="'+tag_id+'"><i class="fa fa-remove"></i></a> '+ths.text()+' </li><input class="tag'+tag_id+'"  name="tags[]" id="tags[]" type="hidden" value="'+tag_id+'" >');

           // response = jQuery.parseJSON(response);
            //ths.remove();
            $(".remove-tag").click(function(){

                $( ".tag"+$(this).attr('data-tag-id')).remove();
                $(this).parent().remove();
            });
     });

    $(".addtag").click(function(e){

        var newTag = $("#new-tag").val();
         if(newTag==""){
            $("#new-tag").focus();
            return false;
        }

        $.post("/ajax/save_post_tag",{name:newTag}, function(response){

            $(".tags").prepend('<li><a href="javascript:;" data-tag-id="'+response+'" class="remove-tag"><i class="fa fa-remove"></i><input class="tag'+response+'"  name="tags[]" id="tags[]" type="hidden" value="'+response+'" ></a> '+newTag+' </li>');

            //response = jQuery.parseJSON(response);

            $(".remove-tag").click(function(){

                //var tag_id = $(this).attr('data-tag-id');
                //var result = delete_tag_with_product(tag_id, product_id);
                $(this).parent().remove();
            });

        });

         e.preventDefault();
    });

    $(".remove-tag").click(function(){

       $(this).parent().remove();
    });



    $("input[name=name]").focusout(function(e){
            var title      = $(this).val();
            var post_id     = $("#post_id").val();

         if(title==""){
            return false;
        }
        else {
             if (post_id == ''){
                 $.ajax({
                     url: '/posts/save_post_slug',
                     data: {title: title ,post_id:post_id},
                     type: 'POST',
                     success: function (response) {
                         $("#post-slug").html(response);

                         $("#perma-link-section").fadeIn();
                         $("#slug").val(response);

                     },
                     error: function (response) {
                         alert('There is something wrong with ajax call.');
                     }

                 });
              }
        }
        e.preventDefault();
    });


    $(".edit-slug").click(function(e){
        $("#post-slug").html('<input type="text" id="new-slug" value="'+$("#post-slug").html()+'" />  <span id="save-cancel-action-button"> <a href="javascript:;" class="save-new-slug">Save</a> | <a href="javascript:;" class="cancel-new-slug">Cancel</a></span>');
        $(this).hide();

        $(".save-new-slug").click(function(){
            var new_slug 			= $("#new-slug").val();
            var post_id 			= $("#post_id").val();
             $.post("/posts/save_post_slug",{title:new_slug,post_id:post_id }, function(response){

                $("#post-slug").html(response);
                $("save-cancel-action-button").remove();
                $("#slug").val(response);
            });

            $("#post-slug").html();
            $(".edit-slug").show();

        });
        $(".cancel-new-slug").click(function() {
            var new_slug 			= $("#new-slug").val();
            $("#post-slug").html(new_slug);
            $("save-cancel-action-button").remove();
            $("#slug").val(new_slug)
            $(".edit-slug").show();

        });



        e.preventDefault();
    });

    $(".edit-slug-with-id").click(function(e){
        $("#post-slug").html('<input type="text" id="new-slug-ById" value="'+$("#post-slug").html()+'" />  <span id="save-cancel-action-button"> <a href="javascript:;" class="edit-new-slug">Save</a> | <a href="javascript:;" class="cancel-new-slug">Cancel</a></span>');
        $(this).hide()

        $(".edit-new-slug").click(function(){
            var new_slug 			= $("#new-slug-ById").val();
            var post_id 			= $("#post_id").val();

            $.post("/posts/save_post_slug",{title:new_slug,post_id:post_id }, function(response){


                $("#post-slug").html(response);
                $("save-cancel-action-button").remove();
                $("#slug").val(response);
            });

           // $("#post-slug").html();
            $(".edit-slug-with-id").show();

        });
        $(".cancel-new-slug").click(function(){
            var new_slug 			= $("#new-slug-ById").val();
            $("#post-slug").html(new_slug);
            $("save-cancel-action-button").remove();
            $("#slug").val(new_slug)
            $(".edit-slug-with-id").show();

        });
        e.preventDefault();
    });




    $(document).on('mouseover', '.remove-img', function(){
        $(this).find('.fa-remove').show();
    });
    $(document).on('mouseout', '.remove-img', function(){
        $(this).find('.fa-remove').hide();

    });
});

