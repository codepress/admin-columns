jQuery( document ).ready( function( $ ) {
	cpac_actions_column( $ );
	cpac_tooltips( $ );
	cpac_quickedit_events( $ );
	cpac_set_column_classes( $ )
} );

function cpac_actions_column( $ ) {
	$( '.column-actions .cpac_use_icons + .row-actions > span' ).each( function() {
		var $link = $( this ).find( 'a' );
		$link.attr( 'data-tip', $link.text() ).addClass( 'cpac-tip' );
	} );
}

function cpac_set_column_classes( $ ) {
	for ( var name in AC.column_types ) {
		if ( AC.column_types.hasOwnProperty( name ) ) {
			var type = AC.column_types[ name ];

			$( '.wp-list-table td.' + name ).addClass( type );
		}
	}
}

/**
 * @since 2.2.4
 */
function cpac_tooltips( $ ) {

	if ( typeof $.fn.qtip === 'undefined' ) {
		return;
	}

	$( '.cpac-tip' ).qtip( {
		content : {
			attr : 'data-tip'
		},
		position : {
			my : 'top center',
			at : 'bottom center'
		},
		style : {
			tip : true,
			classes : 'qtip-tipsy'
		}
	} );
}

function cpac_quickedit_events( $ ) {

	$( document ).ajaxComplete( function( event, request, settings ) {
		var $result = $( '<div>' ).append( request.responseText );
		if ( $result.find( 'tr.iedit' ).length == 1 ) {
			var id = $result.find( 'tr.iedit' ).attr( 'id' );
			$( 'tr#' + id ).trigger( 'updated' );
		}
	} );
}