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
    //Third solution
    var indexvalue=0;
    console.log(maxid);
    console.log(arr.length);


    var myVar = setInterval(function(){

        if(indexvalue<arr.length) {
            $.ajax({
                async:false,
                url: '/resizeoneimage/' + arr[indexvalue],
                type: 'GET',
                success: function (result, status) {
                    indexvalue = indexvalue + 1;
                    $('.resized_images_body').html(result);
                }
            });
        }
        else {
            $('.resized_images_body').html("<h2>Completed</h2> .");
            clearInterval(myVar);
        }
    }, 1000);
});


