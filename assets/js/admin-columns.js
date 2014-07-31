jQuery( document ).ready( function( $ ) {
	cpac_tooltips();

	if ( typeof CPAC.storage_model !== 'undefined' && CPAC.storage_model.is_table_header_fixed ) {
		cpac_floatingheader();
	}
} );

/**
 * @since 2.2.4
 */
function cpac_tooltips() {
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

/**
 * @since 2.2.4
 */
function cpac_floatingheader() {
	var table = jQuery( 'table.wp-list-table.widefat' );
	var topscroll = 0;

	if ( jQuery( '#wpadminbar' ) ) {
		topscroll = 32;
	}

	table.floatThead( {
		scrollingTop: topscroll
	} );

}