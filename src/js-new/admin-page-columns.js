/**
 * AC variables. Defined in DOM.
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */

import Form from "./classes/column-form";

/**
 * DOM ready
 */
jQuery( document ).ready( function( $ ) {

	if ( $( '#cpac' ).length === 0 ) {
		return false;
	}

	AC.incremental_column_name = 0;
	AC.Form = new Form( '#cpac .ac-columns form' );

	cpac_menu( $ );

	$( '.sidebox#direct-feedback' ).ac_feedback();
} );

/*
 * Menu
 *
 * @since 1.5
 */
function cpac_menu( $ ) {
	$( '#ac_list_screen' ).on( 'change', function() {
		$( '.view-link' ).hide();
		$( this ).parents( 'form' ).submit();

		$( this ).prop( 'disabled', true ).next( '.spinner' ).css( 'display', 'inline-block' );
	} );
}

/*
 * jQuery functions
 *
 * @since 2.0
 */
(function( $ ) {

	$.fn.ac_feedback = function() {
		let $box = $( this );

		$box.find( '#feedback-choice a.no' ).click( function( e ) {
			e.preventDefault();

			$box.find( '#feedback-choice' ).slideUp();
			$box.find( '#feedback-support' ).slideDown();
		} );

		$box.find( '#feedback-choice a.yes' ).click( function( e ) {
			e.preventDefault();

			$box.find( '#feedback-choice' ).slideUp();
			$box.find( '#feedback-rate' ).slideDown();
		} );
	};

	/*
	 * Column: bind toggle events
	 *
	 * For performance we bind all other events after the click event.
	 *
	 * @since 2.0
	 */
	$.fn.column_bind_toggle = function() {
		var $column = $( this );
		var is_disabled = $column.closest( '.ac-boxes' ).hasClass( 'disabled' );

		if ( $column.data( 'bound-toggle' ) ) {
			return;
		}

		$column.data( 'bound-toggle', 1 );

		$column.find( '[data-toggle="column"]' ).click( function( e ) {
			e.preventDefault();

			$column.toggleClass( 'opened' ).find( '.ac-column-body' ).slideToggle( 150 );

			if ( is_disabled ) {
				return;
			}

			if ( !$column.hasClass( 'events-binded' ) ) {
				$column.column_bind_events();
			}

			$column.addClass( 'events-binded' );

			// hook for addons
			$( document ).trigger( 'column_init', $column );
		} ).css( 'cursor', 'pointer' );
	};

	/*
	 * Column: bind remove events
	 *
	 * @since 2.0
	 */
	$.fn.column_bind_remove = function() {
		$( this ).find( '.remove-button' ).click( function( e ) {
			$( this ).closest( '.ac-column' ).column_remove();

			e.preventDefault();
		} );
	};

	/**
	 * Column: bind clone events
	 *
	 * @since 2.3.4
	 */
	$.fn.column_bind_clone = function() {
		$( this ).find( '.clone-button' ).click( function( e ) {
			e.preventDefault();

			var $clone = $( this ).closest( '.ac-column' ).column_clone();

			if ( typeof $clone !== 'undefined' ) {
				$clone.removeClass( 'loading' ).hide().slideDown();
			}
		} );
	};

	$.fn.cpac_column_refresh = function() {
		let $column = $( this );
		let $select = $column.find( '[data-refresh="column"]' );

		// Allow plugins to hook into this event
		$( document ).trigger( 'pre_column_refresh', $column );
		$select.prop( 'disabled', 1 );

		$column.addClass( 'loading' ).data( 'column' ).refresh().always( function() {
			$column.removeClass( 'loading' );
			$select.prop( 'disabled', false );

			// TODO: change to column_refresh?
			$( document ).trigger( 'column_change', el );
		} ).fail( function( error ) {

			//TODO does not work?
			AC.Form.showMessage( 'HELLO' );
			let $msg = el.closest( '.ac-admin' ).find( '.ajax-message' );

			$msg.addClass( 'error' ).find( 'p' ).html( AC.i18n.error );
			$msg.slideDown();

			el.slideUp( function() { el.remove() } );
		} );
	};

	$.fn.column_onload = function() {
		var column = $( this );

		/** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */
		var column_label = column.find( '.column_label .toggle' );
		if ( $.trim( column_label.html() ) && column_label.width() < 1 ) {
			column_label.html( column.find( '.column_type .inner' ).html() );
		}
	};

	$.fn.column_bind_type_selector = function() {
		let column = $( this );

		if ( column.data( 'event-type' ) ) {
			return;
		}

		column.data( 'event-type', 1 );

		column.find( 'select.ac-setting-input_type' ).change( function( e ) {
			column.addClass( 'loading' ).data( 'column' ).switchToType( $( this ).val() ).always( function() {
				column.removeClass( 'loading' );
			} );
		} );
	};

	$.fn.column_bind_label_changer = function() {
		let column = $( this );

		/** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */
		let column_label = column.find( '.column_label .toggle' );
		if ( $.trim( column_label.html() ) && column_label.width() < 1 ) {
			column_label.html( column.find( '.column_type .inner' ).html() );
		}

		/** change label */
		column.find( '.ac-column-setting--label input' ).bind( 'keyup change', function() {
			let value = $( this ).val();
			$( this ).closest( '.ac-column' ).find( 'td.column_label .inner > a.toggle' ).html( value );
		} ).trigger( 'change' );

		/** tooltip */
		column.find( '.ac-column-body .col-label .label' ).hover( function() {
			$( this ).parents( '.col-label' ).find( 'div.tooltip' ).show();
		}, function() {
			$( this ).parents( '.col-label' ).find( 'div.tooltip' ).hide();
		} );

		/**
		 * Populates the main Label with the selected label from the dropdown,
		 */
		column.find( 'select[data-label="update"]' ).change( function() {
			let $label = column.find( 'input.ac-setting-input_label' );
			let field_label = $( this ).find( 'option:selected' ).text();

			// Set new label
			$label.val( field_label );
			$label.trigger( 'change' );
			console.log( 'change' );

		} ).trigger( 'change' );

	};

	/*
	 * Form Events
	 *
	 * @since 2.0
	 */
	$.fn.column_bind_events = function() {
		// TODO move to column
		let column = $( this );
		column.column_onload();

		column.column_bind_type_selector();
		column.column_bind_label_changer();

		$( document ).trigger( 'init_settings', column );
	};

	/*
	 * Column: remove from DOM
	 *
	 * @since 2.0
	 */
	$.fn.column_remove = function() {
		$( this ).addClass( 'deleting' ).animate( { opacity : 0, height : 0 }, 350, function() {
			$( this ).remove();
		} );
	};

	/*
	 * Column: clone
	 *
	 * @since 2.3.4
	 */
	$.fn.column_clone = function() {

		var column = $( this );
		var columns = $( this ).closest( 'ac-columns' );

		if ( '1' === column.attr( 'data-original' ) ) {

			var message = AC.i18n.clone.replace( '%s', '<strong>' + column.find( '.column_label .toggle' ).text() + '</strong>' );

			column.addClass( 'opened' ).find( '.ac-column-body' ).slideDown( 150 );
			column.find( '.ac-setting-input_type' ).next( '.msg' ).html( message ).show();

			return;
		}

		AC.Form.cloneColumn( column );

		return;

		var clone = $( this ).clone();

		clone.cpac_update_clone_id();

		$( this ).after( clone );

		// rebind events
		clone.column_bind_toggle();
		clone.column_bind_remove();
		clone.column_bind_clone();

		// rebind all other events
		clone.column_bind_events();

		// reinitialize sortability
		columns.cpac_bind_ordering();

		// hook for addons
		$( document ).trigger( 'column_add', clone );

		return clone;
	};

	/*
	 * Optional Radio Click events
	 *
	 */
	$.fn.cpac_bind_column_addon_events = function() {

		var column = $( this );
		var inputs = column.find( '[data-trigger] label' );

		inputs.on( 'click', function() {

			var id = $( this ).closest( 'td.input' ).data( 'trigger' );
			var state = $( 'input', this ).val();

			// Toggle indicator icon
			var label = column.find( '[data-indicator-id="' + id + '"]' ).removeClass( 'on' );
			if ( 'on' == state ) {
				label.addClass( 'on' );
			}

			// Toggle additional options
			var additional = column.find( '[data-handle="' + id + '"]' ).addClass( 'hide' );
			if ( 'on' == state ) {
				additional.removeClass( 'hide' );
			}
		} );

		// On load
		column.find( '[data-trigger]' ).each( function() {

			var trigger = $( this ).data( 'trigger' );

			// Hide additional column settings
			var additional = column.find( '[data-handle="' + trigger + '"]' ).addClass( 'hide' );
			if ( 'on' == $( 'input:checked', this ).val() ) {
				additional.removeClass( 'hide' );
			}
		} );
	};

	/*
	 * Indicator Click Events
	 *
	 */
	$.fn.cpac_bind_indicator_events = function() {
		var $column = $( this );
		var $indicators = $column.find( '.ac-column-header [data-indicator-toggle]' );

		$indicators.each( function() {
			var $indicator = $( this );
			var setting = $( this ).data( 'setting' );
			var $setting = $column.find( '.ac-column-setting[data-setting=' + setting + ']' );
			var $input = $setting.find( '.col-input:first .ac-setting-input:first input[type=radio]' );

			$indicator.unbind( 'click' ).on( 'click', function( e ) {
				e.preventDefault();
				$indicator.toggleClass( 'on' );
				if ( $( this ).hasClass( 'on' ) ) {
					$input.filter( '[value=on]' ).prop( 'checked', true ).trigger( 'click' ).trigger( 'change' );
				}
				else {
					$input.filter( '[value=off]' ).prop( 'checked', true ).trigger( 'click' ).trigger( 'change' );
				}
			} );

			$input.on( 'change', function() {
				var value = $input.filter( ':checked' ).val();
				if ( 'on' == value ) {
					$indicator.addClass( 'on' );
				} else {
					$indicator.removeClass( 'on' );
				}
			} );
		} );

	};

	// Settings fields: Image _size
	$.fn.cpac_column_setting_image_size = function() {
		function initState( $setting, $select ) {
			if ( 'cpac-custom' == $select.val() ) {
				$setting.find( '.ac-column-setting' ).show();
			} else {
				$setting.find( '.ac-column-setting' ).hide();
			}
		}

		$( this ).each( function() {
			var $setting = $( this );
			var $select = $( this ).find( '.ac-setting-input select' );

			initState( $setting, $select );
			$select.on( 'change', function() {
				initState( $setting, $( this ) );
			} );

		} );
	};

	$.fn.cpac_column_sub_setting_toggle = function( options ) {
		var settings = $.extend( {
			value_show : "on",
			subfield : '.ac-column-setting'
		}, options );

		function initState( $setting, $input ) {
			var value = $input.filter( ':checked' ).val();
			var $subfields = $setting.find( settings.subfield );

			if ( settings.value_show == value ) {
				$subfields.show();
			} else {
				$subfields.hide();
			}
		}

		$( this ).each( function() {
			var $setting = $( this );
			var $input = $( this ).find( '.ac-setting-input input[type="radio"]' );

			initState( $setting, $input );
			$input.on( 'change', function() {
				initState( $setting, $input );
			} );

		} );
	};

	$.fn.cpac_column_setting_date = function() {

		$( this ).each( function() {

			var $container = $( this );

			// Custom input
			var $radio_custom = $container.find( 'input.custom' );
			var $input_custom = $container.find( '.ac-setting-input-date__custom' );
			var $input_value = $container.find( '.ac-setting-input-date__value' );
			var $example_custom = $container.find( '.ac-setting-input-date__example' );
			var $selected = $container.find( 'input[type=radio]:checked' );
			var $help_msg = $container.find( '.help-msg' );

			// Click Event
			$container.find( 'input[type=radio]' ).on( 'change', function() {

				var $input = $( this );
				var $input_container = $input.closest( 'label' );
				var date_format = $input_container.find( 'code' ).text();
				var description = $input_container.find( '.ac-setting-input-date__more' ).html();

				if ( date_format ) {
					$input_custom.val( date_format ).trigger( 'change' );
				}

				if ( $input.hasClass( 'diff' ) ) {
					$input_custom.val( '' );
					$example_custom.text( '' );
				}

				$input_custom.prop( 'disabled', true );

				// Custom input selected
				if ( $input.hasClass( 'custom' ) ) {
					$input.val( $input_custom.val() );
					$input_custom.prop( 'disabled', false );
					$help_msg.show();
				}

				// Show more description
				$help_msg.hide();
				if ( description ) {
					$help_msg.html( description ).show();
				}

				$input_value.val( $input.val() );
			} );

			// Custom input
			$input_custom.on( 'change', function() {

				$example_custom.html( '<span class="spinner is-active"></span>' );
				$radio_custom.val( $input_custom.val() );

				var $custom_value = $( this ).val();

				if ( !$custom_value ) {
					$example_custom.text( '' );
					return;
				}

				$.ajax( {
					url : ajaxurl,
					method : 'post',
					data : {
						action : 'date_format',
						date : $custom_value
					}
				} ).done( function( date ) {
					$example_custom.text( date );
				} );

				$input_value.val( $custom_value );
			} );

			// Update date example box
			$selected.trigger( 'change' );

			// Select custom input as a default
			if ( 0 === $selected.length ) {
				$radio_custom.trigger( 'click' );
			}

		} );

	};

	// Settings fields: Pro
	$.fn.cpac_column_setting_pro = function() {

		$( this ).each( function() {
			let $container = $( this );

			$container.find( 'input' ).on( 'click', function( e ) {
				e.preventDefault();

				$container.find( '[data-ac-open-modal]' ).trigger( 'click' );
			} )

		} );
	};

	$( document ).on( 'init_settings', function( e, column ) {
		$( column ).find( '.ac-column-setting--image' ).cpac_column_setting_image_size();
		$( column ).find( '.ac-column-setting--images' ).cpac_column_setting_image_size();
		$( column ).find( '.ac-column-setting--date' ).cpac_column_setting_date();
		$( column ).find( '.ac-column-setting--pro' ).cpac_column_setting_pro();

		// TODO: pro?
		$( column ).find( '.ac-column-setting--filter' ).cpac_column_sub_setting_toggle();
		$( column ).find( '.ac-column-setting--sort' ).cpac_column_sub_setting_toggle();
		$( column ).find( '.ac-column-setting--edit' ).cpac_column_sub_setting_toggle();
	} );

	// AC Modal Events (todo move to separate logic)
	$().ready( function() {

		$( document ).on( 'click', '[data-ac-open-modal]', function( e ) {
			e.preventDefault();

			$( $( this ).data( 'ac-open-modal' ) ).addClass( '-active' );
		} );

		$( '.ac-modal__dialog__close' ).on( 'click', function( e ) {
			e.preventDefault();

			$( this ).closest( '.ac-modal' ).removeClass( '-active' );
		} );

		$( '.ac-modal' ).on( 'click', function( e ) {
			$( this ).removeClass( '-active' );
		} );

		// Prevent bubbling
		$( '.ac-modal__dialog' ).on( 'click', function( e ) {
			e.stopPropagation();
		} );

		$( document ).keyup( function( e ) {
			if ( e.keyCode === 27 ) {
				$( '.ac-modal' ).removeClass( '-active' );
			}
		} );

	} );

}( jQuery ));

require( './settings/width' );