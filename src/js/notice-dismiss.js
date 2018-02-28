jQuery( function( $ ) {

	$( document ).on( 'click', '.ac-notice [data-dismiss]', function( e ) {
		e.preventDefault();

		let $notice = $( this ).parents( '.ac-notice' );
		let dismissible = $notice.data( 'dismissible' );

		$notice.fadeOut( 500, function() {
			$notice.remove();
		} );

		$.post( ajaxurl, dismissible );
	} );

} );
