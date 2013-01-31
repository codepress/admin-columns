/*
 *	Fires when the dom is ready
 *
 */
jQuery(document).ready(function()
{
	if ( jQuery('#cpac').length == 0 )
		return false;

	cpac_sortable();
	cpac_box_events();
	cpac_menu();
	cpac_clear_input_defaults();
	cpac_addon_activation();
	cpac_pointer();
	cpac_help();
	cpac_sidebar_scroll();
	cpac_export_multiselect();
	cpac_import();
});

/*
 * Sortable
 *
 * @since 1.5
 */
function cpac_sortable()
{
	jQuery('.cpac-columns').sortable({
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
	var box = jQuery(e).closest('.cpac-column');

	jQuery('.column-form', box).slideToggle(150, function() {
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
	/** fold in/out */
	jQuery('.column_edit').unbind('click').click( function(){
		open_form( this );
	});
	jQuery('.column_label a').unbind('click').click( function(){
		open_form( this );
	});

	/** remove custom field box */
	jQuery('#cpac .cpac-delete-custom-field-box').unbind('click').click(function(e){

		var el = jQuery(this).closest('div.cpac-column');

		el.addClass('deleting').animate({
			opacity : 0,
			height: 0
		}, 350, function() {
			el.remove();
		});

		e.preventDefault();
	});

	/** set state */
	jQuery('.column-meta td').not('.column_edit, .column_sort').unbind('click').click( function(e) {
				
		// make sure the TD itself is clicked and not a child element
		if ( this != e.target )
			return;

		var box 	= jQuery(this).closest('.cpac-column');
		var state	= jQuery('.cpac-state', box);
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

	/** change label */
	var fields = jQuery('.column_label .input input');
	fields.keyup(function() {
		updatePreview( jQuery(this) );
	});
	fields.change(function() {
		updatePreview( jQuery(this) );
	});
	function updatePreview( el ) {
		var value = jQuery(el).val();
		var label = jQuery(el).closest('.cpac-column').find('td.column_label > a');

		label.text( value );
	};

	/** init width slider */
	jQuery('.input-width-range').each( function(){

		var input 				= jQuery(this).closest('td').find('.input-width');
		var descr 				= jQuery(this).closest('td').find('.width-decription');
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

	/** display custom image size */
	jQuery('.column_image_size label.custom-size').click(function(){

		var parent = jQuery(this).closest('.input');

		if ( jQuery(this).hasClass('image-size-custom') ) {
			jQuery('.custom-size-w', parent).removeClass('hidden');
			jQuery('.custom-size-h', parent).removeClass('hidden');
		}

		else {
			jQuery('.custom-size-w', parent).addClass('hidden');
			jQuery('.custom-size-h', parent).addClass('hidden');
		}
	});

	/** select image custom field type */
	jQuery('.column_field_type .input select option').click( function(){
		var image_size = jQuery(this).closest('table').find('.column_image_size').show();
		var value = jQuery(this).attr('value');

		if( 'image' == value || 'library_id' == value ) {
			image_size.show();
		}
		else {
			image_size.hide();
		}
	});
	
	/** checkbox label */
	jQuery('.column_label a input').prop('disabled', true);
}

/*
 * Menu
 *
 * @since 1.5
 */
function cpac_menu()
{
	// click
	jQuery('#cpac .cpac-menu a').click( function(e, el) {

		var id = jQuery(this).attr('href');

		if ( id ) {

			var type = id.replace('#cpac-box-','');

			// remove current
			jQuery('.cpac-menu a').removeClass('current');
			jQuery('.columns-container').hide();

			// set current
			jQuery(this).addClass('current');
			jQuery('.columns-container[data-type="' + type + '"]').show();
		}

		e.preventDefault();
	});
}

/*
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


/*
 *	Addon actviate/deactivate
 *
 */
function cpac_addon_activation()
{
	jQuery('.addons .activation_code a.button').click(function(e) {
		e.preventDefault();

		// get input values
		var row			 = jQuery(this).closest('tr');
		var type		 = jQuery(row).attr('id').replace('activation-','');
		var parent_class = jQuery(this).parent('div');
		var msg 		 = jQuery(row).find('.activation-error-msg');

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
				jQuery(msg).text(cpac_i18n.fill_in).hide().fadeIn();
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
						jQuery(msg).text(cpac_i18n.unrecognised).hide().fadeIn();
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					//console.log(xhr);
					//console.log(ajaxOptions);
					//console.log(thrownError);
					jQuery(msg).text(cpac_i18n.unrecognised).hide().fadeIn();
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

/*
 * Help
 *
 * usage: <a href="javascript:;" class="help" data-help="tab-2"></a>
 */
function cpac_help()
{
	jQuery('#cpac a.help').click( function(e) {
		e.preventDefault();

		var panel = jQuery('#contextual-help-wrap');

		panel.parent().show();
		jQuery('a[href="#tab-panel-cpac-' + jQuery(this).attr('data-help') + '"]', panel).trigger('click');
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
	jQuery('.cpac-pointer').each(function(){

		// vars
		var el 	 = jQuery(this),
			html = el.attr('rel'),
			pos  = el.attr('data-pointer-position');

		var position = {
			my: 'left bottom',
			at: 'left top',
			edge: 'bottom',
		};

		if ( 'bottom' == pos ) {
			position = {
				my: 'left top',
				at: 'left bottom',
				edge: 'top',
			};
		}

		// create pointer
		el.pointer({
			content: jQuery('#' + html).html(),
			position: position,
			close: function() {
				el.removeClass('open');
			}
		});

		// click
		el.click( function() {
			if( el.hasClass('open') ) {
				el.removeClass('open');
			}
			else {
				el.addClass('open');
			}
		});

		// show on hover
		el.hover( function() {
			jQuery(this).pointer('open');
		}, function() {
			if( ! el.hasClass('open') ) {
				jQuery(this).pointer('close');
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
	var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;

	if (!msie6 && jQuery('.columns-right-inside').length != 0 ) {

		// top position of the sidebar on loading
		var top = jQuery('.columns-right-inside:visible').offset().top - parseFloat( jQuery('.columns-right-inside:visible').css('margin-top').replace(/auto/, 0) ) - 70;

		jQuery(window).scroll(function (event) {
			// y position of the scroll
			var y 	= jQuery(this).scrollTop();

			// top position of div#cpac is calculated everytime incase of an opened help screen
			var offset = jQuery('#cpac').offset().top - parseFloat( jQuery('#cpac').css('margin-top').replace(/auto/, 0) );

			// whether that's below
			if (y >= top + offset ) {
				// if so, ad the fixed class
				jQuery('.columns-right-inside:visible').addClass('fixed');
			} else {
				// otherwise remove it
				jQuery('.columns-right-inside:visible').removeClass('fixed');
			}
		});
	}
}

/*
 * Export Multiselect
 *
 * @since 1.5
 */
function cpac_export_multiselect()
{
	if( jQuery('#cpac_export_types').length == 0 )
		return;

	var export_types = jQuery('#cpac_export_types');

	// init
	export_types.multiSelect();

	// click events
	jQuery('#export-select-all').click( function(e){
		export_types.multiSelect('select_all');
		e.preventDefault();
	});
}

/*
 * Custom Image Size
 *
 * @since 1.5
 */
function cpac_import()
{
	var container = jQuery('#cpac_import_input');

	jQuery('#upload', container).change(function () {
		if ( jQuery(this).val() )
			jQuery('#import-submit', container).addClass('button-primary');
		else
			jQuery('#import-submit', container).removeClass('button-primary');
	});
}