/*
 *	Fires when the dom is ready
 *
 */
jQuery(document).ready(function() {

	if ( jQuery('#cpac').length === 0 )
		return false;


	// General
	cpac_pointer();
	cpac_submit_form();

	// Settings Page
	cpac_clear_input_defaults();

	// Columns Page
	cpac_sortable();
	cpac_menu();
	cpac_help();
	cpac_add_column();
	cpac_sidebar_scroll();

	/** we start by binding the toggle and remove events. */
	jQuery('.cpac-column').each( function(i,col) {
		jQuery(col).column_bind_toggle();
		jQuery(col).column_bind_remove();
	});
});

/*
 * Submit Form
 *
 * @since 2.0.2
 */
function cpac_submit_form() {
	jQuery('.form-update a.submit-update').click( function(e){
		e.preventDefault();

		jQuery(this).closest('.columns-container').find('.cpac-columns form').submit();
	});
}

/*
 * Column: bind toggle events
 *
 * For performance we bind all other events after the click event.
 *
 * @since 2.0.0
 */
jQuery.fn.column_bind_toggle = function() {

	var column = jQuery(this);

	column.find('td.column_edit, td.column_label a.toggle' ).click( function(){

		column.toggleClass('opened').find('.column-form').slideToggle(150);

		if ( !column.hasClass('events-binded') )
			column.column_bind_events();

		column.addClass('events-binded');

		// hook for addons
		jQuery(document).trigger( 'column_init', column );
	});
};

/*
 * Column: bind remove events
 *
 * @since 2.0.0
 */
jQuery.fn.column_bind_remove = function() {

	jQuery(this).find('.remove-button').click( function(e) {
		jQuery(this).closest('.cpac-column').column_remove();

		e.preventDefault();
	});
};

/*
 * Form Events
 *
 * @since 2.0.0
 */
jQuery.fn.column_bind_events = function() {

	var column		= jQuery(this);
	var container	= column.closest('.columns-container');
	var storage_model = container.attr('data-type');

	/** select column type */
	var default_value =  column.find('.column_type select option:selected').val();

	column.find('.column_type select').change( function() {

		var option	= jQuery('optgroup', this).children(":selected");
		var type	= option.val();
		var label	= option.text();
		var msg		= jQuery(this).next('.msg').hide();

		// create clone
		var clone = container.find(".for-cloning-only .cpac-column[data-type='" + type + "']").clone();
		if ( clone.length > 0 ) {

			// column can have only one instance of itself and should not have another instance present?
			if ( 'undefined' === typeof clone.attr('data-clone') ) {
				if ( jQuery( '.cpac-columns', container ).find("[data-type='" + type + "']").length > 0 ) {
					msg.html( cpac_i18n.clone.replace( '%s', '<strong>' + label + '</strong>' ) ).show();

					// set to default
					jQuery(this).find('option').removeAttr('selected');
					jQuery(this).find('option[value="' + default_value + '"]').attr('selected', 'selected');
					return;
				}
			}

			// open settings
			clone.addClass('opened').find('.column-form').show();

			// increment clone id
			clone.cpac_update_clone_id( storage_model );

			// add to DOM
			column.replaceWith( clone );

			// rebind toggle events
			clone.column_bind_toggle();

			// rebind remove events
			clone.column_bind_remove();

			// rebind all other events
			clone.column_bind_events();

			// hook for addons
			jQuery(document).trigger( 'column_change', clone );
		}
	});

	/** change label */
	column.find('.column_label .input input').bind( 'keyup change', function() {

		var value = jQuery( this ).val();
		jQuery(this).closest('.cpac-column').find( 'td.column_label .inner > a.toggle' ).text( value );
	});

	/** width slider */
	column.find('.input-width-range').each( function(){

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
	column.find('.column_image_size label.custom-size').click( function(){

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

	/**	tooltip */
	column.find('.column-form .label label').hover(function(){
		jQuery(this).find('p.description').show();
	},function(){
		jQuery(this).find('p.description').hide();
	});
};

/*
 * Column: remove from DOM
 *
 * @since 2.0.0
 */
jQuery.fn.column_remove = function() {
	jQuery(this).addClass('deleting').animate({ opacity : 0, height: 0 }, 350, function(e) {
		jQuery(this).remove();
	});
};

/*
 * Update clone ID
 *
 * @since 2.0.0
 */
jQuery.fn.cpac_update_clone_id = function( storage_model ) {

	var el = jQuery( this );

	var type		= el.attr( 'data-type' );
	var all_columns = jQuery( '.columns-container[data-type="' + storage_model + '"]').find( '.cpac-columns' );
	var columns		= jQuery( all_columns ).find( '*[data-type="' + type + '"]' ).not( el );

/*	var type		= el.attr( 'data-type' );
	var all_columns	= el.closest( '.cpac-boxes' ).find( '.cpac-columns' );
	var columns		= jQuery( all_columns ).find( '*[data-type="' + type + '"]' ).not( el );*/

	// get clone ID
	var ids	= jQuery.map( columns, function( e, i ) {
		if ( jQuery(e).attr('data-clone') ){
			return parseInt( jQuery( e ).attr( 'data-clone' ), 10 );
		}
		return 0;
	});
	ids.sort();
	var max_id = Math.max.apply( null, ids ) + 1;
	for ( var id=0; id<=max_id; id++ ) {
		if ( -1 === jQuery.inArray( id, ids ) )
			break;
	}

	// only increment when needed
	if ( 0 === id )
		return;

	// set clone ID
	el.attr( 'data-clone', id );
	el.find( 'input.clone' ).val( id );

	// update input names with clone ID
	var inputs = el.find( 'input, select, label' );
	jQuery( inputs ).each( function( i, v ) {

		var new_name = type + '-' + id;

		// name
		if( jQuery(v).attr( 'name' ) ) {
			jQuery(v).attr( 'name', jQuery(v).attr( 'name' ).replace( type, new_name) );
		}

		// for
		if( jQuery(v).attr( 'for' ) ) {
			jQuery(v).attr( 'for', jQuery(v).attr( 'for' ).replace( type, new_name ) );
		}

		// id
		if( jQuery(v).attr( 'id' ) ) {
			jQuery(v).attr( 'id', jQuery(v).attr( 'id' ).replace( type, new_name ) );
		}
	});
};

/*
 * Add Column
 *
 * @since 2.0.0
 */
function cpac_add_column() {

	jQuery('#cpac .add_column').click( function(e){

		var container = jQuery(this).closest('.columns-container');

		var clone = jQuery('.for-cloning-only .cpac-column', container ).first().clone();

		var storage_model = container.attr('data-type');

		if ( clone.length > 0 ) {

			// increment clone id ( before adding to DOM, otherwise radio buttons will reset )
			clone.cpac_update_clone_id( storage_model );

			// add to DOM
			jQuery('.cpac-columns form', container).append( clone );

			// rebind toggle events
			clone.column_bind_toggle();

			// rebind remove events
			clone.column_bind_remove();

			// rebind all other events
			clone.column_bind_events();

			// open settings
			clone.addClass('opened').find('.column-form').slideDown(150, function(){
				jQuery('html, body').animate({ scrollTop: clone.offset().top - 58 }, 300);
			});

			// hook for addons
			jQuery(document).trigger( 'column_add', clone );
		}

		e.preventDefault();
	});
}

/*
 * Sidebar Scroll
 *
 * @since 1.5
 */
function cpac_sidebar_scroll() {

	if( jQuery('.columns-right-inside').length === 0 )
		return;

	if ( jQuery('.columns-right-inside:visible').offset() ) {

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
 * Sortable
 *
 * @since 1.5
 */
function cpac_sortable() {
	jQuery('div.cpac-columns').sortable({
		items					: '.cpac-column',
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
	jQuery('#cpac div.cpac-menu a').click( function(e, el) {

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


