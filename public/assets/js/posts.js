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
       // $( "input:checkbox").uniform();
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
  //  $( "input:checkbox").uniform();
    var editor = null;

    if($('#post-editor').length > 0) {
        CKEDITOR.replace('post-editor');
        editor =   CKEDITOR.instances['post-editor'];

        editor.on( 'key', function( event ) {

            setTimeout(function(){
                //alert( editor.getData() );
                if((editor.getData()).length < 200){
                    $('#example_desc').html(editor.getData());}

            },10);

        });
    }


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
                $(".all-post-categories").prepend('<li class="parent" data-parent-value="'+parent_category_id+'"><input name="categories[]" id="categories[]"  class="custom"  type="checkbox" value="'+inserted_category_id+'" checked> '+category_name+'</li>');

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
                        $(this).after('<li style="padding-left:15px"  class="child" data-parent-value="'+category_name+'"><input  name="categories[]" class="custom" id="categories[]" type="checkbox" value="'+inserted_category_id+'" checked> '+category_name+'</li>');
                    }
                });
             $("#parent-category option:selected").after('<option class="level" data-level="'+(parseInt(level)+1)+'" value="'+inserted_category_id+'">'+str+category_name+'</option>');
            }

             $("#add-new-category").val('');
            $("#parent-category option:first").attr('selected','selected');


          //  $.uniform.update();
        });

       // $( "input:checkbox").uniform();

         e.preventDefault();
    });
    $("#ck").click(function(){
        // alert('ghjk');
        $(".checkboxes").prop('checked', $(this).prop("checked"), true);
        $.uniform.update();
    });

///////////////////////////////////Show gallery///////////////////////////////////////////
    $(".show-images-btn").click(function(e) {

        var skip = 0;
        var type = $(this).data('title');

        if (type == 'featured') {
            $('#image_type').val('featured');
        }
        else if (type == 'gallery') {
            $('#image_type').val('gallery');
        }
    });

/*    $(".show-images-btn").click(function(e){
        var skip = 0;
        var type = $(this).data('title');
        
        if (type == 'featured') {
            $('#image_type').val('featured');
        }
        else if (type == 'gallery'){
            $('#image_type').val('gallery');
        }
       $.ajax({
            url: '/media/show_image_library',
            data: {post:1},
            dataType: 'json',
            type: 'POST',
            success: function (response) {
               ;
                 var l = window.location;
                var base_url = l.protocol + "//" + l.host;


                var str = '';
                $.each(response, function(i, v){
                    skip++ ;
                    var id = v.id
                    var filename_with_ext = v.path.replace(/^.*[\\\/]/, '');

                    var f = filename_with_ext.split('.');
                    var filename = f[0];
                    var extension      = f[1];

                    var newfilename = filename+'-120x184';

                    ///////////////////////////Path split/////////////////////
                    var p = v.path.split('/');
                    var uploadfolder = p[0];
                    var domainfolder = p[1];
                    var yearfolder = p[2];
                    var monthfolder = p[3];
                    var newpath = uploadfolder+'/'+domainfolder+'/'+yearfolder+'/'+monthfolder+'/'+newfilename+'.'+extension;

                    str +=   '<label for="'+v.id+'"   id="label'+v.id+'" class="label-img">';
                    str +=    '<input type="checkbox" name="checkimg[]" id="'+v.id+'" class="checkimg" value="'+v.id+'" />';
                    str +=    '<div class="tile image" >';
                    str +=     '<div class="tile-body">';
                    str +=     '<div class="">';
                    str +=     '<img class="img_dimension" src= '+base_url+'/'+newpath+' alt="'+v.alt_text+'"  > ';
                    str +=     '</div>';
                    str +=     '</div>';
                    str +=     '<div class="tile-object">';
                    str +=     '<div class="name"></div>';
                    str +=     '</div>';
                    str +=     '</div></label>';



                });

                //str += '<a href="/media/show_image_library?skip='+skip+'">next page</a>'
                    $(".show_media_img").html(str);

                $('.scroll').jscroll({
                    autoTrigger: false
                });
             },
            error: function (response) {
                alert('There is something wrong while showing library.');
            }

        });

    });*/




    //////////////////////////////////////////Attach image to post-product//////////////////////////

    $('.attach_image').click(function (e) {


        var parent_id = $('#parent_id').val();
        var media_type = $('#media_type').val();
        var image_type = $('#image_type').val();

        var imageDetail_Id=  $('input[name="checkimg[]"]:checked').val();

        
        var imageDetail_length=  $('input[name="checkimg[]"]:checked').length;

        var old_image_id =  $('#old_image_id').val();

        if(parent_id == ''){
            alert('Save post against this feature image.');
            return false;
        }
        if(imageDetail_Id == ''){
            alert('Select image from gallery.');
            return false;
        }
        if(image_type == 'featured'){
        if(imageDetail_length > 1){
            alert('Select only one feature image.');
            return false;
        }
        $.ajax({
            url: '/media/save_images',
            data: {parent_id: parent_id , image_detail:imageDetail_Id, old_image_id: old_image_id,media_type: media_type ,image_type:image_type },
            dataType: 'json',
            type: 'POST',
            success: function (response) {

                var l = window.location;
                var base_url = l.protocol + "//" + l.host;
                var str = '';
                str += ' <div class="display-image" >';
                str += '<img src= '+base_url+'/'+response.path+' alt="'+response.alt_text+'" width = "186px" height="285px"> ';
                str += '  <a class="remove-images-btn" data-img-id='+response.id+'>Remove featured image</a></div>';
                str += '<input type="hidden" name="old_image_id" id="old_image_id" value="'+response.id+'"  >';
                $(".show-image-section").html(str);

                 },
            error: function (response) {
                alert('There is something wrong with displaying feature image.');
            }

        });
    }
   });

   ////////////////////////////////////////////Search image from gallery//////////////////////////////////////////////
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
                            var filename_with_ext = v.path.replace(/^.*[\\\/]/, '');

                            var f = filename_with_ext.split('.');
                            var filename = f[0];
                            var extension      = f[1];

                            var newfilename = filename+'-120x184';

                            ///////////////////////////Path split/////////////////////
                            var p = v.path.split('/');
                            var uploadfolder = p[0];
                            var domainfolder = p[1];
                            var yearfolder = p[2];
                            var monthfolder = p[3];
                            var newpath = uploadfolder+'/'+domainfolder+'/'+yearfolder+'/'+monthfolder+'/'+newfilename+'.'+extension;

                            str +=   '<label for="'+v.id+'"   id="label'+v.id+'" class="label-img">';
                            str +=    '<input type="checkbox" name="checkimg[]" id="'+v.id+'" class="checkimg custom" value="'+v.id+'" />';
                            str +=    '<div class="tile image" >';
                            str +=     '<div class="tile-body">';
                            str +=     '<img src= '+base_url+'/'+newpath+' alt="'+v.alt_text+'"  width = "100%"> ';
                            str +=     '</div>';
                            str +=     '<div class="tile-object">';
                            str +=     '<div class="name">'+v.alt_text+'</div>';
                            str +=     '</div>';
                            str +=     '</div></label>';
                      });
                        $(".show_media_img").html(str);
                      },
                    error: function (response) {
                        alert('There is something wrong with ajax call.');
                    }

                });

        e.preventDefault();
    });

    /////////////////////////////////////////////////////File upload/Save in DB/show Content//////////////
    $('body').on('change', '.up', function () {

        var a = $(this).val();
        if($(this).val() != ''){

            $('#fileupload').submit();
        }
    });

/////////////////////////////////////////////////////File upload/Save in DB/show Content//////////////
    $("#fileupload").submit(function(e){
        e.preventDefault();

        var formData = new FormData(this);
        var parent_id = $('#parent_id').val();
        var media_type = $('#media_id').val();
        $.ajax({
            url: '/media/save_images_to_db',
            data: formData,
            dataType: 'json',
            mimeType:"multipart/form-data",
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
           //    console.log(response);
          //      return false;
                var next = $('.nav-tabs li.active').removeClass('active').next('li');
                $('#tab_5_1').removeClass('active');
                next.addClass('active');
                $('#tab_5_2').addClass('active');

                    var l = window.location;
                    var base_url = l.protocol + "//" + l.host;
                    var str = '';
                    var str1 = '';

                $.each(response, function(i, v){
                    var id = v.id
                    var datepart = v.uploaded_on.split('-');
                    var year =datepart[0];
                    var month = datepart[1];
                    var day  = datepart[2];
                    var month_index = month-1;
                    getMonthName = function (v) {
                        var n = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        return n[v]
                    }
                    var month_name = (getMonthName(month_index));
                    var filename_with_ext = v.path.replace(/^.*[\\\/]/, '');

                    var f = filename_with_ext.split('.');
                    var filename = f[0];
                    var extension      = f[1];

                    var newfilename = filename+'-120x184';

                    /*var newfilename = filename;*/
                    ///////////////////////////Path split/////////////////////
                    var p = v.path.split('/');
                    var uploadfolder = p[0];
                    var domainfolder = p[1];
                    var yearfolder = p[2];
                    var monthfolder = p[3];
                    var newpath = uploadfolder+'/'+domainfolder+'/'+yearfolder+'/'+monthfolder+'/'+newfilename+'.'+extension;

                    str += '<label for="' + v.id + '"   id="label' + v.id + '" class="label-img">';
                    str += '<input type="checkbox" name="checkimg[]" checked id="' + v.id + '" class="checkimg custom" value="' + v.id + '" />';
                    str += '<div class="tile image imagesize" >';
                    str += '<div class="tile-body">';
                    str +=  '<div class="">';
                    str += '<img class="img_dimension" src= ' + base_url + '/' +newpath+ ' alt="' + v.alt_text + '" > ';
                    str += '</div>';
                    str += '</div>';
                    str += '<div class="tile-object">';
                    str += '<div class="name"></div>';
                    str += '</div>';
                    str += '</div></label>';


                    str1 = '<h4>Image Details</h4>';
                    str1  += '<div class="row">';
                    str1  += '<div class="col-md-5">';
                    str1  +='<img src= '+base_url+'/'+newpath+' alt="'+v.alt_text+'"></div>';
                    str1  += '<div class="col-md-7">';
                    str1  += '<p class="top3">';
                    str1  += '<strong id="strong-title">File Name: </strong>' +v.title+'.'+v.type+'</p>';
                    str1  += '<p class="top3">';
                    str1  += '<strong id="strong-title">File Type: </strong> Image/' +v.type+'</p>';
                    str1  += '<p class="top3">';
                    str1  += '<strong id="strong-title">Uploaded On: </strong>' +month_name+' '+day+', '+year+'</p>';
                    str1  += '<p class="top3">';
                    str1  += '<strong id="strong-title">File Size: </strong>' +v.size+'</p>';
                    str1  += '<p class="top3">';
                    str1  += '<strong id="strong-title">Dimensions: </strong>' +v.image_dimension+'</p>';
                    str1 += '<p class="top3 red">';
                    str1 += '<a href="javascript:;">Permenant delete</a></p></div><hr>';


                    $('#path').val(v.path);
                    $('#alt_text').val(v.alt_text);
                    $('#title').val(v.title);
                    $("#save_title").attr("data-id",v.id);
                    $('#uploaded_by').val(v['user'].username);
                });
                //$(".show_media_img").prepend(str);
                var scope = angular.element("#searchtrigger").scope();
                scope.change();
                $(".detail-section").html(str1);

            },
            error: function (response) {
                alert('There is something wrong with ajax call.');
            }

        });


    });



    //////////////////////////////////////Show checked image detail////////////////////////////
    $('body').on('change', '.checkimg', function () {

     
        var id = $(this).val();

        var $others = $('input[name="checkimg[]"]').not('#'+id);
        if ($(this).prop('checked')) {

            $others.prop('checked', false);
            $('.label-img').removeClass('check_heighlight');

            $('#'+id).toggleClass('check_heighlight');
            $.ajax({
                url: '/media/show_image_detail',
                data: {img_id:id},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    // console.log(response);
                    var l = window.location;
                    var base_url = l.protocol + "//" + l.host;
                    var datepart = response.uploaded_on.split('-');
                    var year =datepart[0];
                    var month = datepart[1];
                    var day  = datepart[2];
                    var month_index = month-1;
                    getMonthName = function (v) {
                        var n = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        return n[v]
                    }
                    var month_name = (getMonthName(month_index));
                    var filename_with_ext = response.path.replace(/^.*[\\\/]/, '');

                    var f = filename_with_ext.split('.');
                    var filename = f[0];
                    var extension      = f[1];

                    var newfilename = filename+'-120x184';
                    ///////////////////////////Path split/////////////////////
                    var p = response.path.split('/');
                    var uploadfolder = p[0];
                    var domainfolder = p[1];
                    var yearfolder = p[2];
                    var monthfolder = p[3];
                    var newpath = uploadfolder+'/'+domainfolder+'/'+yearfolder+'/'+monthfolder+'/'+newfilename+'.'+extension;
                    var str = '<h4>Image Details</h4>';
                    str  += '<div class="row col-md-12">';
                    str  += '<div class="col-md-4">';
                    str  +='<img class="img_dimension" src= '+base_url+'/'+newpath+' alt="'+response.alt_text+'"  width = "100%" ></div>';
                    str  += '<div class="col-md-8">';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">File Name: </strong>' +response.title+'.'+response.type+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">File Type: </strong> Image/' +response.type+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">Uploaded On: </strong>' +month_name+' '+day+', '+year+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">File Size: </strong>' +response.size+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">Dimensions: </strong>' +response.image_dimension+'</p>';
                    str  += '<p class="top3 red">';
                    str  += '<a class="delete-image" data-id="'+response.id+'" href="javascript:;">Permenant delete</a></p></div></div>';
                    $(".detail-section").html(str);

                    $('#path').val(response.path);
                    $('#alt_text').val(response.alt_text);
                    $('#title').val(response.title);
                    $("#save_title").attr("data-id",response.id);
                    $('#description').val(response.description);
                    $('#uploaded_by').val(response['user'].username);
                },
                error: function (response) {
                    alert('There is something wrong while displaying image detail.');
                }

            });

        }
        else {

            $('#'+id).removeClass('check_heighlight');
        }
    });

    $('body').on('click', '.label-img', function () {


        var id = $(this).find('input[name="checkimg[]"]').val();

        var $others = $('input[name="checkimg[]"]').not('#'+id);
        $(this).find('input[name="checkimg[]"]').prop('checked', true);
        if ($(this).find('input[name="checkimg[]"]').prop('checked')) {

            $others.prop('checked', false);
            $('.label-img').removeClass('check_heighlight');

            $('#'+id).toggleClass('check_heighlight');
            $.ajax({
                url: '/media/show_image_detail',
                data: {img_id:id},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    // console.log(response);
                    var l = window.location;
                    var base_url = l.protocol + "//" + l.host;
                    var datepart = response.uploaded_on.split('-');
                    var year =datepart[0];
                    var month = datepart[1];
                    var day  = datepart[2];
                    var month_index = month-1;
                    getMonthName = function (v) {
                        var n = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                        return n[v]
                    }
                    var month_name = (getMonthName(month_index));
                    var filename_with_ext = response.path.replace(/^.*[\\\/]/, '');

                    var f = filename_with_ext.split('.');
                    var filename = f[0];
                    var extension      = f[1];

                    var newfilename = filename+'-120x184';
                    ///////////////////////////Path split/////////////////////
                    var p = response.path.split('/');
                    var uploadfolder = p[0];
                    var domainfolder = p[1];
                    var yearfolder = p[2];
                    var monthfolder = p[3];
                    var newpath = uploadfolder+'/'+domainfolder+'/'+yearfolder+'/'+monthfolder+'/'+newfilename+'.'+extension;
                    var str = '<h4>Image Details</h4>';
                    str  += '<div class="row col-md-12">';
                    str  += '<div class="col-md-4">';
                    str  +='<img class="img_dimension" src= '+base_url+'/'+newpath+' alt="'+response.alt_text+'"  width = "100%" ></div>';
                    str  += '<div class="col-md-8">';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">File Name: </strong>' +response.title+'.'+response.type+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">File Type: </strong> Image/' +response.type+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">Uploaded On: </strong>' +month_name+' '+day+', '+year+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">File Size: </strong>' +response.size+'</p>';
                    str  += '<p class="top3">';
                    str  += '<strong id="strong-title">Dimensions: </strong>' +response.image_dimension+'</p>';
                    str  += '<p class="top3 red">';
                    str  += '<a class="delete-image" data-id="'+response.id+'" href="javascript:;">Permenant delete</a></p></div></div>';
                    $(".detail-section").html(str);

                    $('#path').val(response.path);
                    $('#alt_text').val(response.alt_text);
                    $('#title').val(response.title);
                    $("#save_title").attr("data-id",response.id);
                    $('#description').val(response.description);
                    $('#uploaded_by').val(response['user'].username);
                },
                error: function (response) {
                    alert('There is something wrong while displaying image detail.');
                }

            });

        }
        else {

            $('#'+id).removeClass('check_heighlight');
        }
    });
    ////////////////////Edit Title///////////////////////////


    $('body').on('click', '.save_title', function () {
        var image_id			= $('#save_title').data('id');
        var new_title			= $("#title").val();
        var new_alt_text		= $("#alt_text").val();

        $.post("/media/update_image_title",{title:new_title, alt_text:new_alt_text, image_id:image_id },
            function(response){

                $("#changes_save").html(' Successfully saved..');
                $("#title").val(response['new_title']);
                $("#alt_text").val(response['new_alt_text']);
            });



    });

    ///////////////Delete Image///////////////////////////////

    $('body').on('click', '.delete-image', function () {
        var image_id = $(this).data('id');

        var confirmId = confirm('Are you sure you want to remove? This action will also delete images attached with post/product');
        if(confirmId == true){
            $.ajax({
                url: '/media/delete_image',
                data: {image_id:image_id},
                dataType: 'json',
                type: 'POST',
                success: function (response) {
                    $('#label'+image_id).remove();
                   
                },
                error: function (response) {
                    alert('There is something wrong while displaying image detail.');
                }

            });
        }
        else{
            return false;
        }
    });

    ///////////////Remove featured image /////////////

 $('body').on('click', '.remove-images-btn', function () {
        //var id = $(this).val();
        var img_id = $(this).attr("data-img-id");
        var parent_id = $('#post_id').val();

        $.ajax({
            url: '/media/remove_image_by_PostId',
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
                alert('There is something wrong while removing feature image.');
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
        show_image_detail


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
    var seo_title_count = 60;
    var seo_desc_count = 160;

    $("input[name=name]").focusout(function(e){
            var title      = $(this).val();
            var post_id     = $("#post_id").val();
            var con_title = $('#con_title').val();
            var link_t = title+ ' | '+ con_title;

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

                         var slug = response+'/';
                         $("#link_slug").html(slug);
                         var l = window.location;
                         var base_url = l.protocol + "//" + l.host+'/post/'+response+'/';
                         $('#can_url').val(base_url);

                     },
                     error: function (response) {
                         alert('There is something erong while fetching data.');
                     }

                 });
              }
             else{
                 $.ajax({
                     url: '/posts/update_post_slug',
                     data: {title: title ,post_id:post_id},
                     type: 'POST',
                     success: function (response) {
                         $("#post-slug").html(response);

                         $("#perma-link-section").fadeIn();
                         $("#slug").val(response);

                         var slug = response+'/';
                         $("#link_slug").html(slug);
                         var l = window.location;
                         var base_url = l.protocol + "//" + l.host+'/post/'+response+'/';
                         $('#can_url').val(base_url);

                     },
                     error: function (response) {
                         alert('There is something erong while fetching data.');
                     }

                 });
             }
        }

        $('#link_title').html(title);

        $('#seo_title').val(link_t);
        CKEDITOR.instances['seo_desc'].setData(link_t)

        var seo_title_length = $('#name').val().length;
        var text_remaining = seo_title_count - seo_title_length;
        if(seo_title_length >= 0 && seo_title_length <= 60 ){


            $('#title_count').html('you have <span id="num_count">'+text_remaining +' characters</span>' + ' remaining.');
            $('#num_count').addClass('seo_title_green');
        }
        else if (seo_title_length > 60){


            $('#title_count').html('you have <span id="num_count">'+text_remaining +' characters</span>' + ' remaining.');
            $('#num_count').addClass('seo_title_red');
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
    ///////////////////////////////////////on hover hide/show /////////////////////
  $(document).on('mouseover', '.remove-img', function(){
        $(this).find('.fa-remove').show();
    });
    $(document).on('mouseout', '.remove-img', function(){
        $(this).find('.fa-remove').hide();

    });
    $(document).on('click', '#publish, #draft, #trash', function(){
         $('#form-action').val($(this).attr('id'));

        if( $('#form-action').val()!='')
        {
            $('#post-form').submit();
        }
    });

   


 });
 ///////////////////////////////////////Display media type and parent_id/////////////////////
$(document).ready(function() {
   // $( "input:checkbox").uniform();
    $("#full").on('show.bs.modal', function(){
        

       if($('#post_id').length  > 0){

             var postId =  $('#post_id').val();

            $('#media_type').val('post');
            $('#parent_id').val(postId);
        }
        if($('#product_id').length  > 0){
            var productId =  $('#product_id').val();
            $('#media_type').val('product');
            $('#parent_id').val(productId);
        }   });




});

