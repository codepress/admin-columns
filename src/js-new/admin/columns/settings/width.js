
// Settings fields: Width
jQuery.fn.column_width_slider = function() {

	let $column_width = $( this ).find( '.ac-setting-input-width' );
	let input_width = $column_width.find( '.description input' ),
		input_unit = $column_width.find( '.unit-select input' ),
		unit = input_unit.filter( ':checked' ).val(),
		width = input_width.val(),
		slider = $column_width.find( '.width-slider' ),
		indicator = $( this ).find( '.ac-column-header .ac-column-heading-setting--width' );

	// width
	if ( '%' === unit && width > 100 ) {
		width = 100;
	}

	input_width.val( width );

	slider.slider( {
		range : 'min',
		min : 0,
		max : '%' === unit ? 100 : 500,
		value : width,
		slide : function( event, ui ) {
			input_width.val( ui.value );
			indicator.trigger( 'update' );
			input_width.trigger( 'validate' );
		}
	} );
};

let width = function( column ) {
	let $column = column.$el;

	$column.find( '.ac-column-setting--width' ).each( function() {
		$column.column_width_slider();

		// indicator
		let $width_indicator = $column.find( '.ac-column-header .ac-column-heading-setting--width' );

		$width_indicator.on( 'update', function() {
			let _width = $column.find( '.ac-setting-input-width .description input' ).val();
			let _unit = $column.find( '.ac-setting-input-width .description .unit' ).text();
			if ( _width > 0 ) {
				$( this ).text( _width + _unit );
			} else {
				$( this ).text( '' );
			}
		} );

		// unit selector
		let width_unit_select = $column.find( '.ac-setting-input-width .unit-select label' );
		width_unit_select.on( 'click', function() {

			$column.find( 'span.unit' ).text( $( this ).find( 'input' ).val() );
			$column.column_width_slider(); // re-init slider
			$width_indicator.trigger( 'update' ); // update indicator
		} );

		// width_input
		let width_input = $column.find( '.ac-setting-input-width .description input' )
			.on( 'keyup', function() {
				$column.column_width_slider(); // re-init slider
				$( this ).trigger( 'validate' ); // validate input
				$width_indicator.trigger( 'update' ); // update indicator
			} )

			// width_input:validate
			.on( 'validate', function() {
				let _width = width_input.val();
				let _new_width = $.trim( _width );

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

module.exports = width;