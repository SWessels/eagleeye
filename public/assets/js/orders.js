
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
 
    }

});

$(document).ready(function () {
    if($("#order_customer").length)
    {
        $("#order_customer").select2();
    }

    if($('#order_date_time').length)
    {
        $('#order_date_time').datetimepicker({
            format: "yyyy-mm-dd  hh:ii:ss",
            autoclose: true,
            minuteStep: 5
        });
    }


    if($('.shipping_form_action').length)
    {
        $('body').on('click', '.shipping_form_action', function () {
            $('.shipping_details_display').hide();
            $('.shipping_details_form').show();
            $('.shipping_form_action i').removeClass('fa-pencil');
            $('.shipping_form_action i').addClass('fa-user');
        });
    }


    if($('.billing_form_action').length)
    {
        $('body').on('click', '.billing_form_action', function () {
            $('.billing_details_display').hide();
            $('.billing_details_form').show();
            $('.billing_form_action i').removeClass('fa-pencil');
            $('.billing_form_action i').addClass('fa-user');
        });
    }

});


$('body').on('click', '.show_items', function () {
    var idstr   = $(this).attr('id');
    var id      = getId(idstr);
    $('#items_div_'+id).toggle(); 
});

// save order note
$('body').on('click', '#save_order_note', function () {
    var note = $('#order_note').val();
    var note_for = $('#order_note').val();
    var order_id = $('#order_id').val();

    $.ajax({
        url: "/orders/save_order_note",
        method: 'POST',
        dataType: 'json',
        data: { order_id:order_id, note:note, note_for:note_for },
    }).success(function (response) {
        if(response.action == true)
        {
            $('#order_note').val('');
            var note_str = '';
                note_str += '<li id="order_note_'+response.note_id+'" class="order_note">';
                note_str += '<div class="note_content">';
                note_str += '<p>'+response.note+'</p>';
                note_str += '</div>';
                note_str += '<p class="meta">';
                note_str += '<abbr class="exact-date" title="'+response.date+'">';
                note_str += response.date+' </abbr>';
                note_str += '<a  class="delete_order_note" id="delete_order_note_'+response.note_id+'">Delete Note</a>';
                note_str += '</p>';
                note_str += '</li>';
                $('.order_notes_ul').append(note_str);
        }else{
            alert(response.msg);
        }

    })
    
});
// end

// delete order note

$('body').on('click', '.delete_order_note', function () {
    var idstr       = $(this).attr('id');
    var note_id     = getId(idstr);
    $.ajax({
        url: "/orders/delete_order_note",
        method: 'POST',
        dataType: 'json',
        data: { note_id:note_id},
    }).success(function (response) {
        if (response.action == true) {
            $('#order_note_'+note_id).remove();
        }
    });
});

// end

$("#check-all-orders").click(function(){
    var check_uncheck =  $(this).prop("checked");
    $(".order-checkbox").each(function(){ $(this).prop('checked', check_uncheck, true);  });
    $.uniform.update();
});


$(function() {

    function cb(start, end) {
        if(start != '' && end != '')
        {
            $('#date-range-field').val(start.format('D/MM/YYYY') + ' - ' + end.format('D/MM/YYYY'));
        } 

    }
    cb('', '');

    $('#daterange').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

});

//$('body').on('focus', '#order_customer', function () {
$('select').on('select2:open', function () {

    //console.log($('#order_customer option').length);
    if($('#order_customer option').length <= 1) {

        $.ajax({
            url: "/ajax/get_customers",
            method: 'GET',
            dataType: 'json',
        }).success(function (response) {
            var dropdown_str = '';
            $.each(response.data, function (i, v) {
                dropdown_str += '<option value="' + v.id + '"> ' + v.username + ' #'+v.id+' '+v.email+'</option>';
                //select2_data.push({ id: 4, product_text: 'text_4' });
            }); 

            $('#order_customer').append(dropdown_str);
            $("#order_customer").select2("destroy").select2();
            $("#order_customer").select2('open');
        })
    }
    
});









/////////////////////  functions ////////////////////


function getId(str)
{
    if(str !== undefined)
    {
        return str.replace(/\D/g,'');
    }

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
