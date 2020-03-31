export default class Pointer {

	constructor( el ) {
		this.el = el;
		this.settings = this.getDefaults();
		this.init();

		this.setInitialized();
	}

	setInitialized() {
		this.el.dataset.ac_pointer_initialized = 1;
	}

	getDefaults() {
		return {
			width : this.el.getAttribute( 'data-width' ) ? this.el.getAttribute( 'data-width' ) : 250,
			noclick : this.el.getAttribute( 'data-noclick' ) ? this.el.getAttribute( 'data-noclick' ) : false,
			position : this.getPosition()
		}
	}

	isInitialized() {
		return this.el.dataset.hasOwnProperty( 'ac_pointer_initialized' );
	}

	init() {
		if ( this.isInitialized() ) {
			return;
		}

		// create pointer
		jQuery( this.el ).pointer( {
			content : this.getRelatedHTML(),
			position : this.settings.position,
			pointerWidth : this.settings.width,
			pointerClass : this.getPointerClass()
		} );

		this.initEvents();
	}

	getPosition() {
		let position = {
			at : 'left top',		// position of wp-pointer relative to the element which triggers the pointer event
			my : 'right top',	// position of wp-pointer relative to the at-coordinates
			edge : 'right',		// position of arrow
		};

		let pos = this.el.getAttribute( 'data-pos' );
		let edge = this.el.getAttribute( 'data-pos_edge' );

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

		if ( edge ) {
			position.edge = edge;
		}

		return position;

	}

	getPointerClass() {
		let classes = [
			'ac-wp-pointer',
			'wp-pointer',
			'wp-pointer-' + this.settings.position.edge
		];

		if ( this.settings.noclick ) {
			classes.push( 'noclick' );
		}

		return classes.join( ' ' );
	}

	getRelatedHTML() {
		let related_element = document.getElementById( this.el.getAttribute( 'rel' ) );

		return related_element ? related_element.innerHTML : ''
	}

	initEvents() {
		let el = jQuery( this.el );

		// click
		if ( !this.settings.noclick ) {
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
	}
}