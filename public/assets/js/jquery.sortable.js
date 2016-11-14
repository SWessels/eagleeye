/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.ir/projects/html5sortable
 * 
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
(function($) {
var dragging, placeholders = $();
$.fn.sortable = function(options) {
	var method = String(options);
	options = $.extend({
		connectWith: false
	}, options);
	return this.each(function() {
		if (/^enable|disable|destroy$/.test(method)) {
			var items = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
			if (method == 'destroy') {
				items.add(this).removeData('connectWith items')
					.off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
			}
			return;
		}
		var isHandle, index, items = $(this).children(options.items);
		var placeholder = $('<' + (/^ul|ol$/i.test(this.tagName) ? 'li' : 'div') + ' class="sortable-placeholder">');
		items.find(options.handle).mousedown(function() {
			isHandle = true;
		}).mouseup(function() {
			isHandle = false;
		});
		$(this).data('items', options.items)
		placeholders = placeholders.add(placeholder);
		if (options.connectWith) {
			$(options.connectWith).add(this).data('connectWith', options.connectWith);
		}
		items.attr('draggable', 'true').on('dragstart.h5s', function(e) {
			if (options.handle && !isHandle) {
				return false;
			}
			isHandle = false;
			var dt = e.originalEvent.dataTransfer;
			dt.effectAllowed = 'move';
			dt.setData('Text', 'dummy');
			index = (dragging = $(this)).addClass('sortable-dragging').index();
		}).on('dragend.h5s', function() {
			if (!dragging) {
				return;
			}
			dragging.removeClass('sortable-dragging').show();
			placeholders.detach();
			if (index != dragging.index()) {
				dragging.parent().trigger('sortupdate', {item: dragging});
			}
			dragging = null;
		}).not('a[href], img').on('selectstart.h5s', function() {
			this.dragDrop && this.dragDrop();
			return false;
		}).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function(e) {
			if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
				return true;
			}
			if (e.type == 'drop') {
				e.stopPropagation();
				placeholders.filter(':visible').after(dragging);
				dragging.trigger('dragend.h5s');


				var data_array = new Array();
				$("#main_sortable li").each(function(){ 
				        var item = {};
				        item['pid'] = $(this).data('id');  
				        data_array.push(item);

				    });
				    var main_sortable = JSON.stringify(data_array);
				    $('#main_sorted_data').val(main_sortable);


				    var left_array = new Array();
					$("#left_reserve li").each(function(){ 
				        var item = {};
				        if($(this).data('id') != undefined)
				        {
				        	item['pid'] = $(this).data('id');  	
				        	left_array.push(item);
				        }

				    });
				    var left_reserve_sortable = JSON.stringify(left_array);
				    $('#left_reserved_data').val(left_reserve_sortable);

				    var right_array = new Array();
					$("#right_reserve li").each(function(){ 
				        var item = {};
				        if($(this).data('id') != undefined)
				        {
				        	item['pid'] = $(this).data('id');  	
				        	right_array.push(item);
				        }

				    });
				    var right_reserve_sortable = JSON.stringify(right_array);
				    $('#right_reserved_data').val(right_reserve_sortable);

				    $('#is_changed').val(1); 

				return false;
			}
			e.preventDefault();
			e.originalEvent.dataTransfer.dropEffect = 'move';
			if (items.is(this)) {
				if (options.forcePlaceholderSize) {
					placeholder.height(dragging.outerHeight());
				}
				dragging.hide();
				$(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
				placeholders.not(placeholder).detach();
			} else if (!placeholders.is(this) && !$(this).children(options.items).length) {
				placeholders.detach();
				$(this).append(placeholder);
			}
			return false;
		});
	});
};
})(jQuery);


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
	console.log(crpath);
	crpath 		= crpath.replace(/\d+$/, "");
	crpath		= crpath.replace(/\/$/, "");
	console.log(crpath);
	document.location.href= crpath  + '/' + catID;  
}

function addClass(id)
{ 
	$('#main_sortable').addClass(id);	 
}


function removeClass(id)
{ 
	$('#main_sortable').removeClass(id);	 
}
 

window.onbeforeunload = function (e) {
	var is_changed = $('#is_changed').val(); 
    if(is_changed == 1)
        return "";
}

function draftSortedData(status){

	 var leftReserve 	= $('#left_reserved_data').val();
 	 var rightReserve 	= $('#right_reserved_data').val();
 	 if(leftReserve != '[]' && rightReserve !='[]')
 	 {
	 	 alert('There are products remaining in left and right reserve area');
 	 	 return false;	
 	 }
 	 else if(leftReserve != '[]')
 	 {
 	 	alert('There are products remaining in left reserve area');
 	 	 return false;
	 }else if(rightReserve !='[]'){
		alert('There are products remaining in right reserve area');
 	 	 return false;
	 }   


	  $.ajax({
            url: '/savedraftcategoryproducts',
            data: {status: status, left_reserve:$('#left_reserved_data').val() ,right_reserve:$('#right_reserved_data').val() , main_sorted_data:$('#main_sorted_data').val(), cat:$('#sotrable_category').val()},
            type: 'POST',
            success: function(response){
                response = jQuery.parseJSON(response);
                if(response.response !== false)
                {
					if(status == 'draft') 
	                {
		               $('#msg_area').html('Saved as draft!').addClass('alert alert-success'); 
		               $('#is_changed').val(0);  
		           	}else{
		    	       	$('#msg_area').html('Published').addClass('alert alert-success'); 
	    	            $('#is_changed').val(0); 	
		           	}
                }else{
                		alert(response.error);	
                }  
                	
               
               
            },
            error: function(response){
                alert('Error in ajax call.');
            }

        });
}	




 
 $(document).ready(function(){
 	
				var data_array = new Array();
				$("#main_sortable li").each(function(){ 
				        var item = {};
				        item['pid'] = $(this).data('id');  
				        data_array.push(item);

				    });
				    var main_sortable = JSON.stringify(data_array);
				    $('#main_sorted_data').val(main_sortable);


				    var left_array = new Array();
					$("#left_reserve li").each(function(){ 
				        var item = {}; 
				        if($(this).data('id') != undefined)
				        {
				        	item['pid'] = $(this).data('id');  	
				        	left_array.push(item);
				        } 

				    });
				    var left_reserve_sortable = JSON.stringify(left_array);
				    $('#left_reserved_data').val(left_reserve_sortable);

				    var right_array = new Array();
					$("#right_reserve li").each(function(){ 
				        var item = {};
				        if($(this).data('id') != undefined)
				        {
				        	item['pid'] = $(this).data('id');  	
				        	right_array.push(item);
				        } 

				    });
				    var right_reserve_sortable = JSON.stringify(right_array);
				    $('#right_reserved_data').val(right_reserve_sortable);
 });


 