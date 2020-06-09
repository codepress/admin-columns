let selector = function( column ) {
	let $ = jQuery;
	column.$el.find( 'select.ac-setting-input_type' ).change( function() {
		column.$el.addClass( 'loading' );
		column.switchToType( $( this ).val() ).always( function() {
			column.$el.removeClass( 'loading' );

			AC.Form.reindexColumns();
		} ).fail( () => {
			column.showMessage( AC.i18n.errors.loading_column );
		} );
	} );
};

export default selector;