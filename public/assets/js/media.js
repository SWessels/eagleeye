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

    ///////////////////////////////////Show image detail///////////////////////////////////////////

    $(".show-images-btn").click(function(e) {

        var id = $(this).data('id');

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

                var str = '<h4>Image Details</h4>';
                str  += '<div class="col-md-9">';
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
                str  += '<a class="delete-image" data-id="'+response.id+'" href="javascript:;">Permenant delete</a></p></div><hr>';

                var str2 = '';
                str2 = '<img src= '+base_url+'/'+response.path+' alt="'+response.alt_text+'"  style="max-height:100%"  >';
                $(".detail-section").html(str);
                $(".show_media_img").html(str2);

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

    });


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
                $('#full').modal('hide')
                location.reload();


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


    /////////////////////////////////////////////////////File upload/Save in DB/show Content//////////////
    $('body').on('change', '.up', function () {

        var a = $(this).val();

        if($(this).val() != ''){

         $('#fileupload').submit();
        }
    });

    $("#fileupload").submit(function (e) {
        e.preventDefault();

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

                var l = window.location;
                var base_url = l.protocol + "//" + l.host;
                window.location = base_url+'/media';

            },
            error: function (response) {
                alert('There is something wrong while uploading file.');
            }

        });


    });





});