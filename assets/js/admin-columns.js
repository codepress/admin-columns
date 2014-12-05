jQuery( document ).ready( function( $ ) {
	cpac_tooltips();
} );

/**
 * @since 2.2.4
 */
function cpac_tooltips() {

	if ( typeof qtip === 'undefined' || ! jQuery.isFunction( qtip ) ) {
		return;
	}

	jQuery( '.cpac-tip' ).qtip( {
		content: {
			attr: 'data-tip'
		},
		position: {
			my: 'top center',
			at: 'bottom center'
		},
		style: {
			tip: true,
			classes: 'qtip-tipsy'
		}
	} );
}