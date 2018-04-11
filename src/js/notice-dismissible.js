jQuery( function( $ ) {

	$( '.ac-notice' ).on( 'click', '.ac-notice__dismiss, [data-dismiss], .notice-dismiss', function( e ) {
		e.preventDefault();

		let $notice = $( this ).closest( '.ac-notice' );
		let dismissible_callback = $notice.data( 'dismissible-callback' );

		if ( dismissible_callback ) {
			$.post( ajaxurl, dismissible_callback );
		}

		$notice.fadeOut( 500, function() {
			$notice.remove();
		} );
	} );

} );