jQuery( function( $ ) {

	$( '.ac-notice' ).on( 'click', '.ac-notice__dismiss, [data-dismiss], .notice-dismiss', function( e ) {
		e.preventDefault();
		console.log('S');

		let dismissible_callback = $( this ).closest( '.ac-notice' ).data( 'dismissible-callback' );

		if ( dismissible_callback ) {
			$.post( ajaxurl, dismissible_callback );
		}
	} );

} ); 