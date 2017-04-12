jQuery( document ).ready( function( $ ) {
	ac_quickedit_events( $ );
	ac_set_column_classes( $ );
	ac_actions_column( $, $( '.column-actions' ) );
	ac_tooltips( $ );
	ac_show_more( $ );
	ac_edit_button( $ );

	$( '.wp-list-table' ).on( 'updated', 'tr', function() {
		ac_set_column_classes( $ );
		ac_actions_column( $, $( this ).find( '.column-actions' ) );
		ac_show_more( $ );
	} );
} );

function ac_edit_button( $ ) {
	if ( AC.edit_link ) {
		$( '.tablenav.top .actions:last' ).append( '<a class="cpac-edit add-new-h2" href="' + AC.edit_link + '">' + AC.i18n.edit_columns + '</a>' );
	}
}

function ac_show_more( $ ) {
	$( '.ac-more-link-show' ).click( function( e ) {
		e.preventDefault();
		var td = $( this ).hide().closest( 'td' );

		td.find( '.ac-show-more-block' ).show();

	} );
	$( '.ac-more-link-hide' ).click( function( e ) {
		e.preventDefault();
		var td = $( this ).closest( 'td' );

		td.find( '.ac-more-link-show' ).show();
		td.find( '.ac-show-more-block' ).hide();
	} );
}

function ac_actions_column( $, $selector ) {
	$( $selector ).each( function() {
		var $column = $( this );

		if ( $column.find( '.cpac_use_icons' ).length > 0 ) {
			$column.addClass( 'cpac_use_icons' );
		}
	} );

	$( $selector ).find( '.cpac_use_icons + .hidden + .row-actions > span' ).each( function() {
		var $link = $( this ).find( 'a' );
		$link.attr( 'data-ac-tip', $link.text() ).addClass( 'ac-tip' );
	} );
}

function ac_set_column_classes( $ ) {
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
function ac_tooltips( $ ) {

	if ( typeof $.fn.qtip === 'undefined' ) {
		return;
	}

	$( '[data-ac-tip]' ).qtip( {
		content : {
			attr : 'data-ac-tip'
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

function ac_quickedit_events( $ ) {

	$( document ).ajaxComplete( function( event, request ) {
		var $result = $( '<div>' ).append( request.responseText );

		if ( $result.find( 'tr.iedit' ).length == 1 ) {
			var id = $result.find( 'tr.iedit' ).attr( 'id' );

			$( 'tr#' + id ).trigger( 'updated' );
		}
	} );
}