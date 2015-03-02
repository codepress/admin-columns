/*
 *	Fires when the dom is ready
 *
 */
jQuery(document).ready(function() {

	if ( jQuery('#cpac').length === 0 ) {
		return false;
	}

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
	cpac_importexport();
	cpac_sidebar_feedback();
	//cpac_sidebar_scroll();

	// we start by binding the toggle and remove events.
	jQuery('.cpac-column').each( function( i, col ) {
		jQuery( col ).column_bind_toggle();
		jQuery( col ).column_bind_remove();
		jQuery( col ).column_bind_clone();
		jQuery( col ).cpac_bind_container_addon_events();
	});
});

function cpac_importexport() {
	jQuery( '#php-export-results textarea' ).on( 'focus, mouseup', function() {
		jQuery( this ).select();
	} ).select().focus();
}

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
 * @since 2.0
 */
jQuery.fn.column_bind_toggle = function() {

	var column = jQuery(this);

	column.find( 'td.column_type a, td.column_edit, td.column_label a.toggle, td.column_label .edit-button' ).click( function( e ) {
		e.preventDefault();

		column.toggleClass( 'opened' ).find( '.column-form' ).slideToggle( 150 );

		if ( ! column.hasClass( 'events-binded' ) ) {
			column.column_bind_events();
		}

		column.addClass('events-binded');

		// hook for addons
		jQuery( document ).trigger( 'column_init', column );
	} );
};

/*
 * Column: bind remove events
 *
 * @since 2.0
 */
jQuery.fn.column_bind_remove = function() {

	jQuery(this).find('.remove-button').click( function(e) {
		jQuery(this).closest('.cpac-column').column_remove();

		e.preventDefault();
	});
};

/**
 * Column: bind clone events
 *
 * @since 2.3.4
 */
jQuery.fn.column_bind_clone = function() {

	jQuery( this ).find( '.clone-button' ).click( function( e ) {
		var column, clone;

		e.preventDefault();

		column = jQuery( this ).closest( '.cpac-column' );

		clone = column.column_clone();

		if ( typeof clone !== 'undefined' ) {
			clone.removeClass( 'loading' ).hide().slideDown();
		}
	} );
};

jQuery.fn.cpac_column_refresh = function() {
	var el = jQuery( this );

	// Mark column as loading
	el.addClass( 'loading' );
	el.find( '.column-form' ).prepend( '<span class="spinner" />' );

	// Fetch new form HTML
	jQuery.post( ajaxurl, {
		plugin_id: 'cpac',
		action: 'cpac_column_refresh',
		column: jQuery( this ).find( 'input.column-name' ).val(),
		formdata: jQuery( this ).parents( 'form' ).serialize()
	}, function( data ) {

		// Replace current form by new form
		var newel = jQuery( '<div>' + data + '</div>' ).children();
		el.replaceWith( newel );
		el = newel;

		// Bind events
		el.column_bind_toggle();
		el.column_bind_remove();
		el.column_bind_clone();
		el.column_bind_events();

		// Remove "loading" marking from column
		el.removeClass( 'loading' ).addClass( 'opened' ).find( '.column-form' ).show();

		// Allow plugins to hook into this event
		jQuery( document ).trigger( 'column_change', el );
	} );
};

/*
 * Form Events
 *
 * @since 2.0
 */
jQuery.fn.column_bind_events = function() {

	var column			= jQuery( this );
	var container		= column.closest( '.columns-container ');
	var storage_model	= container.attr( 'data-type' );

	// Current column type
	var default_value =  column.find( '.column_type select option:selected' ).val();

	column.find( '.column_type select' ).change( function() {
		var option	= jQuery( 'optgroup', this ).children( ':selected' );
		var type	= option.val();
		var label	= option.text();
		var msg		= jQuery( this ).next( '.msg' ).hide();

		// Find template element for this field type
		var template = container.find( '.for-cloning-only .cpac-column[data-type="' + type + '"]' );

		if ( template.length ) {
			if ( template.find( '.is-disabled' ).length ) {
				msg.html( template.find( '.is-disabled' ).html() ).show();

				// Set to default
				jQuery(this).find( 'option' ).removeAttr( 'selected' );
				jQuery(this).find( 'option[value="' + default_value + '"]' ).attr( 'selected', 'selected' );
			}
			// Prevent column types that do not allow it to have multiple instances
			else if ( typeof template.attr( 'data-clone' ) === 'undefined' && jQuery( '.cpac-columns', container ).find( '[data-type="' + type + '"]' ).length ) {
				msg.html( cpac_i18n.clone.replace( '%s', '<strong>' + label + '</strong>' ) ).show();

				// Set to default
				jQuery(this).find('option').removeAttr('selected');
				jQuery(this).find('option[value="' + default_value + '"]').attr('selected', 'selected');

				return;
			}
			else {
				var clone = template.clone();

				// Open settings
				clone.addClass('opened').find('.column-form').show();
				clone.find( '.column-meta' ).replaceWith( column.find( '.column-meta' ) );
				clone.find( '.column-form' ).replaceWith( column.find( '.column-form' ) );

				// Increment clone id
				clone.cpac_update_clone_id( storage_model );

				// Load clone
				column.replaceWith( clone );
				clone.cpac_column_refresh();
			}
		}
	} );

	/** change label */
	column.find('.column_label .input input').bind( 'keyup change', function() {

		var value = jQuery( this ).val();
		jQuery(this).closest('.cpac-column').find( 'td.column_label .inner > a.toggle' ).text( value );
	});

	/** width slider */
	column.find('.input-width-range').each( function(){

		var input				= jQuery(this).closest('td').find('.input-width');
		var descr				= jQuery(this).closest('td').find('.width-decription');
		var indicator			= jQuery(this).closest('.cpac-column').find('.column-meta span.width');
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
				var indicator_value = ui.value > 0 ? ui.value + '%' : '';

				// set input value
				jQuery(input).val( ui.value );

				// set description
				descr.text( descr_value );
				indicator.text( indicator_value );
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

	if ( column.find( '.column_type select' ).val() == 'column-meta' ) {
		column.find( '.column_field_type select' ).change( function() {
			column.cpac_column_refresh();
		} );
	}
};

/*
 * Column: remove from DOM
 *
 * @since 2.0
 */
jQuery.fn.column_remove = function() {
	jQuery(this).addClass('deleting').animate({ opacity : 0, height: 0 }, 350, function(e) {
		jQuery(this).remove();
	});
};

/*
 * Column: clone
 *
 * @since 2.3.4
 */
jQuery.fn.column_clone = function() {

	var container = jQuery( this ).closest( '.columns-container' );
	var column = jQuery( this );

	if ( typeof column.attr( 'data-clone' ) === 'undefined' ) {
		var message = cpac_i18n.clone.replace( '%s', '<strong>' + column.find( '.column_label .toggle' ).text() + '</strong>' );
		/*var el_message = jQuery( '<div class="cpac_message error"><p>' + message + '</p></div>' );

		container.find( '.cpac-boxes' ).before( el_message );
		el_message.hide().slideDown().delay( 2500 ).slideUp( function() {
			jQuery( this ).remove();
		} );*/

		column.addClass( 'opened' ).find( '.column-form' ).slideDown( 150 );
		column.find( '.msg' ).html( message ).show();

		return;
	}

	var clone = jQuery( this ).clone();

	clone.cpac_update_clone_id( container.attr( 'data-type' ) );
	jQuery( this ).after( clone );

	// rebind toggle events
	clone.column_bind_toggle();

	// rebind remove events
	clone.column_bind_remove();

	// rebind clone events
	clone.column_bind_clone();

	// rebind all other events
	clone.column_bind_events();

	// reinitialize sortability
	cpac_sortable();

	// hook for addons
	jQuery( document ).trigger( 'column_add', clone );

	return clone;
};

/*
 * Update clone ID
 *
 * @since 2.0
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

	// get original clone ID
	var clone_id = el.attr( 'data-clone' );
	var clone_suffix = '';

	if ( clone_id ) {
		clone_suffix = '-' + clone_id;
	}

	// set clone ID
	el.attr( 'data-clone', id );
	el.find( 'input.clone' ).val( id );
	el.find( 'input.column-name' ).val( type + '-' + id );

	// update input names with clone ID
	var inputs = el.find( 'input, select, label' );
	jQuery( inputs ).each( function( i, v ) {

		var new_name = type + '-' + id;

		// name
		if( jQuery(v).attr( 'name' ) ) {
			jQuery(v).attr( 'name', jQuery(v).attr( 'name' ).replace( type + clone_suffix, new_name) );
		}

		// for
		if( jQuery(v).attr( 'for' ) ) {
			jQuery(v).attr( 'for', jQuery(v).attr( 'for' ).replace( type + clone_suffix, new_name ) );
		}

		// id
		if( jQuery(v).attr( 'id' ) ) {
			jQuery(v).attr( 'id', jQuery(v).attr( 'id' ).replace( type + clone_suffix, new_name ) );
		}
	});
};

function cpac_create_column( container ) {

	var clone = jQuery( '.for-cloning-only .cpac-column', container ).first().clone();
	var storage_model = container.attr( 'data-type' );

	if ( clone.length > 0 ) {
		// increment clone id ( before adding to DOM, otherwise radio buttons will reset )
		clone.cpac_update_clone_id( storage_model );

		// add to DOM
		jQuery( '.cpac-columns form', container ).append( clone );

		// rebind toggle events
		clone.column_bind_toggle();

		// rebind remove events
		clone.column_bind_remove();

		// rebind clone events
		clone.column_bind_clone();

		// rebind all other events
		clone.column_bind_events();

		// reinitialize sortability
		cpac_sortable();

		// hook for addons
		jQuery( document ).trigger( 'column_add', clone );
	}

	return clone;
}

/*
 * Add Column
 *
 * @since 2.0
 */
function cpac_add_column() {

	jQuery( '#cpac .add_column' ).click( function( e ) {
		var container = jQuery( this ).closest( '.columns-container' );
		var clone = cpac_create_column( container );

		// open settings
		clone.addClass('opened').find('.column-form').slideDown(150, function(){
			jQuery('html, body').animate({ scrollTop: clone.offset().top - 58 }, 300);
		});

		e.preventDefault();
	});
}

/**
 * @since 2.2.1
 */
function cpac_sidebar_feedback() {
	jQuery( function( $ ) {
		var sidebox = $( '.sidebox#direct-feedback' );

		sidebox.find( '#feedback-choice a.no' ).click( function( e ) {
			e.preventDefault();

			sidebox.find( '#feedback-choice' ).slideUp();
			sidebox.find( '#feedback-support' ).slideDown();
		} );

		sidebox.find( '#feedback-choice a.yes' ).click( function( e ) {
			e.preventDefault();

			sidebox.find( '#feedback-choice' ).slideUp();
			sidebox.find( '#feedback-rate' ).slideDown();
		} );
	} );
}

/*
 * Sidebar Scroll
 *
 * @since 1.5
 */
/*function cpac_sidebar_scroll() {

	if ( jQuery('.columns-right-inside').length === 0 ) {
		return;
	}

	if ( jQuery('.columns-right-inside:visible').offset() ) {
		var sidebar = jQuery('.columns-right-inside:visible');
		var top = sidebar.offset().top - parseFloat( sidebar.css('margin-top').replace(/auto/, 0) ) - 70;
		var viewport_height = jQuery(window).height();
		var sidebar_height = sidebar.height();

		jQuery(window).scroll(function (event) {
			var y = jQuery(this).scrollTop();

			// top position of div#cpac is calculated everytime incase of an opened help screen
			var offset = jQuery('#cpac').offset().top - parseFloat( jQuery('#cpac').css('margin-top').replace(/auto/, 0) );
			var sidebar_fits_on_screen = sidebar_height < ( viewport_height - 32 ); // adminbar

			// whether that's below
			if ( ( y >= top + offset ) && sidebar_fits_on_screen ) {
				jQuery('.columns-right-inside:visible').addClass('fixed');
			} else {
				jQuery('.columns-right-inside:visible').removeClass('fixed');
			}
		});
	}
}*/

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
	jQuery( 'div.cpac-columns' ).each( function() {
		if ( jQuery( this ).hasClass( 'ui-sortable' ) ) {
			jQuery( this ).sortable( 'refresh' );
		}
		else {
			jQuery( this ).sortable( {
				items : '.cpac-column'
			} );
		}
	} );
}

/*
 * Menu
 *
 * @since 1.5
 */
function cpac_menu() {

	var menu = jQuery('#cpac div.cpac-menu');
	// click
	menu.find('a').click( function(e, el) {

		var id = jQuery(this).attr('href');

		if ( id ) {

			var type = id.replace('#cpac-box-','');

			// remove current
			jQuery('.cpac-menu a').removeClass('current');
			jQuery('.columns-container').hide();

			// set current
			jQuery(this).addClass('current');
			var container = jQuery('.columns-container[data-type="' + type + '"]').show();
			var columns = container.find( '.cpac-columns' );

			// hook for addons
			jQuery( document ).trigger( 'cac_menu_change', columns );
		}

		// re init sidebar scroll
		//cpac_sidebar_scroll();

		e.preventDefault();
	});

	// activate first menu
	menu.find('a.current').trigger('click');
}

/*
 * Bind events: triggered after column is init, changed or added
 *
 */
jQuery( document ).bind('column_init column_change column_add', function( e, column ){
	jQuery( column ).cpac_bind_column_addon_events();
	jQuery( column ).cpac_bind_container_addon_events();
});

/*
 * Radio Click events
 *
 */
jQuery.fn.cpac_bind_column_addon_events = function() {

	var column = jQuery( this );
	var inputs = column.find('[data-toggle-id] label');

	// Enable editing: radio button
	inputs.click( function(){

		var id = jQuery( this ).closest('td.input').data('toggle-id');
		var label = column.find('[data-indicator-id="' + id + '"]' ).removeClass( 'on' );
		var status = jQuery( 'input', this ).val();

		if ( 'on' == status ) {
			label.addClass( 'on' );
		}
	});
};

/*
 * Indicator Click Events
 *
 */
jQuery.fn.cpac_bind_container_addon_events = function() {

	var column = jQuery( this );
	var indicator = column.find('[data-indicator-id]');

	indicator.unbind('click').click( function() {

		var id = jQuery( this ).data('indicator-id');
		var radio = column.find('[data-toggle-id="' + id + '"] input' );

		if ( jQuery( this ).hasClass('on') ) {
			jQuery( this ).removeClass('on').addClass('off');
			radio.filter('[value=off]').prop('checked', true);
		}
		else {
			jQuery( this ).removeClass('off').addClass('on');
			radio.filter('[value=on]').prop('checked', true);
		}
	});
};
