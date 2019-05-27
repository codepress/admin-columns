global.AdminColumns = typeof AdminColumns !== "undefined" ? AdminColumns : {};

jQuery( document ).ready( function( $ ) {
	if ( $( '#cpac' ).length === 0 ) {
		return false;
	}

	ac_pointers( $ );
	ac_help( $ );
} );

/*
 * WP Pointer
 *
 */
function ac_pointers( $ ) {

	$( '.ac-pointer' ).each( function() { ac_pointer( $(this) ) } );

	$( '.ac-wp-pointer' ).hover( function() {
		$( this ).addClass( 'hover' );
	}, function() {
		$( this ).removeClass( 'hover' );
		$( '.ac-pointer' ).trigger( 'close' );
	} );
}

function ac_pointer( $el ) {
	let el = $el,
		$ = jQuery,
		html = el.attr( 'rel' ),
		pos = el.attr( 'data-pos' ),
		w = el.attr( 'data-width' ),
		noclick = el.attr( 'data-noclick' );

	let position = {
		at : 'left top',		// position of wp-pointer relative to the element which triggers the pointer event
		my : 'right top',	// position of wp-pointer relative to the at-coordinates
		edge : 'right',		// position of arrow
	};

	let width = w ? w : 250;

	if ( 'right' === pos ) {
		position = {
			at : 'right middle',
			my : 'left middle',
			edge : 'left'
		};
	}

	if ( 'left' === pos ) {
		position = {
			at : 'left middle',
			my : 'right middle',
			edge : 'right'
		};
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
			}
			else {
				el.addClass( 'open' );
			}
		} );
	}

	// show on hover
	el.hover( function() {
		$( this ).pointer( 'open' );
	}, function() {
		let el = $( this );
		setTimeout( function() {
			if ( !el.hasClass( 'open' ) && $( '.ac-wp-pointer.hover' ).length == 0 ) {
				el.pointer( 'close' );
			}
		}, 100 );

	} ).on( 'close', function() {
		if ( !el.hasClass( 'open' ) && $( '.ac-wp-pointer.hover' ).length == 0 ) {
			el.pointer( 'close' );
		}
	} );
}

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