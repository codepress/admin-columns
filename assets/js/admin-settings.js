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

	// we start by binding the toggle and remove events.
	jQuery('.cpac-column').each( function( i, col ) {
		jQuery( col ).column_bind_toggle();
		jQuery( col ).column_bind_remove();
		jQuery( col ).column_bind_clone();
		jQuery( col ).cpac_bind_container_addon_events();
	});

    jQuery( document ).cpacChanges();
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
        jQuery( document ).trigger( 'cpac_change' );
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
        jQuery( document ).trigger( 'cpac_change' );

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
        jQuery( document ).trigger( 'cpac_change' );
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

        jQuery( document ).trigger( 'cpac_change' );
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
        jQuery( document ).trigger( 'cpac_change' );
	});

	/** width */

	// slider
	column.column_width_slider();

	// indicator
	var width_indicator = column.find('.column-meta span.width');
	width_indicator.on( 'update', function(){
		var _width = column.find('input.width').val();
		var _unit = column.find('input.unit').filter(':checked').val();
		if  ( _width > 0 ) {
			jQuery(this).text( _width + _unit );
		} else {
			jQuery(this).text('');
		}
        jQuery( document).trigger( 'cpac_change' );
	});

	// unit selector
	var width_unit_select = column.find('.column_width .unit-select label');
	width_unit_select.on( 'click', function(){

		column.find('span.unit').text( jQuery(this).find('input').val() );
		column.column_width_slider(); // re-init slider
		width_indicator.trigger('update'); // update indicator
	});

	// width_input
	var width_input = column.find('input.width')
	.on( 'keyup', function() {
		column.column_width_slider(); // re-init slider
		jQuery(this).trigger('validate'); // validate input
		width_indicator.trigger('update'); // update indicator
	})

	// width_input:validate
	.on( 'validate', function() {
		var _width = width_input.val();
		var _new_width = jQuery.trim( _width );

		if ( ! jQuery.isNumeric( _new_width ) ) {
			_new_width = _new_width.replace(/\D/g,'');
		}
		if ( _new_width.length > 3 ) {
			_new_width = _new_width.substring(0, 3);
		}
		if ( _new_width <= 0 ) {
			_new_width = '';
		}
		if ( _new_width !== _width ) {
			width_input.val( _new_width );
		}
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
	column.find('.column-form .label label, .column-form .label .info').hover(function(){
		jQuery(this).parents( '.label' ).find('p.description').show();
	},function(){
		jQuery(this).parents( '.label' ).find('p.description').hide();
	});

	// refresh column and re-bind all events
	column.find( '[data-refresh="1"] select' ).change( function() {
		column.cpac_column_refresh();
	} );
};

/*
 * Column: remove from DOM
 *
 * @since 2.0
 */
jQuery.fn.column_remove = function() {
	jQuery(this).addClass('deleting').animate({ opacity : 0, height: 0 }, 350, function(e) {
		jQuery(this).remove();
        jQuery(document).trigger( 'cpac_change' );
	});
};

/*
 * Column: remove from DOM
 *
 * @since 2.0
 */
jQuery.fn.column_width_slider = function() {

	var column_width = jQuery(this).find('.column_width');

	var input_width = column_width.find('input.width'),
		input_unit = column_width.find('input.unit'),
		unit = input_unit.filter(':checked').val(),
		width = input_width.val(),
		slider = column_width.find('.width-slider'),
		indicator = jQuery(this).find('.column-meta span.width');

	// width
	if ( '%' == unit && width > 100 ) {
		width = 100;
	}

	input_width.val( width );

	slider.slider({
		range:	'min',
		min:	0,
		max:	'%' == unit ? 100 : 500,
		value:	width,
		slide: function( event, ui ) {

			input_width.val( ui.value );
			indicator.trigger('update');
			input_width.trigger('validate');
		}
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
    jQuery( document ).trigger( 'cpac_change' );

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
		if ( -1 === jQuery.inArray( id, ids ) ) {
            break;
        }
	}

	// only increment when needed
	//if ( 0 === id )
	//	return;

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
        jQuery( document ).trigger( 'cpac_change' );
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
				items : '.cpac-column',
                change: function(){
                    jQuery( document ).trigger( 'cpac_change' );
                }
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
 * Optional Radio Click events
 *
 */
jQuery.fn.cpac_bind_column_addon_events = function() {

	var column = jQuery( this );
	var inputs = column.find('[data-toggle-id] label');

	inputs.on( 'click', function(){

		var id = jQuery( this ).closest('td.input').data('toggle-id');
		var state = jQuery( 'input', this ).val();

		// Toggle indicator icon
		var label = column.find('[data-indicator-id="' + id + '"]' ).removeClass( 'on' );
		if ( 'on' == state ) {
			label.addClass( 'on' );
		}

		// Toggle additional options
		var additional = column.find('[data-additional-option-id="' + id + '"]' ).addClass( 'hide' );
		if ( 'on' == state ) {
			additional.removeClass( 'hide' );
		}

        jQuery( document).trigger( 'cpac_change' );
	});

	// Hide additonal options on ready
	column.find('[data-toggle-id]').each( function(){
		var additional = column.find('[data-additional-option-id="' + jQuery( this ).data('toggle-id') + '"]' ).addClass( 'hide' );
		if ( 'on' == jQuery( 'input:checked', this ).val() ) {
			additional.removeClass( 'hide' );
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

        jQuery( document).trigger( 'cpac_change' );
	});
};


(function($) {

    $.cpacChanges = function(element, options) {
        var plugin = this;
        var $el = $(element),
            changes = 0;

        plugin.init = function() {
            if( ! $el.data('cpac_changes') ){
                $el.data('cpac_changes', changes );
            }

            window.onbeforeunload = function (e) {
                if( $el.data('cpac_changes') > 0  ){
                    var message = cpac_i18n.changed,
                        e = e || window.event;
                    // For IE and Firefox
                    if (e) {
                        e.returnValue = message;
                    }
                    return message;
                }

            };

            $el.on( 'cpac_change', function(){
                plugin.change();
            });
        }

        plugin.change = function(){
            changes++;
            setChanges();
        }

        plugin.reset = function(){
            changes = 0;
            setChanges();
        }

        function setChanges(){
            $el.data('cpac_changes', changes);
        }

        plugin.init();
    }

    $.fn.cpacChanges = function(options) {
        return this.each(function() {

            if (undefined === $(this).data('cpacChanges')) {
                var plugin = new $.cpacChanges(this, options);
                $(this).data('cpacChanges', plugin);
            }
        });

    }

})(jQuery);