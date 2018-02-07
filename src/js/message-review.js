jQuery( function( $ ) {
	$( document ).ready( function() {
		$( '.updated a.hide-review-notice' ).click( function( e ) {
			e.preventDefault();

			let el = $( this ).parents( '.ac-message' );
			let el_close = el.find( '.hide-notice' );
			let soft = $( this ).hasClass( 'hide-review-notice-soft' );

			if ( soft ) {
				el.find( '.info' ).slideUp();
				el.find( '.help' ).slideDown();
			}
			else {
				el_close.hide();
				el_close.after( '<div class="spinner right"></div>' );
				el.find( '.spinner' ).show();
			}

			$.post( ajaxurl, {
				'action' : 'ac_hide_notice_review'
			}, function() {
				if ( !soft ) {
					el.find( '.spinner' ).remove();
					el.slideUp();
				}
			} );

			return false;
		} );
	} );
} );