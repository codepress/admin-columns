jQuery( function( $ ) {

	$( document ).on( 'click', '.ac-notice [data-dismiss]', function( e ) {
		e.preventDefault();

		let $notice = $( this ).parents( '.ac-notice' );
		let action = $notice.data( 'action' );
		let nonce = $notice.data( 'nonce' );

		$notice.fadeOut( 500, function() {
			$notice.remove();
		} );

		$.post( ajaxurl, {
			action : 'ac_notice_dismiss_' + action,
			_ajax_nonce : nonce
		} );
	} );

} );
