import Table from "./table/table";
import Tooltip from "./table/tooltips";
import Modals from "./modules/modals";
import ScreenOptionsColumns from "./table/screen-options-columns";
import ShowMore from "./modules/show-more";
import ToggleBoxLink from "./modules/toggle-box-link";

// Register the global variable
global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};

Modals.init();

jQuery( document ).ready( function( $ ) {
	ac_quickedit_events( $ );
	ac_actions_column( $, $( '.column-actions' ) );
	ac_show_more( $ );
	ac_actions_tooltips( $ );
	ac_toggle_box();

	let table = document.querySelector( AC.table_id );

	if ( table ) {
		ac_load_table( table.parentElement );

		AdminColumns.ScreenOptionsColumns = new ScreenOptionsColumns( AdminColumns.Table.Columns );
	}

	AdminColumns.Tooltips = new Tooltip();

	$( '.wp-list-table' ).on( 'updated', 'tr', function() {
		AdminColumns.Table.addCellClasses();
		ac_actions_column( $, $( this ).find( '.column-actions' ) );
		ac_show_more( $ );
	} );

	$( '.wp-list-table td' ).on( 'ACP_InlineEditing_After_SetValue', function() {
		ac_show_more( $ );
	} );

} );

global.ac_toggle_box = function() {

	document.querySelectorAll( '.ac-toggle-box-link' ).forEach( el => {
		new ToggleBoxLink( el );
	} );
};

global.ac_load_table = function( el ) {
	AdminColumns.Table = new Table( el );
	AC.Table = AdminColumns.Table; // TODO use AdminColumns instead of AC
};

function ac_actions_tooltips() {

	jQuery( '.cpac_use_icons' ).parent().find( '.row-actions a' ).qtip( {
		content : {
			text : function() {
				return jQuery( this ).text();
			}
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

global.ac_show_more = function( $ ) {
	document.querySelectorAll( '.ac-show-more' ).forEach( el => {
		new ShowMore( el );
	} );
};

function ac_actions_column( $, $selector ) {
	$( $selector ).each( function() {
		let $column = $( this );

		if ( $column.find( '.cpac_use_icons' ).length > 0 ) {
			$column.addClass( 'cpac_use_icons' );
		}
	} );

	$( $selector ).find( '.cpac_use_icons + .hidden + .row-actions > span' ).each( function() {
		let $link = $( this ).find( 'a' );
		$link.attr( 'data-ac-tip', $link.text() ).addClass( 'ac-tip' );
	} );
}

function ac_quickedit_events( $ ) {

	$( document ).ajaxComplete( function( event, request ) {
		let ownerDocument = document.implementation.createHTMLDocument( 'quickeditevents' );
		let $result = $( '<div>', ownerDocument );

		$result.append( request.responseText );
		if ( $result.find( 'tr.iedit' ).length === 1 ) {
			let id = $result.find( 'tr.iedit' ).attr( 'id' );

			$( 'tr#' + id ).trigger( 'updated', { id : id } )
		}
	} );

}

/** CustomEvent Polyfill */
(function() {

	if ( typeof window.CustomEvent === "function" ) {
		return false;
	}

	function CustomEvent( event, params ) {
		params = params || { bubbles : false, cancelable : false, detail : undefined };
		let evt = document.createEvent( 'CustomEvent' );
		evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
		return evt;
	}

	CustomEvent.prototype = window.Event.prototype;

	window.CustomEvent = CustomEvent;
})();