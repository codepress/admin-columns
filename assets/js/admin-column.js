(function($){
	
	/*
	 *  Exists
	 *  
	 *  @since 			1.5
	 *  @description	returns true or false on a element's existance
	 */
	
	$.fn.exists = function()
	{
		return $(this).length>0;
	};
	
	/*
	 *	Fires when the dom is ready
	 *
	 */
	$(document).ready(function()
	{	
		if ( ! $('#cpac').exists )
			return false;
		
		cpac_sortable();		
		cpac_box_events();
		cpac_menu();
		cpac_add_custom_column();
		cpac_clear_input_defaults();
		cpac_tooltips();
		cpac_addon_activation();
		cpac_width_range();
		cpac_export();
		cpac_import();
		cpac_pointer();
		cpac_help();
		cpac_sidebar_scroll();
	});

	/*
	 * Sortable
	 *
	 * @since 1.5
	 */
	function cpac_sortable() 
	{	
		$('.cpac-columns').sortable({
			handle: 'td.column_sort',
			placeholder: 'cpac-placeholder',
			forcePlaceholderSize: true
		});
	}	
	
	/*
	 * Open Form
	 *
	 * @since 1.5
	 */
	function open_form( e ) {
		var box = $(e).closest('.cpac-column');
		
		$('.column-form', box).slideToggle(150, function() {
			box.toggleClass('opened');
		});
	}
	
	/*
	 * Open and close box
	 *
	 * @since 1.5
	 */
	function cpac_box_events()
	{
		// fold in/out		
		$('.column_edit').unbind('click').click( function(){			
			open_form( this );					
		});
		
		$('.column_label a').unbind('click').click( function(){
			open_form( this );					
		});		
		
		// remove custom field box
		$('#cpac .cpac-delete-custom-field-box').unbind('click').click(function(e){
			
			var el = $(this).closest('div.cpac-column');
			
			el.addClass('deleting').animate({
				opacity : 0,
				height: 0
			}, 350, function() {
				el.remove();
			});
			
			e.preventDefault();
		});		
		
		// set state
		$('.column-meta td').not('.column_edit, .column_sort').unbind('click').click( function(e) {
		
			// make sure the TD itself is clicked and not a child element
			if ( this != e.target )
				return;
			
			var box 	= $(this).closest('.cpac-column');				
			var state	= $('.cpac-state', box);
			var value 	= state.attr('value');
			
			// toggle on
			if ( value != 'on') {
				box.addClass('active');
				state.attr('value', 'on');
			} 
			
			// toggle off
			else {
				box.removeClass('active');
				state.attr('value', '');
			}			
		});		
	} 

	/*
	 * Menu
	 *
	 * @since 1.5
	 */
	function cpac_menu()
	{		
		// click
		$('#cpac .cpac-menu a').click( function(e, el) {
			
			var id = $(this).attr('href');
			
			if ( id ) {
				
				var type = id.replace('#cpac-box-','');
								
				// remove current
				$('.cpac-menu a').removeClass('current');
				$('.columns-container').hide();		
				
				// set current
				$(this).addClass('current');
				$('.columns-container[data-type="' + type + '"]').show();		
			}
			
			e.preventDefault();
		});
	}

	/*
	 *	add custom columns 
	 *
	 */
	function cpac_add_custom_column() 
	{	
		$('.add-customfield-column').click(function(e){
			e.preventDefault();		
				
			var list 		= $(this).closest('.cpac-boxes').find('.cpac-columns');		
			var metafields 	= $('.cpac-box-metafield', list);
			
			// get unique ID number...
			var ids = [];		
			metafields.each(function(k,v) {	
				var classes = $(v).attr('class').split(' ');
				
				$.each(classes, function(kc,vc){
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
			var clone = $( '.cpac-box-column-meta-' + id, list ).clone();
			
			// Toggle class
			$(clone).removeClass('cpac-box-column-meta-' + id );
			$(clone).addClass('cpac-box-column-meta-' + new_id );
			
			// Replace inputs ID's 
			var inputs = $(clone).find('input, select');		
			$(inputs).each(function(ik, iv){
				$(iv).attr('name', $(iv).attr('name').replace(id, new_id) );		
				$(iv).attr('id', $(iv).attr('id').replace(id, new_id) );
			});
			
			// Replace label ID's
			var labels = $(clone).find('label');			
			$(labels).each(function(ik, iv){	
				var attr_for = $(iv).attr('for');		
				if ( attr_for ) {					
					$(iv).attr('for', attr_for.replace(id, new_id) );
				}
			});		
			
			// remove description
			clone.find('.remove-description').remove();
			
			// change label text
			clone.find('td.column_label a, tr.column_label input.text').text('Custom Field');
			clone.find('tr.column_label input.text').val('Custom Field');
			
			// add remove button
			if ( clone.find('.cpac-delete-custom-field-box').length == 0 ) {
				var remove = '<p><a href="javascript:;" class="cpac-delete-custom-field-box button">Remove</a>';
				clone.find('tr.column_action td.input').append(remove);
			}
					
			// add cloned box to the list
			list.append(clone);
			
			// retrigger click events
			cpac_box_events();
			
			// re-init width range slider
			cpac_width_range();
			
			// open 
			open_form( clone );
			
			// focus on clone
			$('html,body').animate({ scrollTop: clone.offset().top }, 'slow');
		});
	}

	/*
	 *	Clear Input Defaults
	 *
	 */
	function cpac_clear_input_defaults() 
	{	
		$.fn.cleardefault = function() {
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
		$("#cpac-box-plugin_settings .addons input").cleardefault();	
	}


	/*
	 *	Tooltip
	 *
	 */
	function cpac_tooltips() 
	{		
		$('#cpac .activation_type span').each(function() {	
			var info = $(this).next('.cpac-tooltip').html();
			
			if ( ! info )
				return;
			
			$('#cpac .activation_type span').qtip({
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

	/*
	 *	Width range
	 *
	 */
	function cpac_width_range() 
	{
		if ( $('.input-width-range').length == false )
			return;
		
		// loop through all width-range-sliders
		$('.input-width-range').each( function(){
			
			var input 				= $(this).closest('td').find('.input-width');
			var descr 				= $(this).closest('td').find('.width-decription');
			var input_default 		= $(input)[0].defaultValue;
			var translation_default = descr.attr('title');
			
			// add slider
			$(this).slider({
				range: 	'min',
				value: 	1,
				min: 	0,
				max: 	100,
				value:  input_default,
				slide: function( event, ui ) {	
					
					// set default
					var descr_value = ui.value > 0 ? ui.value + '%' : translation_default;
					
					// set input value
					$(input).val( ui.value );
					
					// set description
					$(descr).text( descr_value );
				}
			});		
		});
	}

	/*
	 *	Addon actviate/deactivate
	 *
	 */
	function cpac_addon_activation() 
	{	
		$('#cpac-box-plugin_settings .addons .activation_code a.button').click(function(e) {
			e.preventDefault();		
			
			// get input values		
			var row			 = $(this).closest('tr');
			var type		 = $(row).attr('id').replace('cpac-activation-','');
			var parent_class = $(this).parent('div');
			var msg 		 = $(row).find('.activation-error-msg');
			
			// get translated string
			var translations 	 = $('#cpac-box-plugin_settings .addon-translation-string');
			var msg_fillin		 = $('.tstring-fill-in',translations).text();
			var msg_unrecognised = $('.tstring-unrecognised',translations).text();
			
			// reset
			$(msg).empty();
			
			// Activate
			if ( parent_class.hasClass('activate') ) {			
			
				// get input values
				var input 		= $('.activate input', row);
				var button 		= $('.activate .button', row);
				var key 		= input.val();
				var default_val = $(input)[0].defaultValue;			
					
				// make sure the input value has changed			
				if ( key == default_val ) {
					$(msg).text(msg_fillin).hide().fadeIn();
					return false;
				}			
				
				// set loading icon			
				button.addClass('loading');
				
				// update key
				$.ajax({
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
							$('div.activate', row).hide(); // hide activation button
							$('div.deactivate', row).show(); // show deactivation button
							$('div.deactivate span.masked_key', row).text(data); // display the returned masked key			
						} else {
							$(msg).text(msg_unrecognised).hide().fadeIn();
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						//console.log(xhr);
						//console.log(ajaxOptions);
						//console.log(thrownError);
						$(msg).text(msg_unrecognised).hide().fadeIn();
					},
					complete: function() {
						button.removeClass('loading');
					}
				});
			}
			
			// Deactivate
			if ( parent_class.hasClass('deactivate') ) {			

				var button = $('.deactivate .button', row);
				var input  = $('.activate input', row);
				
				// set loading icon
				button.addClass('loading');
				
				// update key
				$.ajax({
					url 		: ajaxurl,
					type 		: 'POST',
					dataType 	: 'json',
					data : {
						action  : 'cpac_addon_activation',
						type	: 'sortable',
						key		: 'remove'
					},
					success: function(data) {
						$('div.activate', row).show(); // show activation button
						$('div.deactivate', row).hide(); // hide deactivation button
						$('div.deactivate span.masked_key', row).empty(); // remove masked key
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

	/*
	 *	Export Settings
	 *
	 */
	function cpac_export()
	{
		// Submit Export
		$('#cpac_export_submit').click( function(e){
			
			var values = [];			
			
			// get selected values
			$('#cpac_export_types :selected').each(function(i, selected){ 
				values[i] = $(selected).val(); 
			});
			
			var btn 			 = $(this);
			var export_container = $('#cpac_export_output');
			var export_textarea  = $('textarea', export_container);
			var msg 			 = btn.next('.export-message');
			
			// reset
			export_container.hide();
			export_textarea.empty();
			msg.hide();
			
			// get export code		
			if ( values ) {
				
				// set loading icon			
				btn.addClass('loading');
				
				$.ajax({
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
					complete: function() {
						btn.removeClass('loading');
					}
				});
			}
			
			e.preventDefault;
		});
		
		// Select Export Code
		$('#cpac_export_output textarea').focus(function() {
			var t = $(this);
			t.select();
			
			t.mouseup(function() { // Work around Chrome's little problem
				
				t.unbind("mouseup"); // Prevent further mouseup intervention
				return false;
			});
		});
	}

	/*
	 *	Import Settings
	 *
	 */
	function cpac_import()
	{
		$('#cpac_import_submit'). click( function(e){
			
			var btn 		= $(this);		
			var import_code = $('#cpac_import_input textarea').val();
			var msg 		= btn.next('.import-message');
			
			btn.addClass('loading');		
			msg.hide();
			
			if ( import_code ) {
			
				$.ajax({
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
								msg.html(data.msg).show();
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
		$('#cpac_import_input textarea').focus(function() {
			var t = $(this);
			t.select();
			
			t.mouseup(function() { // Work around Chrome's little problem
				
				t.unbind("mouseup"); // Prevent further mouseup intervention
				return false;
			});
		});
	}
	
	/*
	 * Help
	 *	 
	 * usage: <a href="javascript:;" class="help" data-help="tab-2"></a>
	 */
	function cpac_help()
	{
		$('#cpac a.help').click( function(e) {
			e.preventDefault();
			
			var panel = $('#contextual-help-wrap');
			
			panel.parent().show();			
			$('a[href="#tab-panel-cpac-' + $(this).attr('data-help') + '"]', panel).trigger('click');
			panel.slideDown( 'fast', function() {				
				panel.focus();				
			});			
		});		
	}
	
	/*
	 * WP Pointer
	 *
	 * credits to ACF ( Elliot Condon )
	 */
	function cpac_pointer()
	{
		$('a.cpac-pointer').each(function(){
			
			// vars
			var a 	 = $(this),
				html = a.attr('rel');			
			
			// create pointer
			a.pointer({
		        content: $('#' + html).html(),
		        position: {
		            my: 'left bottom',
		            at: 'left top',
		            edge: 'bottom',
		        },
		        close: function() {		        	
		        	a.removeClass('open');		        	
		        }
		    });
			
			// click
		    a.click( function() {		    
			    if( a.hasClass('open') ) {
				    a.removeClass('open');
			    }
			    else {
				    a.addClass('open');
			    }			    
		    });		    
		    
		    // show on hover
		    a.hover( function() {						
			    $(this).pointer('open');			    
		    }, function() {		    	
		    	if( ! a.hasClass('open') ) {
			    	$(this).pointer('close');
		    	}
			    
		    });
		});
	}
	
	
	/*
	 * Sidebar Fixed Scroll
	 *
	 * @since 1.5
	 */
	function cpac_sidebar_scroll()
	{
		var msie6 = $.browser == 'msie' && $.browser.version < 7;
  
		if (!msie6 && $('.columns-right-inside').length != 0 ) {
			var top = $('.columns-right-inside:visible').offset().top - parseFloat($('.columns-right-inside:visible').css('margin-top').replace(/auto/, 0));
			
			$(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = $(this).scrollTop() + 40;

				// whether that's below the form
				if (y >= top) {
					// if so, ad the fixed class
					$('.columns-right-inside:visible').addClass('fixed');
				} else {
					// otherwise remove it
					$('.columns-right-inside:visible').removeClass('fixed');
				}
			});
		}  
	}
	
})(jQuery);