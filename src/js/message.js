jQuery( function( $ ) {

	$( document ).on( 'click', '.ac-notice [data-dismiss], .ac-notice button.notice-dismiss', function( e ) {
		e.preventDefault();

		let $notice = $( this ).parents( '.ac-notice' );
		let key = $notice.data( 'key' );

		if ( !key ) {
			return false;
		}

		$notice.fadeOut( 500, function(){
			$notice.remove();
		});

		$.post( ajaxurl, {
			action : 'ac_dismiss_notice',
			key : key
		} );

	} );

} );
