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
	jQuery("#cpac-activation-sortorder input").cleardefault();
	
}

/**
 *	Tooltip
 *
 */
function cpac_tooltips() 
{	
	var info = '<div class="qtip_title">Sortorder</div><div class="qtip_content"><p>Tekst en uitleg.</p></div>';
	jQuery('#cpac .activation_type span').qtip({
		content: info,
		title: 'title',
		style: { 
			width: 		280,
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
}


