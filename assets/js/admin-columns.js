/*
 *	Fires when the dom is ready
 *
 */
jQuery(document).ready(function() {

	if ( jQuery('#cpac').length === 0 )
		return false;

	cpac_sortable();
	cpac_menu();
	cpac_clear_input_defaults();
	cpac_pointer();
	cpac_help();
	cpac_sidebar_scroll();
	cpac_export_multiselect();
	cpac_import();

	/** init form events */
	jQuery( '.cpac-column' ).cpac_form_events();

	/** checkbox label */
	jQuery( '.column-meta .column_label input, .column-meta .column_type input' ).prop( 'disabled', true );
});

/*
 * Form Events
 *
 * @since 2.0.0
 */
jQuery.fn.cpac_form_events = function() {

	var column = jQuery( this );

	/** fold in/out */
	jQuery( '.column_edit, .column_label a', column ).click( function(){
		var box = jQuery( this ).closest( '.cpac-column' );

		jQuery( '.column-form', box ).slideToggle( 150, function() {
			box.toggleClass( 'opened' );
		});
	});

	/** set state */
	jQuery( '.column-meta td, .column-meta td .inner', column ).not( '.column_edit, .column_sort' ).click( function(e) {

		// make sure the TD itself is clicked and not a child element
		if ( this != e.target )
			return;

		var box		= jQuery(this).closest('.cpac-column');
		var state	= jQuery('.cpac-state', box);
		var value	= state.attr('value');

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
	jQuery( '.column_label .input input', column ).bind( 'keyup change', function() {

		var value = jQuery( this ).val();
		var label = jQuery( this ).closest( '.cpac-column' ).find( 'td.column_label .inner > a' );

		label.text( value );
	});

	/** width slider */
	jQuery( '.input-width-range', column ).each( function(){

		var input				= jQuery(this).closest('td').find('.input-width');
		var descr				= jQuery(this).closest('td').find('.width-decription');
		var input_default		= jQuery(input)[0].defaultValue;
		var translation_default = descr.attr('title');

		// add slider
		jQuery(this).slider({
			range:	'min',
			min:	0,
			max:	100,
			value:	input_default,
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
	jQuery( '.column_image_size label.custom-size', column ).click( function(){

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
}

/*
 * Sortable
 *
 * @since 1.5
 */
function cpac_sortable() {
	jQuery('.cpac-columns').sortable({
		revert					: 250,
		handle					: 'td.column_sort',
		placeholder				: 'cpac-placeholder',
		forcePlaceholderSize	: true,
		sort: function(e,ui){
			if ( jQuery(ui.placeholder).is(':empty') )
				jQuery(ui.placeholder).html('<div class="inner-placeholder"></div>');
		}
	});
}

/*
 * Menu
 *
 * @since 1.5
 */
function cpac_menu() {
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
function cpac_clear_input_defaults() {
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
 * Help
 *
 * usage: <a href="javascript:;" class="help" data-help="tab-2"></a>
 */
function cpac_help() {
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
 */
function cpac_pointer() {
	jQuery('.cpac-pointer').each(function(){

		// vars
		var el		= jQuery(this),
			html	= el.attr('rel'),
			pos		= el.attr('data-pos');

		var position = {
			at:		'left top',		// position of wp-pointer relative to the element which triggers the pointer event
			my:		'right top',	// position of wp-pointer relative to the at-coordinates
			edge:	'right',		// position of arrow
			offset: '0 0'			// offset for wp-pointer
		};

		if ( 'right' == pos ) {
			position = {
				at:		'right middle',
				my:		'left middle',
				edge:	'left'
			};
		}

		// create pointer
		el.pointer({
			content: jQuery('#' + html).html(),
			position: position,
			pointerWidth: 250,
			close: function() {
				el.removeClass('open');
			},

			// bug fix. with an arrow on the right side the position of wp-pointer is incorrect. it does not take
			// into account the padding of the arrow. adding "wp-pointer-' + position.edge"  will fix that.
			pointerClass: 'wp-pointer wp-pointer-' + position.edge
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
 * Sidebar Scroll
 *
 * @since 1.5
 */
function cpac_sidebar_scroll() {
	var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;

	if (!msie6 && jQuery('.columns-right-inside').length !== 0 ) {

		// top position of the sidebar on loading
		var top = jQuery('.columns-right-inside:visible').offset().top - parseFloat( jQuery('.columns-right-inside:visible').css('margin-top').replace(/auto/, 0) ) - 70;

		jQuery(window).scroll(function (event) {
			// y position of the scroll
			var y = jQuery(this).scrollTop();

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
function cpac_export_multiselect() {
	if( jQuery('#cpac_export_types').length === 0 )
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
 * Import
 *
 * @since 1.5
 */
function cpac_import() {
	var container = jQuery('#cpac_import_input');

	jQuery('#upload', container).change(function () {
		if ( jQuery(this).val() )
			jQuery('#import-submit', container).addClass('button-primary');
		else
			jQuery('#import-submit', container).removeClass('button-primary');
	});
}