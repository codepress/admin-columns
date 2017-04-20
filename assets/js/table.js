jQuery( document ).ready( function( $ ) {
	ac_quickedit_events( $ );
	ac_set_column_classes( $ );
	ac_actions_column( $, $( '.column-actions' ) );
	ac_tooltips( $ );
	ac_show_more( $ );
	ac_edit_button( $ );
	ac_toggle_box( $ );
	ac_toggle_box_ajax_init( $ );

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

function ac_toggle_box( $ ) {
	$( '.ac-toggle-box-link' ).click( function( e ) {
		e.preventDefault();
		$( this ).next( '.ac-toggle-box-contents' ).toggle();
	} );
}

function ac_toggle_box_ajax_init( $ ) {

	/**
	 * Toggle box
	 */
	var do_toggle_value = function( e ) {
		e.preventDefault();

		$( this ).next( '.ac-toggle-box-contents-ajax' ).toggle();
	};

	/**
	 * Retrieves the contents from the column through ajax
	 */
	var do_retrieve_ajax_value = function( e ) {
		e.preventDefault();

		var $this = $( this );

		var data = {
			action : 'ac_get_column_value',
			list_screen : AC.list_screen,
			layout : AC.layout,
			column : $this.data( 'column' ),
			pk : $this.attr( 'data-item-id' ),
			_ajax_nonce : AC.ajax_nonce
		};

		$this.addClass( 'loading' );

		var xhr = $.post( ajaxurl, data, function( response ) {
			if ( response ) {
				$this.after( '<div class="ac-toggle-box-contents-ajax">' + response + '</div>' );

				// We only need to run the ajax request once. Unbind the event, and replace with a Toggle Box.
				$this.unbind( 'click', do_retrieve_ajax_value ).bind( 'click', do_toggle_value );

				// Added hook on Table Cell
				$( $this.parent( 'td' ) ).trigger( 'ajax_column_value_ready' );

				// Re-init tooltips
				ac_tooltips( $ );
			}
		} );

		xhr.always( function() {
			$this.removeClass( 'loading' );
		} );
	};

	// Click event
	$( 'a[data-ajax-populate=1]' ).bind( 'click', do_retrieve_ajax_value );
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

		if ( $result.find( 'tr.iedit' ).length === 1 ) {
			var id = $result.find( 'tr.iedit' ).attr( 'id' );

			$( 'tr#' + id ).trigger( 'updated' );
		}
	} );
}