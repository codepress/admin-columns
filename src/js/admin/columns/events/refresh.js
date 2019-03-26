let refresh = function( column ) {
	let $ = jQuery;

	column.$el.find( '[data-refresh="column"]' ).on( 'change', function() {
		// Allow plugins to hook into this event
		$( document ).trigger( 'AC.column.prerefresh', column.$el );
		column.$el.addClass( 'loading' );

		setTimeout( function() {
			column.refresh().always( function() {
				column.$el.removeClass( 'loading' );
			} ).fail( () => {
				column.showMessage( AC.i18n.errors.loading_column );
			} );
		}, 200 );

	} );
};

module.exports = refresh;