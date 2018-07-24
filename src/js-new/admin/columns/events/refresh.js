let refresh = function( column ) {
	column.$el.find( '[data-refresh="column"]' ).on( 'change', function() {
		// Allow plugins to hook into this event
		$( document ).trigger( 'pre_column_refresh', column.$el );

		column.$el.addClass( 'loading' );
		column.refresh().always( function() {
			column.$el.removeClass( 'loading' );

			$( document ).trigger( 'AC.columnRefresh', { column } );
		} );
	} );
};

module.exports = refresh;