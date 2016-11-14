/**
 * Created by Maryam on 8/19/2016.
 */
$(document).ready(function() {
    $("input[name=name]").focusout(function (e) {

        var title = $(this).val();
        var con_title = $('#con_title').val();
        var link_t = title + ' | ' + con_title;

        if (title == "") {
            return false;
        }
        else {


        $('#link_title').html(title);

        $('#seo_title').val(link_t);
        $('#seo_desc').val(link_t);

        var seo_title_length = $('#name').val().length;
        var text_remaining = seo_title_count - seo_title_length;
        if (seo_title_length >= 0 && seo_title_length <= 60) {


            $('#title_count').html('you have <span id="num_count">' + text_remaining + ' characters</span>' + ' remaining.');
            $('#num_count').addClass('seo_title_green');
        }
        else if (seo_title_length > 60) {


            $('#title_count').html('you have <span id="num_count">' + text_remaining + ' characters</span>' + ' remaining.');
            $('#num_count').addClass('seo_title_red');
        }
    }
        e.preventDefault();
    });

    $("input[name=slug]").focusout(function (e) {

        var slug_1 = $(this).val();
        if (slug_1 == "") {
            var slug = '';
        }
        else {
            var slug = slug_1 + '/';
        }

             $("#link_slug").html(slug);
             var l = window.location;
             var base_url = l.protocol + "//" + l.host + '/post-category/' + slug;
             $('#can_url').val(base_url);


        e.preventDefault();
    });




        $('#desc').on( 'keyup', function(){
            if( $('#desc').val().length < 200){

                    var desc = $('#desc').val();
                    $('#example_desc').html(desc);
        }

        });
    var seo_title_count = 60;
    var seo_desc_count = 160;


    $('#seo_desc').on( 'keyup', function() {


        var descText_length = $('#seo_desc').val().length;
        var descText_remaining = seo_desc_count - descText_length;
        if(descText_length >= 0 && descText_length <= 160 ){

            $('#desc_count').html('you have <span id="dnum_count">'+descText_remaining +' characters</span>' + ' remaining.');
            $('#dnum_count').addClass('seo_title_green');
        }
        else if(descText_length > 60){

            $('#desc_count').html('you have <span id="dnum_count">'+descText_remaining +' characters</span>' + ' remaining.');
            $('#dnum_count').addClass('seo_title_red');

        }
        else{
            $('#desc_count').html('you have <span id="dnum_count">'+descText_remaining +' characters</span>' + ' remaining.');}


        });

    $('#seo_title').keyup(function() {
        var text_length = $('#seo_title').val().length;
        var text_remaining = seo_title_count - text_length;
        if(text_length >= 0 && text_length <= 60 ){

            $('#title_count').html('you have <span id="num_count">'+text_remaining +' characters</span>' + ' remaining.');
            $('#num_count').addClass('seo_title_green');
        }
        else if(text_length > 60){

            $('#title_count').html('you have <span id="num_count">'+text_remaining +' characters</span>' + ' remaining.');
            $('#num_count').addClass('seo_title_red');

        }
        else{
            $('#title_count').html('you have <span id="num_count">'+text_remaining +' characters</span>' + ' remaining.');}
    });

});
