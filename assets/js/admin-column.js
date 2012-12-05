/**
 *	fires when the dom is ready
 *
 */
jQuery(document).ready(function()
{	
	if (jQuery('#cpac').length == 0)
		return false;
	
	cpac_sortable();
	cpac_checked();
	cpac_box_events();
	cpac_menu();
	cpac_add_custom_column();
	cpac_clear_input_defaults();
	cpac_tooltips();
	cpac_addon_activation();
	cpac_width_range();
	cpac_export();
	cpac_import();
});

/**
 *	sortable
 *
 */
function cpac_sortable() 
{	
	jQuery('ul.cpac-option-list').sortable({
		handle: 		'div.cpac-sort-handle',
		placeholder: 	'cpac-placeholder',
		forcePlaceholderSize: true
	});
}

/**
 *	checked
 *
 */
function cpac_checked() 
{	
	jQuery('#cpac .cpac-option-list li .cpac-type-options').live({
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
function cpac_box_events()
{
	// fold in/out
	jQuery('#cpac .cpac-option-list .cpac-action').unbind('click').click(function(e){
		e.preventDefault();
		var li = jQuery(this).closest('li');
		li.find('.cpac-type-inside').slideToggle(150, function() {
			li.toggleClass('opened');
		});		
	});
	
	// remove custom field box
	jQuery('#cpac .cpac-delete-custom-field-box').unbind('click').click(function(e){
		e.preventDefault();		
		var el = jQuery(this).closest('li');
		
		el.addClass('deleting').animate({
			opacity : 0,
			height: 0
		}, 350, function() {
			el.remove();
		});		
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
 *	add custom columns 
 *
 */
function cpac_add_custom_column() 
{	
	jQuery('.cpac-add-customfield-column').click(function(e){
		e.preventDefault();		
			
		var list 		= jQuery(this).closest('td').find('ul.cpac-option-list');		
		var metafields 	= jQuery('li.cpac-box-metafield', list);
		
		// get unique ID number...
		var ids = [];		
		metafields.each(function(k,v) { 
			var _class = jQuery(v).attr('class');
			var classes = _class.split(' ');
			jQuery.each(classes, function(kc,vc){
				if ( vc.indexOf('cpac-box-column-meta-') === 0 ) {
					var id = vc.replace('cpac-box-column-meta-','');
					if ( id )
						ids.push(id);
				}
			});			
		});

		// ...and sort them
		ids.sort(sortNumber);
	
		if ( !ids )
			return;		
		
		function sortNumber(a,b) {
			return b - a;
		}		
		
		// ID's
		var id 		= parseFloat(ids[0]);
		var new_id 	= id + 1;
			
		// Clone
		var clone = jQuery( '.cpac-box-column-meta-' + id, list ).clone();
		
		// Toggle class
		jQuery(clone).removeClass('cpac-box-column-meta-' + id );
		jQuery(clone).addClass('cpac-box-column-meta-' + new_id );
		
		// Replace inputs ID's 
		var inputs = jQuery(clone).find('input, select');		
		jQuery(inputs).each(function(ik, iv){	
			jQuery(iv).attr('name', jQuery(iv).attr('name').replace(id, new_id) );
		});
		
		// Replace label ID's
		var labels = jQuery(clone).find('label');
		jQuery(labels).each(function(ik, iv){	
			var attr_for = jQuery(iv).attr('for');
			if ( attr_for ) {
				jQuery(iv).attr('for', attr_for.replace(id, new_id) );
			}
		});		
		
		// remove description
		clone.find('.remove-description').remove();
		
		// change label text
		clone.find('label.main-label, .cpac-type-inside input.text').text('Custom Field');
		clone.find('.cpac-type-inside input.text').val('Custom Field');
		
		// add remove button
		if ( clone.find('.cpac-delete-custom-field-box').length == 0 ) {
			var remove = '<p><a href="javascript:;" class="cpac-delete-custom-field-box">Remove</a>';
			clone.find('.cpac-type-inside').append(remove);
		}
				
		// add cloned box to the list
		list.append(clone);
		
		// retrigger click events
		cpac_box_events();
		
		// re-init width range slider
		cpac_width_range();
	});
}

/**
 *	Clear Input Defaults
 *
 */
function cpac_clear_input_defaults() 
{	
	jQuery.fn.cleardefault = function() {
		return this.focus(function() {
			if( this.value == this.defaultValue ) {
				this.value = "";
			}
		}).blur(function() {
			if( !this.value.length ) {
				this.value = this.defaultValue;
			}
		});
	};
	jQuery("#cpac-box-plugin_settings .addons input").cleardefault();	
}


/**
 *	Tooltip
 *
 */
function cpac_tooltips() 
{		
	jQuery('#cpac .activation_type span').each(function() {	
		var info = jQuery(this).next('.cpac-tooltip').html();
		
		if ( ! info )
			return;
		
		jQuery('#cpac .activation_type span').qtip({
			content: info,
			title: 'title',
			style: { 
				width: 		400,
				padding: 	0,
				background: 'transparent',
				color: 		'black',
				textAlign: 	'left',
				border: {
					width: 	0,
					radius: 0
				},
				tip: {
					corner: 'topMiddle', 
					color: '#8cc1e9',
					size: {
						x: 32,
						y : 15
					}
				}
			},
			position: {
				corner: {
					target: 'bottomRight'				
				},
				adjust: { 
					x: -80,
					y: 0
				}
			},
			hide: { 
				when: 'mouseout', 
				fixed: true ,
				delay: 100
			}
	   });
	});
}

/**
 *	Width range
 *
 */
function cpac_width_range() 
{
	if ( jQuery('.input-width-range').length == false )
		return;
	
	// loop through all width-range-sliders
	jQuery('.input-width-range').each( function(){
		
		var input 				= jQuery(this).closest('.cpac-type-inside').find('.input-width');
		var descr 				= jQuery(this).closest('.cpac-type-inside').find('.width-decription');
		var input_default 		= jQuery(input)[0].defaultValue;
		var translation_default = descr.attr('title');
		
		// add slider
		jQuery(this).slider({
			range: 	'min',
			value: 	1,
			min: 	0,
			max: 	100,
			value:  input_default,
			slide: function( event, ui ) {	
				
				// set default
				var descr_value = ui.value > 0 ? ui.value + '%' : translation_default;
				
				// set input value
				jQuery(input).val( ui.value );
				
				// set description
				jQuery(descr).text( descr_value );
			}
		});		
	});
}

/**
 *	Addon actviate/deactivate
 *
 */
function cpac_addon_activation() 
{	
	jQuery('#cpac-box-plugin_settings .addons .activation_code a.button').click(function(e) {
		e.preventDefault();		
		
		// get input values		
		var row			 = jQuery(this).closest('tr');
		var type		 = jQuery(row).attr('id').replace('cpac-activation-','');
		var parent_class = jQuery(this).parent('div');
		var msg 		 = jQuery(row).find('.activation-error-msg');
		
		// get translated string
		var translations 	 = jQuery('#cpac-box-plugin_settings .addon-translation-string');
		var msg_fillin		 = jQuery('.tstring-fill-in',translations).text();
		var msg_unrecognised = jQuery('.tstring-unrecognised',translations).text();
		
		// reset
		jQuery(msg).empty();
		
		// Activate
		if ( parent_class.hasClass('activate') ) {			
		
			// get input values
			var input 		= jQuery('.activate input', row);
			var button 		= jQuery('.activate .button', row);
			var key 		= input.val();
			var default_val = jQuery(input)[0].defaultValue;			
				
			// make sure the input value has changed			
			if ( key == default_val ) {
				jQuery(msg).text(msg_fillin).hide().fadeIn();
				return false;
			}			
			
			// set loading icon			
			button.addClass('loading');
			
			// update key
			jQuery.ajax({
				url 		: ajaxurl,
				type 		: 'POST',
				dataType 	: 'json',
				data : {
					action  : 'cpac_addon_activation',
					type	: 'sortable',
					key		: key
				},
				success: function(data) {					
					if ( data != null ) {				
						jQuery('div.activate', row).hide(); // hide activation button
						jQuery('div.deactivate', row).show(); // show deactivation button
						jQuery('div.deactivate span.masked_key', row).text(data); // display the returned masked key			
					} else {
						jQuery(msg).text(msg_unrecognised).hide().fadeIn();
					}
				},
                error: function(xhr, ajaxOptions, thrownError) {
					//console.log(xhr);
					//console.log(ajaxOptions);
					//console.log(thrownError);
                    jQuery(msg).text(msg_unrecognised).hide().fadeIn();
                },
				complete: function() {
					button.removeClass('loading');
				}
			});
		}
		
		// Deactivate
		if ( parent_class.hasClass('deactivate') ) {			

			var button = jQuery('.deactivate .button', row);
			var input  = jQuery('.activate input', row);
			
			// set loading icon
			button.addClass('loading');
			
			// update key
			jQuery.ajax({
				url 		: ajaxurl,
				type 		: 'POST',
				dataType 	: 'json',
				data : {
					action  : 'cpac_addon_activation',
					type	: 'sortable',
					key		: 'remove'
				},
				success: function(data) {
					jQuery('div.activate', row).show(); // show activation button
					jQuery('div.deactivate', row).hide(); // hide deactivation button
					jQuery('div.deactivate span.masked_key', row).empty(); // remove masked key
					input.val('');
				},
                error: function(xhr, ajaxOptions, thrownError) {
					//console.log(xhr);
					//console.log(ajaxOptions);
					//console.log(thrownError);
				},
				complete: function() {
					button.removeClass('loading');
				}
			});
		}
	});
}

/**
 *	Export Settings
 *
 */
function cpac_export()
{
	// Submit Export
	jQuery('#cpac_export_submit').click( function(e){
		
		var values = [];			
		
		// get selected values
		jQuery('#cpac_export_types :selected').each(function(i, selected){ 
			values[i] = jQuery(selected).val(); 
		});
		
		var btn 			 = jQuery(this);
		var export_container = jQuery('#cpac_export_output');
		var export_textarea  = jQuery('textarea', export_container);
		var msg 			 = btn.next('.export-message');
		
		// reset
		export_container.hide();
		export_textarea.empty();
		msg.hide();
		
		// get export code		
		if ( values ) {
			
			jQuery.ajax({
				url 		: ajaxurl,
				type 		: 'POST',
				dataType 	: 'json',
				data : {
					action  : 'cpac_get_export',
					types	: values				
				},
				success: function(data) {					
					if ( data != null ) {
						
						// succes						
						if ( 1 == data.status ) {
							export_textarea.text(data.msg);					
							export_container.show();							
						}
						
						// fail					
						else if ( data.msg ) {
							msg.text(data.msg).show();
						}							
						
					} else {
						// error msg						
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {},
				complete: function() {}
			});
		}
		
		e.preventDefault;
	});
	
	// Select Export Code
	jQuery('#cpac_export_output textarea').focus(function() {
		var t = jQuery(this);
		t.select();
		
		t.mouseup(function() { // Work around Chrome's little problem
			
			t.unbind("mouseup"); // Prevent further mouseup intervention
			return false;
		});
	});
}

/**
 *	Import Settings
 *
 */
function cpac_import()
{
	jQuery('#cpac_import_submit'). click( function(e){
		
		var btn 		= jQuery(this);		
		var import_code = jQuery('#cpac_import_input textarea').val();
		var msg 		= btn.next('.import-message');
		
		btn.addClass('loading');		
		msg.hide();
		
		if ( import_code ) {
		
			jQuery.ajax({
				url 		: ajaxurl,
				type 		: 'POST',
				dataType 	: 'json',
				data : {
					action  	: 'cpac_import',
					import_code	: import_code
				},
				success: function(data) {					
					if ( data != null ) {
										
						// succes						
						if ( 1 == data.status ) {
							msg.text(data.msg).show();
						}
						
						// fail					
						else if ( data.msg ) {
							msg.text(data.msg).show();
						}						
					} 
					
					else {
						msg.text('error').show();
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {},
				complete: function() {
					btn.removeClass('loading');
				}
			});
		}

		else {
			btn.removeClass('loading');
			msg.text('empty').show();
		}
		
		e.preventDefault;
	});
	
	// Select Import Code
	jQuery('#cpac_import_input textarea').focus(function() {
		var t = jQuery(this);
		t.select();
		
		t.mouseup(function() { // Work around Chrome's little problem
			
			t.unbind("mouseup"); // Prevent further mouseup intervention
			return false;
		});
	});
}