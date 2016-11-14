/**
 * Created by Maryam on 8/17/2016.
 */

$(document).ready(function() {
    
    var seo_title_count = 60;
    var seo_desc_count = 160;
    var seo_editor = null;
    seo_editor =   CKEDITOR.instances['seo_desc'];

    if($('#seo_desc').length > 0) {
        seo_desc = CKEDITOR.replace('seo_desc', {
            toolbar: [

                {
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Strike',  '-', 'RemoveFormat']
                },
                {
                    name: 'insert',
                    items: ['Image', 'Table', 'HorizontalRule' ,'SpecialChar']
                },
                {
                    name: 'clipboard',
                    groups: ['clipboard', 'undo'],
                    items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
                },
                {
                    name: 'editing',
                    groups: ['find', 'selection', 'spellchecker'],
                    items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt']
                }
            ]
        });
        seo_editor =   CKEDITOR.instances['seo_desc'];

        seo_editor.on( 'key', function( event ) {

            setTimeout(function(){

                var descText_length = (seo_editor.getData()).length;
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

            },10);

        });

    }


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
