/*
 * Column: bind remove events
 *
 * @since 2.0
 */
let remove = function( column ) {
	column.$el.find( '.remove-button' ).click( function( e ) {
		e.preventDefault();

		AC.Form.removeColumn( column.name );
	} );
};

export default remove;