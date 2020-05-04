/*
* Column: bind clone events
*
* @since 2.0
*/
let clone = function( column ) {
	column.$el.find( '.clone-button' ).click( function( e ) {
		e.preventDefault();

		if ( column.isOriginal() ) {
			return;
		}

		AC.Form.cloneColumn( column.$el );
	} );
};

export default clone;