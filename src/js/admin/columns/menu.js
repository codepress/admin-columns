class Menu {

	init() {
		let $ = jQuery;

		$( '#ac_list_screen' ).on( 'change', function() {
			$( '.view-link' ).hide();
			$( this ).parents( 'form' ).submit();

			$( this ).prop( 'disabled', true ).next( '.spinner' ).css( 'display', 'inline-block' );
		} );

	}

}

module.exports = Menu;