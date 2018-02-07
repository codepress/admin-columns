jQuery( function( $ ) {

	$( document ).on( 'click', '.ac-notice [data-dismiss], .ac-notice button.notice-dismiss', function() {
		let $notice = $( this ).parents( '.ac-notice' );
		let key = $notice.data( 'key' );

		if ( !key ) {
			return false;
		}

		setTimeout( function(){
			"use strict";
			$notice.fadeOut().remove();
	}, 3000 );

		$.get( ajaxurl, {
			action : 'ac_dismiss_notice',
			key : key
		}, function() {
			$notice.fadeOut().remove();
		} );

	} );

} );
