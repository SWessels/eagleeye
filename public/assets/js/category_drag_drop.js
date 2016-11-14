
$(function() {
  	$('#left_reserve, #main_sortable, #right_reserve').sortable({
		connectWith: '.connected'
	});
}); 


function reloadWithID()
{
	var catID = $('#sotrable_category').val(); 
	if(catID == '')
		return false; 
	var crpath 	= window.location.href; 
	crpath 		= crpath.replace(/\d+$/, "");
	document.location.href= crpath + catID; 
}

function toggleLayoutClass()
{
	$('#main_sortable').toggleClass('layout_3_columns');
}


window.onbeforeunload = closingCode;
function closingCode(){
    
   return false;
}

 
 $(document).ready(function(){
 	
				var data_array = new Array();
				$("#main_sortable li").each(function(){ 
				        var item = {};
				        item['data-id'] = $(this).data('id');  
				        data_array.push(item);

				    });
				    var main_sortable = JSON.stringify(data_array);
				    $('#main_sorted_data').val(main_sortable);


				    var left_array = new Array();
					$("#left_reserve li").each(function(){ 
				        var item = {};
				        item['data-id'] = $(this).data('id');  
				        left_array.push(item);

				    });
				    var left_reserve_sortable = JSON.stringify(left_array);
				    $('#left_reserved_data').val(left_reserve_sortable);

				    var right_array = new Array();
					$("#right_reserve li").each(function(){ 
				        var item = {};
				        item['data-id'] = $(this).data('id');  
				        right_array.push(item);

				    });
				    var right_reserve_sortable = JSON.stringify(right_array);
				    $('#right_reserved_data').val(right_reserve_sortable);
 });