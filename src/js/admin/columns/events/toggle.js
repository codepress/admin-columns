/*
 * Column: bind toggle events
 *
 * For performance we bind all other events after the click event.
 *
 * @since 2.0
 */
let toggle = function( column ) {
	column.$el.find( '[data-toggle="column"]' ).click( function( e ) {
		e.preventDefault();

		column.toggle();
	} ).css( 'cursor', 'pointer' );
};

module.exports = toggle;