jQuery( function( $ ) {

	$( document ).on( 'click', 'a.hide-review-notice', function( e ) {
		e.preventDefault();

		// TODO: animations are not always correct and ajax handling needs work
		let $notice = $( this ).closest( '.ac-notice' );
		let dismissible_callback = $notice.data( 'dismissible-callback' );

		let el_close = $notice.find( '.hide-review-notice' );
		let soft = $( this ).hasClass( 'hide-review-notice-soft' );

		if ( soft ) {
			$notice.find( '.info' ).slideUp();
			$notice.find( '.help' ).slideDown();
		}
		else {
			el_close.hide();
			el_close.after( '<div class="spinner right"></div>' );

			$notice.find( '.spinner' ).show();
		}

		$.post( ajaxurl, dismissible_callback, function() {
			if ( !soft ) {
				$notice.find( '.spinner' ).remove();
				$notice.slideUp();
			}
		} );

	} );
} );