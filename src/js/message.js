jQuery( function( $ ) {

	$( document ).on( 'click', '.ac-notice [data-dismiss], .ac-notice button.notice-dismiss', function() {
		let $notice = $( this ).parents( '.ac-notice' );
		let key = $notice.data( 'key' );

		if ( !key ) {
			return false;
		}

		$.post( ajaxurl, {
			action : 'ac_dismiss_notice',
			key : key
		}, function() {
			$notice.fadeOut().remove();
		} );

	} );

} );
