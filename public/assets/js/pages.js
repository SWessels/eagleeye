$(function() {

    var editor = null;

    if($('#page-editor').length > 0) {
        CKEDITOR.replace('page-editor');
        editor =   CKEDITOR.instances['page-editor'];

        editor.on( 'key', function( event ) {

            setTimeout(function(){
                //alert( editor.getData() );
                if((editor.getData()).length < 200){
                    $('#example_desc').html(editor.getData());}

            },10);

        });
    }


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



    $("input[name=name]").focusout(function(e){
        var title      = $(this).val();
        var page_id     = $("#page_id").val();
        var con_title = $('#con_title').val();
        var link_t = title+ ' | '+ con_title;

        if(title==""){
            return false;
        }
        else {
            if (page_id == ''){
                $.ajax({
                    url: '/pages/get_page_slug',
                    data: {title: title,page_id:page_id},
                    type: 'POST',
                    success: function (response) {
                        $("#page-slug").html(response);

                        $("#perma-link-section").fadeIn();
                        $("#slug").val(response);

                        var slug = response+'/';
                        $("#link_slug").html(slug);
                        var l = window.location;
                        var base_url = l.protocol + "//" + l.host+'/page/'+response+'/';
                        $('#can_url').val(base_url);

                    },
                    error: function (response) {
                        alert('error');
                    }

                });
            }
            else{
                $.ajax({
                    url: '/pages/update_page_slug',
                    data: {title: title,page_id:page_id},
                    type: 'POST',
                    success: function (response) {
                        $("#page-slug").html(response);

                        $("#perma-link-section").fadeIn();
                        $("#slug").val(response);

                        var slug = response+'/';
                        $("#link_slug").html(slug);
                        var l = window.location;
                        var base_url = l.protocol + "//" + l.host+'/page/'+response+'/';
                        $('#can_url').val(base_url);

                    },
                    error: function (response) {
                        alert('error');
                    }

                });

            }
        }
        $('#link_title').html(title);

        $('#seo_title').val(link_t);
        CKEDITOR.instances['seo_desc'].setData(link_t);

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
        $("#page-slug").html('<input type="text" id="new-slug" value="'+$("#page-slug").html()+'" />  <span id="save-cancel-action-button"> <a href="javascript:;" class="save-new-slug">Save</a> | <a href="javascript:;" class="cancel-new-slug">Cancel</a></span>');
        $(this).hide();

        $(".save-new-slug").click(function(){
            var new_slug 			= $("#new-slug").val();
            var page_id     = $("#page_id").val();
            $.post("/pages/get_page_slug",{title:new_slug,page_id:page_id }, function(response){

                $("#page-slug").html(response);
                $("save-cancel-action-button").remove();
                $("#slug").val(response);
            });

            $("#page-slug").html();
            $(".edit-slug").show();

        });
        $(".cancel-new-slug").click(function() {
            var new_slug 			= $("#new-slug").val();
            $("#page-slug").html(new_slug);
            $("save-cancel-action-button").remove();
            $("#slug").val(new_slug)
            $(".edit-slug").show();

        });
        e.preventDefault();
    });

    $(".edit-slug-with-id").click(function(e){
        $("#page-slug").html('<input type="text" id="new-slug-ById" value="'+$("#page-slug").html()+'" />  <span id="save-cancel-action-button"> <a href="javascript:;" class="edit-new-slug">Save</a> | <a href="javascript:;" class="cancel-new-slug">Cancel</a></span>');
        $(this).hide()

        $(".edit-new-slug").click(function(){
            var new_slug 			= $("#new-slug-ById").val();
            var page_id 			= $("#page_id").val();

            $.post("/pages/get_page_slug",{title:new_slug,page_id:page_id }, function(response){


                $("#page-slug").html(response);
                $("save-cancel-action-button").remove();
                $("#slug").val(response);
            });

            // $("#post-slug").html();
            $(".edit-slug-with-id").show();

        });
        $(".cancel-new-slug").click(function(){
            var new_slug 			= $("#new-slug-ById").val();
            $("#page-slug").html(new_slug);
            $("save-cancel-action-button").remove();
            $("#slug").val(new_slug)
            $(".edit-slug-with-id").show();

        });
        e.preventDefault();
    });

});