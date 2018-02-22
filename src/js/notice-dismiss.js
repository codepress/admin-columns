jQuery( function( $ ) {

	$( document ).on( 'click', '.ac-notice .notice-dismiss', function( e ) {
		e.preventDefault();

		let $notice = $( this ).parents( '.ac-notice' );
		let action = $( this ).data( 'action' );

		$notice.fadeOut( 500, function() {
			$notice.remove();
		} );

		$.post( ajaxurl, {
			action : 'ac_notice_dismiss_' + action,
			_ajax_nonce : $( this ).data( 'nonce' )
		} );
	} );

} );
