import Tooltip from "./modules/tooltips";
import AcSection from "./modules/ac-section";
import Pointer from "./modules/ac-pointer";
import {initDismissibleNotices} from "./plugin/dismissible-notice";

const nanobus = require( 'nanobus' );

global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};

AdminColumns.events = nanobus();

jQuery( document ).ready( function( $ ) {
	initDismissibleNotices();

	if ( $( '#cpac' ).length === 0 ) {
		return false;
	}

	ac_pointers();

	document.querySelectorAll( '.ac-section' ).forEach( el => {
		new AcSection( el );
	} );
} );

/*
 * WP Pointer
 *
 */
global.ac_pointers = function() {
	let $ = jQuery;
	document.querySelectorAll( '.ac-pointer' ).forEach( element => {
		new Pointer( element );
	} );

	$( '.ac-wp-pointer' ).hover( function() {
		$( this ).addClass( 'hover' );
	}, function() {
		$( this ).removeClass( 'hover' );
		$( '.ac-pointer' ).trigger( 'close' );
	} ).on( 'click', '.close', function() {
		$( '.ac-pointer' ).removeClass( 'open' );
	} );

	new Tooltip();
};

global.ac_pointer = function( el ) {
	new Pointer( el );
};