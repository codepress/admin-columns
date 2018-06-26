jQuery( function( $ ) {

	$( document ).on( 'click', 'a.hide-review-notice-soft', function( e ) {
		e.preventDefault();

		let $notice = $( this ).closest( '.ac-notice' );

		$notice.find( '.info' ).slideUp();
		$notice.find( '.help' ).slideDown();

		$.post( ajaxurl, $notice.data( 'dismissible-callback' ) );
	} );

	$( document ).on( 'click', 'a.hide-review-notice', function( e ) {
		e.preventDefault();

		$( this ).closest( '.ac-notice' ).find( '.notice-dismiss' ).trigger( 'click' );
	} );

} );