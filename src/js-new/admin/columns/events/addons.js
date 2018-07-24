/*
 * Optional Radio Click events
 * TODO: Is not used anymore?
 */

let addons = function( column ) {
	let $column = column.$el;
	let inputs = $column.find( '[data-trigger] label' );

	inputs.on( 'click', function() {

		let id = $( this ).closest( 'td.input' ).data( 'trigger' );
		let state = $( 'input', this ).val();

		// Toggle indicator icon
		let label = $column.find( '[data-indicator-id="' + id + '"]' ).removeClass( 'on' );
		if ( 'on' === state ) {
			label.addClass( 'on' );
		}

		// Toggle additional options
		let additional = $column.find( '[data-handle="' + id + '"]' ).addClass( 'hide' );
		if ( 'on' === state ) {
			additional.removeClass( 'hide' );
		}
	} );

	// On load
	$column.find( '[data-trigger]' ).each( function() {

		let trigger = $( this ).data( 'trigger' );

		// Hide additional column settings
		let additional = $column.find( '[data-handle="' + trigger + '"]' ).addClass( 'hide' );
		if ( 'on' === $( 'input:checked', this ).val() ) {
			additional.removeClass( 'hide' );
		}
	} );

};

module.exports = addons;