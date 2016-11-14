// JavaScript Document

$.ajaxSetup({
    headers:{
        "wssn-api-key": "Pn5UUtkGrC5toglSNe44qKVe3O9TgXbX",
        "wssn-client-id": "c17"
    },
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
     //   $( "input:checkbox").uniform();

        hidesaveButton();
        //hideUpdateButton();


    }

});

$( window ).load(function() {
    // generate josn file for all products.
    $.ajax({
        url: "https://api.wssn.nl/v2/stocks/e/99169004225",
        method: 'GET',
        dataType: 'json',
    });
});
