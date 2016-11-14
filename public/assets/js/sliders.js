
$(document).ready(function() {


    
    $("#full").on('show.bs.modal', function(){
        // alert('here');
        // var image_type = $('#f').data("title");
        // alert(image_type);

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

///////////////////////////////////Show gallery///////////////////////////////////////////
    $(".show-images-btn").click(function(e) {

        var type = $(this).data('title');
        var slider_type = $(this).data('type');
        var id   = this.id;
        if (type == 'featured') {
            $('#image_type').val('featured');
        }
        else if (type == 'gallery'){
            $('#image_type').val('gallery');
        }
        else if (type == 'slider'){
            $('#image_type').val('slider');
            $('#slider_type').val(slider_type);
            $('#slider_id').val(id);
        }
    });


    //////////////////////////////////////////Attach image to post-product//////////////////////////

    $('.attach_image').click(function (e) {


        var image_type = $('#image_type').val();
        var slider_type = $('#slider_type').val();
        var id = $('#slider_id').val();
        var con = $('#connection').val();

        var path =  $('input[name="checkimg[]"]:checked').data('path');
        var imageDetail_Id=  $('input[name="checkimg[]"]:checked').val();
        var filename = path.replace(/^.*[\\\/]/, '');


        var l = window.location;
        var base_url = l.protocol + "//" + l.host;
        if($('.display-'+id).css("display") == "none"){

            $('.display-'+id).show();
        }
        if(slider_type == 'mobile'){
            $('#mob_imageId-'+id).val(imageDetail_Id);
            $('#mobpath-'+id).val(base_url+'/'+path);
            $('#mobimg-'+id).attr('src', l.protocol + "//" + l.host+'/'+path);
        }
        else if(slider_type == 'desktop'){
            $('#des_imageId-'+id).val(imageDetail_Id);
            $('#despath-'+id).val(base_url+'/'+path);
            $('#desimg-'+id).attr('src', l.protocol + "//" + l.host+'/'+path);
        }
        else if(slider_type == 'mobile_homepage'){
            $('#mobHome_imageId-'+id).val(imageDetail_Id);
            $('#mobHomepath-'+id).val(base_url+'/'+path);
            $('#mobHome_img-'+id).attr('src', l.protocol + "//" + l.host+'/'+path);
        }
        else if(slider_type == 'desktop_homepage'){
            $('#desHome_imageId-'+id).val(imageDetail_Id);
            $('#desHomepath-'+id).val(base_url+'/'+path);
            $('#desHome_img-'+id).attr('src', l.protocol + "//" + l.host+'/'+path);
        }


    });


    /////////////////////////////////////////////////////File upload/Save in DB/show Content//////////////
    $('body').on('change', '.up', function () {

        var a = $(this).val();
        if($(this).val() != ''){

            $('#fileupload').submit();
        }
    });
    $("#fileupload").submit(function (e) {
        e.preventDefault();
        //   $('#fileupload').submit();
        var formData = new FormData(this);

        var parent_id = $('#parent_id').val();
        var media_type = $('#media_id').val();
        $.ajax({
            url: '/media/save_images_to_db',
            data: formData,
            dataType: 'json',
            mimeType: "multipart/form-data",
            type: 'POST',
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                // console.log(response);

                var next = $('.nav-tabs li.active').removeClass('active').next('li');
                $('#tab_5_1').removeClass('active');
                next.addClass('active');
                $('#tab_5_2').addClass('active');

                var l = window.location;
                var base_url = l.protocol + "//" + l.host;
                var str = '';
                var str1 = '';

                $.each(response, function (i, v) {
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
                    str += '<img class="img_dimension" src= ' + base_url + '/' +newpath+ ' alt="' + v.alt_text + '"  width = "100%"> ';
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
                alert('Error uploading file.');
            }

        });


    });


    //////////////////////////////////////on check image show detail/////////////////////////////////////////////////////////////////////////////////////////////////////

    $('body').on('change', '.checkimg ', function () {

        var id = $(this).val();
        var image_type = $('#image_type').val();
        var $others = $('input[name="checkimg[]"]').not('#'+id);
        if ($(this).prop('checked')) {

            if(image_type == 'slider'){
           $others.prop('checked', false);}
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
                    //alert(filename_with_ext);
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
                    str  += '<div class="row">';
                    str  += '<div class="col-md-5">';
                    str  +='<img src= '+base_url+'/'+newpath+' alt="'+response.alt_text+'"></div>';
                    str  += '<div class="col-md-7">';
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

            $('#label'+id).removeClass('check_heighlight');
        }
    });


    $('body').on('click', '.label-img', function () {

        var id = $(this).find('input[name="checkimg[]"]').val();
        var image_type = $('#image_type').val();
        var $others = $('input[name="checkimg[]"]').not('#'+id);

        if($(this).find('input[name="checkimg[]"]').prop('checked')) {

            if(image_type == 'featured'){
                $others.prop('checked', false);}

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
                    //alert(filename_with_ext);
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
                    str  += '<div class="row">';
                    str  += '<div class="col-md-5">';
                    str  +='<img src= '+base_url+'/'+newpath+' alt="'+response.alt_text+'"></div>';
                    str  += '<div class="col-md-7">';
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

            $('#label'+id).removeClass('check_heighlight');
        }
    });
    /////////////////////////////////////Edit Title///////////////////////////////////////////////


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

    //////////////////////////////////Delete Image///////////////////////////////////////////

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

    $(".pselect2").select2({

        placeholder:"Select product",
        minimumInputLength: 3,
        delay: 250,
        ajax: {
            url: "/sliders/get_products",
            dataType: 'json',
            type: "POST",
            data: function (params) {
                return {
                    q: params.term, // search term

                };
            },
            processResults: function (data) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
              
                return {
                    results: data
                };
            },

        },
    });

    $(".pselect2").on("select2:select", function(e) {
        var id = $(this).attr("id");
        var product_id = $(this).val();

        $.post("/sliders/get_featured_image",{product_id:product_id},
            function(response){

                $("#product_img-"+id).attr("src",response);

            });
    });

});
