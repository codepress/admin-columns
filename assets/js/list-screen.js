jQuery( document ).ready( function( $ ) {
	cpac_actions_column();
	cpac_tooltips();
	cpac_quickedit_events();
} );


function cpac_actions_column(){
	jQuery('.column-actions .cpac_use_icons + .row-actions > span').each( function(){
		var $link = jQuery(this).find('a');
		$link.attr( 'data-tip', $link.text() ).addClass('cpac-tip');
	});
}

/**
 * @since 2.2.4
 */
function cpac_tooltips() {

	if ( typeof jQuery.fn.qtip === 'undefined' ) {
		return;
	}

	jQuery( '.cpac-tip' ).qtip( {
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

function cpac_quickedit_events() {
	var $ = jQuery;

	$( document ).ajaxComplete( function( event, request, settings ) {
		var $result = $( '<div>' ).append( request.responseText );
		if ( $result.find( 'tr.iedit' ).length == 1 ) {
			var id = $result.find( 'tr.iedit' ).attr( 'id' );
			$( 'tr#' + id ).trigger( 'updated' );
		}
	} );
}