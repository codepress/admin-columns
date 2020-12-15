/*
 * Column: bind remove events
 *
 * @since 2.0
 */
let remove = function( column ) {
	column.$el.find( '.remove-button' ).click( function( e ) {
		e.preventDefault();

		AdminColumns.Form.removeColumn( column.name );
	} );
};

export default remove;