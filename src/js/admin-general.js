import Tooltip from "./modules/tooltips";
import AcSection from "./modules/ac-section";

global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};

jQuery( document ).ready( function( $ ) {
	if ( $( '#cpac' ).length === 0 ) {
		return false;
	}

	ac_pointers( $ );
	ac_help( $ );

	document.querySelectorAll( '.ac-section' ).forEach( el => {
		new AcSection( el );
	} );
} );

/*
 * WP Pointer
 *
 */
global.ac_pointers = function( $ ) {

	$( '.ac-pointer' ).each( function() { ac_pointer( $( this ) ) } );

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

global.ac_pointer = function( $el ) {
	let el = $el.first(),
		$ = jQuery,
		html = el.attr( 'rel' ),
		pos = el.attr( 'data-pos' ),
		pos_edge = el.attr( 'data-pos_edge' ),
		w = el.attr( 'data-width' ),
		noclick = el.attr( 'data-noclick' );

	let position = {
		at : 'left top',		// position of wp-pointer relative to the element which triggers the pointer event
		my : 'right top',	// position of wp-pointer relative to the at-coordinates
		edge : 'right',		// position of arrow
	};

	if ( $el[ 0 ].dataset.hasOwnProperty( 'acpointer' ) ) {
		return;
	}

	let width = w ? w : 250;

	if ( 'right' === pos ) {
		position = {
			at : 'right middle',
			my : 'left middle',
			edge : 'left'
		};
	}

	if ( 'right_bottom' === pos ) {
		position = {
			at : 'right middle',
			my : 'left bottom',
			edge : 'none'
		};
	}

	if ( 'left' === pos ) {
		position = {
			at : 'left middle',
			my : 'right middle',
			edge : 'right'
		};
	}

	if ( pos_edge ) {
		position.edge = pos_edge;
	}

	// create pointer
	el.pointer( {
		content : $( '#' + html ).html(),
		position : position,
		pointerWidth : width,
		// bug fix. with an arrow on the right side the position of wp-pointer is incorrect. it does not take
		// into account the padding of the arrow. adding "wp-pointer-' + position.edge"  will fix that.
		pointerClass : 'ac-wp-pointer wp-pointer wp-pointer-' + position.edge + (noclick ? ' noclick' : '')
	} );

	// click
	if ( !noclick ) {
		el.click( function() {
			if ( el.hasClass( 'open' ) ) {
				el.removeClass( 'open' );
			} else {
				el.addClass( 'open' );
			}
		} );
	}

	el.click( function() {
		el.pointer( 'open' );
	} );

	el.mouseenter( function() {
		el.pointer( 'open' );
		setTimeout( () => {
			el.pointer( 'open' );
		}, 2 );
	} );

	el.mouseleave( function() {
		setTimeout( () => {
			if ( !el.hasClass( 'open' ) && jQuery( '.ac-wp-pointer.hover' ).length === 0 ) {
				el.pointer( 'close' );
			}
		}, 1 );
	} );

	el.on( 'close', () => {
		setTimeout( () => {
			if ( !el.hasClass( 'open' ) ) {
				el.pointer( 'close' );
			}
		} )
	} );

	$el[ 0 ].dataset.acpointer = 1;

};

/*
 * Help
 *
 * usage: <a href="javascript:;" class="help" data-help="tab-2"></a>
 */
function ac_help( $ ) {
	$( 'a.help' ).click( function( e ) {
		e.preventDefault();

		let panel = $( '#contextual-help-wrap' );

		panel.parent().show();
		$( 'a[href="#tab-panel-cpac-' + $( this ).attr( 'data-help' ) + '"]', panel ).trigger( 'click' );
		panel.slideDown( 'fast', function() {
			panel.focus();
		} );
	} );
}