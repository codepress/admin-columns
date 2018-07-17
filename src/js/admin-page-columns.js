/**
 * AC variables. Defined in DOM.
 * @param AC {Object}
 * @param AC.list_screen {String}
 * @param AC.layout {String}
 * @param AC.i81n {String}
 */
var AC;

/**
 * Temporary column name used for form elements.
 *
 * @type {number}
 */
var incremental_column_name = 0;

class AC_Form {

	constructor( el ) {
		this.$form = jQuery( el );
		this.$container = jQuery( '#cpac .ac-admin' );
		this.columns = {};
		this.init();

		// Todo move or remove?
		jQuery( document ).trigger( 'cac_model_ready', this.$container.data( 'type' ) );
	}

	init() {
		this.initColumns();
		this.bindFormEvents();
		this.bindOrdering();
	}

	bindOrdering() {

		if ( this.$form.hasClass( 'ui-sortable' ) ) {
			this.$form.sortable( 'refresh' );
		}
		else {
			this.$form.sortable( {
				items : '.ac-column',
				handle : '.column_sort'
			} );
		}

	}

	bindFormEvents() {
		let self = this;
		let $buttons = jQuery( '.sidebox a.submit, .column-footer a.submit' );

		$buttons.on( 'click', function() {
			$buttons.attr( 'disabled', 'disabled' );
			self.submitForm().always( function() {
				$buttons.removeAttr( 'disabled', 'disabled' );
			} )
		} );

		self.$container.find( '.add_column' ).on( 'click', function() {
			self.addColumn();
		} );

		let $boxes = jQuery( '#cpac .ac-boxes' );
		if ( $boxes.hasClass( 'disabled' ) ) {

			$boxes.find( '.ac-column' ).each( function( i, col ) {
				jQuery( col ).find( 'input, select' ).prop( 'disabled', true );
			} );
		}

	}

	initColumns() {
		let self = this;

		this.$form.find( '.ac-column' ).each( function() {
			let $el = jQuery( this );
			let column = new AC_Column( $el );

			column.disable();
			column.bindEvents();

			self.columns[ column.name ] = column;
		} );
	}

	resetColumns() {
		Object.keys( this.columns ).forEach( ( key ) => {
			console.log( key );
			let column = this.columns[ key ];

			column.destroy();
		} );

	}

	serialize() {
		return this.$form.serialize();
	}

	submitForm() {
		let self = this;

		let xhr = jQuery.post( ajaxurl, {
				action : 'ac_columns_save',
				data : this.serialize(),
				_ajax_nonce : AC._ajax_nonce,
				list_screen : AC.list_screen,
				layout : AC.layout,
				original_columns : AC.original_columns
			},

			function( response ) {
				if ( response ) {
					if ( response.success ) {
						self.showMessage( response.data, 'updated' );

						self.$container.addClass( 'stored' );
					}

					// Error message
					else if ( response.data ) {
						self.showMessage( response.data.message, 'notice notice-warning' );
					}
				}

			}, 'json' );

		// No JSON
		xhr.fail( function( error ) {
			// We choose not to notify the user of errors, because the settings will have
			// been saved correctly despite of PHP notices/errors from plugin or themes.
		} );

		jQuery( document ).trigger( 'cac_update', self.$container );
		return xhr;
	}

	showMessage( message, attr_class = 'updated' ) {
		let $msg = jQuery( '<div class="ac-message hidden ' + attr_class + '"><p>' + message + '</p></div>' );

		this.$container.find( '.ac-boxes' ).before( $msg );

		$msg.slideDown();
	}

	addColumn() {
		let $clone = jQuery( '#add-new-column-template' ).find( '.ac-column' ).clone();
		let column = new AC_Column( $clone );

		column.initNewInstance().bindEvents();

		this.columns[ column.name ] = column;
		this.$form.append( column.$el );

		column.toggle();

		jQuery( 'html, body' ).animate( { scrollTop : column.$el.offset().top - 58 }, 300 );
		jQuery( document ).trigger( 'column_add', column );

		return column;
	}

	removeColumn( name ) {
		if ( this.columns[ name ] ) {
			this.columns[ name ].destroy();
			delete this.columns[ name ];
		}
	}

}

class AC_Column {

	constructor( $el ) {
		this.$el = $el;
		this._name = this.$el.data( 'column-name' );
	}

	get name() {
		return this._name;
	}

	set name( name ) {
		this.$el.data( 'column-name', name );

		this._name = name;
	}

	isOriginal() {
		return (1 === this.$el.data( 'original' ));
	}

	isDisabled() {
		return this.$el.hasClass( 'disabled' );
	}

	disable() {
		this.$el.addClass( 'disabled' );

		return this;
	}

	enable() {
		this.$el.removeClass( 'disabled' );

		return this;
	}

	initNewInstance() {
		let temp_column_name = '_new_column_' + AC.incremental_column_name;
		let original_column_name = this.name;

		// update input names with clone ID
		this.$el.find( 'input, select, label' ).each( function( i, v ) {
			let $input = jQuery( v );

			// name attributes
			if ( $input.attr( 'name' ) ) {
				$input.attr( 'name', $input.attr( 'name' ).replace( `columns[${original_column_name}]`, `columns[${temp_column_name}]` ) );
			}

			// id attributes
			if ( $input.attr( 'id' ) ) {
				$input.attr( 'id', $input.attr( 'id' ).replace( `-${original_column_name}-`, `-${temp_column_name}-` ) );
			}

		} );

		this.name = temp_column_name;
		AC.incremental_column_name++;

		return this;
	}

	bindEvents() {
		this.$el.column_bind_toggle();
		this.$el.column_bind_remove();
		this.$el.column_bind_clone();
		this.$el.column_bind_events();

		//this.$el.cpac_bind_ordering();
		this.$el.cpac_bind_indicator_events();

		return this;
	}

	destroy() {
		this.$el.remove();
	}

	toggle() {
		this.$el.toggleClass( 'opened' ).find( '.ac-column-body' ).slideToggle( 150 );
	}

}

/**
 * DOM ready
 */
jQuery( document ).ready( function( $ ) {

	if ( $( '#cpac' ).length === 0 ) {
		return false;
	}

	AC.incremental_column_name = 0;
	AC.Form = new AC_Form( '#cpac .ac-columns form' );

	cpac_reset_columns( $ );
	cpac_menu( $ );
	cpac_sidebar_feedback( $ );
} );

/**
 * @since 2.2.1
 */
function cpac_sidebar_feedback( $ ) {
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
}

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
 * Reset columns
 *
 * @since 3.0.3
 */
function cpac_reset_columns( $ ) {
	var $container = $( '.ac-admin' );

	$( 'a[data-clear-columns]' ).on( 'click', function() {
		$container.find( '.ac-column' ).each( function() {
			$( this ).find( '.remove-button' ).trigger( 'click' );
		} );
	} );
}

/*
 * jQuery functions
 *
 * @since 2.0
 */
(function( $ ) {

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

		var el = $( this );
		var select = el.find( '[data-refresh="column"]' );
		var column_name = $( this ).attr( 'data-column-name' );
		var opened = el.hasClass( 'opened' );

		// Allow plugins to hook into this event
		$( document ).trigger( 'pre_column_refresh', el );

		var data = $( this ).find( ':input' ).serializeArray();
		var request_data = {
			action : 'ac_column_refresh',
			_ajax_nonce : AC._ajax_nonce,
			list_screen : AC.list_screen,
			layout : AC.layout,
			column_name : column_name,
			original_columns : AC.original_columns
		};

		$.each( request_data, function( name, value ) {
			data.push( {
				name : name,
				value : value
			} );
		} );

		// Mark column as loading
		el.addClass( 'loading' );
		select.prop( 'disabled', 1 );

		// Fetch new form HTML
		var xhr = $.post( ajaxurl, data, function( response ) {

			if ( response ) {
				// Replace current form by new form
				var newel = $( '<div>' + response.data + '</div>' ).children();
				el.replaceWith( newel );
				el = newel;

				// Bind events
				el.column_bind_toggle();
				el.column_bind_remove();
				el.column_bind_clone();
				el.column_bind_events();

				// Open settings
				if ( opened ) {
					el.addClass( 'opened' ).find( '.ac-column-body' ).show();
				}

				// Allow plugins to hook into this event

				// TODO: change to column_refresh?
				$( document ).trigger( 'column_change', el );
			}

			// Do nothing
			else {

			}
		}, 'json' );

		xhr.fail( function( error ) {
			var $msg = el.closest( '.ac-admin' ).find( '.ajax-message' );

			$msg.addClass( 'error' ).find( 'p' ).html( AC.i18n.error );
			$msg.slideDown();

			el.slideUp( function() { el.remove() } );

			console.log( 'responseText: ' + error.responseText );
		} );

		xhr.always( function() {
			// Remove "loading" marking from column
			el.removeClass( 'loading' );
			select.prop( 'disabled', false );
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
	

	/*
	 * Form Events
	 *
	 * @since 2.0
	 */
	$.fn.column_bind_events = function() {
		var column = $( this );
		var container = column.closest( '.ac-admin ' );

		column.column_onload();
		// Current column type
		var default_value = column.find( 'select.ac-setting-input_type option:selected' ).val();

		// Type selector
		column.find( 'select.ac-setting-input_type' ).change( function() {
			var option = $( 'optgroup', this ).children( ':selected' );
			var type = option.val();
			var msg = $( this ).next( '.msg' ).hide();
			var $select = $( this );

			var current_original_columns = [];
			container.find( '.ac-column[data-original=1]' ).each( function() {
				current_original_columns.push( $( this ).data( 'type' ) );
			} );

			column.addClass( 'loading' );

			$.ajax( {
				url : ajaxurl,
				method : 'post',
				dataType : 'json',
				data : {
					action : 'ac_column_select',
					type : type,
					current_original_columns : current_original_columns,
					original_columns : AC.original_columns,
					list_screen : AC.list_screen,
					layout : AC.layout,
					_ajax_nonce : AC._ajax_nonce,
				}
			} )
				.done( function( response ) {
					if ( response ) {

						if ( response.success ) {
							var el = column.closest( '.ac-column' );

							// Replace current form by new form
							var newel = $( '<div>' + response.data + '</div>' ).children();
							el.replaceWith( newel );
							el = newel;

							// Bind events
							el.column_bind_toggle();
							el.column_bind_remove();
							el.column_bind_clone();
							el.column_bind_events();

							// Open settings
							el.addClass( 'opened' ).find( '.ac-column-body' ).show();

							el.cpac_update_clone_id();

							// Allow plugins to hook into this event
							$( document ).trigger( 'column_change', el );
						}

						// Error message
						else if ( response.data ) {
							if ( 'message' === response.data.type ) {
								msg.html( response.data.error ).show();

								// Set to default
								$select.find( 'option' ).removeAttr( 'selected' );
								$select.find( 'option[value="' + default_value + '"]' ).attr( 'selected', 'selected' );
							}
						}
					}
				} )

				.always( function() {
					column.removeClass( 'loading' );
				} );

		} );

		/** change label */
		column.find( '.ac-column-setting--label input' ).bind( 'keyup change', function() {
			var value = $( this ).val();
			$( this ).closest( '.ac-column' ).find( 'td.column_label .inner > a.toggle' ).html( value );
		} );

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
			var $label = column.find( 'input.ac-setting-input_label' );
			var field_label = $( this ).find( 'option:selected' ).text();

			// Set new label
			$label.val( field_label );
			$label.trigger( 'change' );
		} );

		// refresh column and re-bind all events
		column.find( '[data-refresh="column"]' ).change( function() {
			column.cpac_column_refresh();
		} );

		$( document ).trigger( 'init_settings', column );
	};

	$.fn.column_bind_settings = function() {
		var $column = $( this );

		$column.find( '.ac-column-setting--image_size' ).cpac_column_setting_image_size();
		$column.find( '.ac-column-setting--width' ).cpac_column_setting_width();
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
	 * Update clone ID
	 *
	 * @since 2.0
	 */
	$.fn.cpac_update_clone_id = function() {
		var $el = $( this );
		var original_column_name = $el.attr( 'data-column-name' );
		var temp_column_name = '_new_column_' + incremental_column_name;

		// update input names with clone ID
		var inputs = $el.find( 'input, select, label' );
		$( inputs ).each( function( i, v ) {

			// name
			if ( $( v ).attr( 'name' ) ) {
				$( v ).attr( 'name', $( v ).attr( 'name' ).replace( 'columns[' + original_column_name + ']', 'columns[' + temp_column_name + ']' ) );
			}

			// id
			if ( $( v ).attr( 'id' ) ) {
				$( v ).attr( 'id', $( v ).attr( 'id' ).replace( '-' + original_column_name + '-', '-' + temp_column_name + '-' ) );
			}

			// TODO for
		} );

		$el.attr( 'data-column-name', temp_column_name );

		// increment
		incremental_column_name++;
	};

	/*
	 * Bind events: triggered after column is init, changed or added
	 *
	 */
	$( document ).bind( 'column_init column_change column_add', function( e, column ) {
		var is_disabled = $( column ).closest( '.ac-boxes' ).hasClass( 'disabled' );

		if ( is_disabled ) {
			return;
		}

		$( column ).cpac_bind_column_addon_events();
		$( column ).cpac_bind_indicator_events();
	} );

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

	// Settings fields: Width
	$.fn.column_width_slider = function() {

		var column_width = $( this ).find( '.ac-setting-input-width' );

		var input_width = column_width.find( '.description input' ),
			input_unit = column_width.find( '.unit-select input' ),
			unit = input_unit.filter( ':checked' ).val(),
			width = input_width.val(),
			slider = column_width.find( '.width-slider' ),
			indicator = $( this ).find( '.ac-column-header .ac-column-heading-setting--width' );

		// width
		if ( '%' == unit && width > 100 ) {
			width = 100;
		}

		input_width.val( width );

		slider.slider( {
			range : 'min',
			min : 0,
			max : '%' == unit ? 100 : 500,
			value : width,
			slide : function( event, ui ) {

				input_width.val( ui.value );
				indicator.trigger( 'update' );
				input_width.trigger( 'validate' );
			}
		} );
	};

	$.fn.cpac_column_setting_width = function() {

		$( this ).each( function() {
			var $column = $( this ).parents( '.ac-column' );
			$column.column_width_slider();

			// indicator
			var $width_indicator = $column.find( '.ac-column-header .ac-column-heading-setting--width' );

			$width_indicator.on( 'update', function() {
				var _width = $column.find( '.ac-setting-input-width .description input' ).val();
				var _unit = $column.find( '.ac-setting-input-width .description .unit' ).text();
				if ( _width > 0 ) {
					$( this ).text( _width + _unit );
				} else {
					$( this ).text( '' );
				}
			} );

			// unit selector
			var width_unit_select = $column.find( '.ac-setting-input-width .unit-select label' );
			width_unit_select.on( 'click', function() {

				$column.find( 'span.unit' ).text( $( this ).find( 'input' ).val() );
				$column.column_width_slider(); // re-init slider
				$width_indicator.trigger( 'update' ); // update indicator
			} );

			// width_input
			var width_input = $column.find( '.ac-setting-input-width .description input' )
				.on( 'keyup', function() {
					$column.column_width_slider(); // re-init slider
					$( this ).trigger( 'validate' ); // validate input
					$width_indicator.trigger( 'update' ); // update indicator
				} )

				// width_input:validate
				.on( 'validate', function() {
					var _width = width_input.val();
					var _new_width = $.trim( _width );

					if ( !$.isNumeric( _new_width ) ) {
						_new_width = _new_width.replace( /\D/g, '' );
					}
					if ( _new_width.length > 3 ) {
						_new_width = _new_width.substring( 0, 3 );
					}
					if ( _new_width <= 0 ) {
						_new_width = '';
					}
					if ( _new_width !== _width ) {
						width_input.val( _new_width );
					}
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
		$( column ).find( '.ac-column-setting--width' ).cpac_column_setting_width();
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