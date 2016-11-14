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
        //$( "input:checkbox").uniform();
    }

});

$(document).ready(function() {
  //  $("input:checkbox").uniform();


    $(".editpublish").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $("#available-status").toggle('slow');
    });
    $('.cancelpublish').click(function(){
        $('#available-status').toggle('slow');
    });

    $(".editcal").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $("#available-calender").toggle('slow');
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

    $("#coupon-form").validate({

        ignore: [],
        rules: {
            code:{ required: true },
            coupon_expiry_date:{ required: true },
            discount_type:{ required: true },
            coupon_amount:{ required: true , number: true},
            min_spend:{ required: true , number: true},
            max_spend:{ required: true , number: true},
        },
        tooltip_options: {
            code: { placement: 'top' },
            discount_type: { placement: 'top' },
            coupon_amount: { placement: 'top' },
        },
        invalidHandler: function(form, validator) {
            //console.log(validator.numberOfInvalids());
            if(validator.numberOfInvalids() > 0) {
                var error_list = validator.errorList;
               // console.log(error_list);

            }
            $(".error_div").html('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' + validator.numberOfInvalids() + ' error' + (validator.numberOfInvalids() > 1 ? 's' : '') + ' found.  please fill the fields properly </div>');
            },
        submitHandler: function(form) {

             form.submit();

        }
    });
    
    $(document).on('click', '#publish, #draft, #trash', function(){
        $('#form-action').val($(this).attr('id'));

        if( $('#form-action').val()!='')
        {
            $('#coupon-form').submit();
        }
    });
    $(".pselect2-multiple , .epselect2-multiple").select2({
        placeholder:"Select product",
        minimumInputLength: 3,
        delay: 250,
        multiple:true,
        ajax: {
            url: "/coupons/get_products",
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

    $(".cselect2-multiple , .ecselect2-multiple").select2({
        placeholder:"Select product",
        minimumInputLength: 3,
        delay: 250,
        multiple:true,
        ajax: {
            url: "/coupons/get_categories",
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
    
   });

function getId(str)
{
    if(str !== undefined)
    {
        return str.replace(/\D/g,'');
    }

}
