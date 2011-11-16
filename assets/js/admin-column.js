/**
 *	fires when the dom is ready
 *
 */
jQuery(document).ready(function(){	
	cpac_sortable();
	cpac_checked();
	cpac_open_box();
	cpac_menu();
	//cpac_add_custom_column();
});

/**
 *	sortable
 *
 */
function cpac_sortable() 
{
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			jQuery(this).width(jQuery(this).width());
		});
		return ui;
	};
	
	jQuery('ul.cpac-option-list').sortable({
		handle: 		'div.cpac-sort-handle',
		placeholder: 	'cpac-placeholder',
		forcePlaceholderSize: true,
		helper: 		fixHelper
	});
}

/**
 *	checked
 *
 */
function cpac_checked() 
{	
	
	jQuery('.cpac-option-list li .cpac-type-options').live({
		click: function() {
			var li 		= jQuery(this).closest('li');
			var state	= jQuery('.cpac-state', li);
			var value 	= state.attr('value');
			
			// toggle on
			if ( value != 'on') {
				li.addClass('active');
				state.attr('value', 'on');
				
			} 
			
			// toggle off
			else {
				li.removeClass('active');
				state.attr('value', '');
			}			
		}		
	});
}

/**
 *	Open and close box
 *
 */
function cpac_open_box()
{
	jQuery('.cpac-option-list .cpac-action').click(function(e){
		e.preventDefault();
		jQuery(this).closest('li').find('.cpac-type-inside').slideToggle(150);
		
	});
} 

/**
 *	Menu
 *
 */
function cpac_menu()
{
	// referer
	var referer 		= jQuery("input[type='hidden'][name='_wp_http_referer']");
	var referer_value 	= referer.attr('value');
	
	// click
	jQuery('#cpac .cpac-menu a').click( function(e, el) {
		e.preventDefault();
		var id = jQuery(this).attr('href');

		if ( id ) {
			// remove current
			jQuery('#cpac .cpac-menu a').removeClass('current');
			jQuery('#cpac .cpac-box-row').hide().removeClass('current');
			
			// set current
			jQuery(this).addClass('current');
			jQuery(id).show().addClass('current');

			// set refere
			var querystring = '&cpac_type=' + id.replace('#','');
			referer.attr('value', referer_value + querystring );			
		}
	});
}

/**
 *	add columns through ajax
 *
 */
/* function cpac_add_custom_column() 
{
	jQuery('.cpac-add-column a').click(function(e){
		
		e.preventDefault();		
		
		var a = jQuery(this);
		
		var column_type = a.attr('class').replace('cpac-add-column-', '');
		var post_type 	= a.parents('tr').find('.cpac_post_type').text().trim();
		var label 		= a.attr('title');
		var list 		= a.parents('tr').find('.cpac-option-list');
		
		console.log(column_type);
		console.log(post_type);
		console.log(label);
		
		jQuery.ajax({
			url 	 : ajaxurl,
			type 	 : 'POST',
			dataType : 'json',
			data  	 : {
				action  	: 'cpac_add_custom_column',
				column_type	: column_type,
				post_type	: post_type,
				label		: label
			},
			success: function(data) {
				jQuery(list).append(data);
				a.hide();
			}
		});
	});
}	 */	