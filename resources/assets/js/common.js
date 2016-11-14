/**
 * Created by User on 4/22/2016.
 */


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

        //   $( "input:checkbox").uniform();

        //hidesaveButton();
        //hideUpdateButton();


    }

});
$(document).ready(function () {
    // loading spinner
    $('.page-spinner-bar').addClass('hide');

    $(".add_term").click(function(e){
        e.preventDefault();
        e.stopPropagation();
       // alert('asd');
        var att_id = $("#att_id").val();
        var name = $("#name").val();
        var slug = $("#slug").val();
        var desc = $("#desc").val();
       // alert(att_id);
        $.ajax({
            url: '/ajax/save_attribute_terms',
            data: {att_id:att_id ,name:name , slug:slug, desc:desc},
            type: 'POST',
            success: function(response){
                response = jQuery.parseJSON(response);
                //console.log(response);
               // $('#table_id tbody').append("<tr><td>" + data.column1 + "</td><td>" + data.column2 + "</td><td>" + data.column3 + "</td></tr>");
               $('#datatable_products').prepend('<tr ><span><td><input name="del[\''+response.id+'\']" id="del[\''+response.id+'\']" value='+response.id+' type="checkbox" ></td><td><a href="/terms/'+response.id+'/edit"> '+response.name+'</a></td><td>'+response.desc+'</td><td>'+response.slug+'</td></tr>')
                $( "input:checkbox").uniform();
            },
            error: function(response){
                alert('Error in ajax call.');
            }

        });

    });
    $("#ck").click(function(){
       // alert('ghjk');
        $(".checkboxes").prop('checked', $(this).prop("checked"), true);
         $.uniform.update();
    });
   /* $(document).on("click", "#apply", function(e) {
        bootbox.alert("Hello world!", function() {
            console.log("Alert Callback");
        });
    })*/;

    $('#postcat_delform').submit(function(e) {
        var currentForm = this;
        e.preventDefault();
        var rm = $("#remove").val();
       // alert(rm);
        if(rm != 'rm'){
        bootbox.alert("Kindly select remove option!", function() {

        });
        }
            else{
        //alert('asdf');
        bootbox.confirm("Are you sure you want to remove?", function(result) {
            if (result) {
                currentForm.submit();
            }

        });  }
    });
    $('#posttag_delform').submit(function(e) {
        var currentForm = this;
        e.preventDefault();
        var rm = $("#remove").val();
        // alert(rm);
        if(rm != 'rm'){
            bootbox.alert("Kindly select remove option!", function() {

            });
        }
        else{
            //alert('asdf');
            bootbox.confirm("Are you sure you want to remove?", function(result) {
                if (result) {
                    currentForm.submit();
                }

            });  }
    });




});

var logarea = document.getElementById("logarea");
function log(text)
{
    logarea.innerHTML = text;
}


var sorter = $('#datatable_products').rowSorter({
    onDragStart: function(tbody, row, index)
    {
        // alert('gona drag');

    },
    onDrop: function(tbody, row, new_index, old_index)
    {
       // alert('dropped');

        $('.datarows').each(function(index, value) {
            
            $(this).attr('data-index', index );
            var dataid = $(this).attr('data-id');
            var dataindex = $(this).attr('data-index');

            //alert(dataid);
            // alert(dataindex);
            //  var id_array = [];
            //   id_array.push(dataid);
            //  alert(id_array);
            $.ajax({
                url: '/ajax/save_term_index',
                data: {id:dataid ,indexid:dataindex},
                type: 'POST',
                success: function(response){
                   // response = jQuery.parseJSON(response);
                  //  console.log(response);
                 },
                error: function(response){
                    alert('error');
                }

            });
            
         });


    
    
    }
       
});

function destroyRowSorter()
{
    sorter.destroy();
}


function  processCompositeWaitList(product_id){

    if(product_id) {

        $.ajax({
            url: 'waitlist/process_waitlist_composite',
            data: {product_id: product_id},
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.action  == true ) {

                    $('.wait_list_td_'+product_id).html('<span  class="alert alert-success waitlist_process_done" id="wait_list_msg_'+product_id+'">Process Complete</span>');
                    $('#wait_list_msg_'+product_id).show();
                    setTimeout(function () {
                        $('#wait_list_msg_'+product_id).hide();
                        $('.wait_list_td_'+product_id).html('0');
                    }, 3000);

                } else if (response.action  ==  false ) {
                    $('#wait_list_msg_'+product_id).html(response['message']).show();
                } else {
                    alert('Error occurred');
                }

            }

        });
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