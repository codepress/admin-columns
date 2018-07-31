let selector = function( column ) {
	column.$el.find( 'select.ac-setting-input_type' ).change( function() {
		column.$el.addClass( 'loading' );
		column.switchToType( $( this ).val() ).always( function() {
			column.$el.removeClass( 'loading' );
		} );
	} );
};

module.exports = selector;