var nanobus = require( 'nanobus' );

class ListscreenInitialize {

	constructor( list_screens ) {
		this.list_screens = list_screens;
		this.processed = [];
		this.errors = [];
		this.success = [];
		this.events = nanobus();
	}

	doAjaxCall( list_screen ) {
		return jQuery.ajax( {
			url : list_screen.screen_link,
			method : 'get',
		} );
	}

	run() {
		Object.keys( this.list_screens ).forEach( key => {
			this.processListScreen( this.list_screens[ key ] );
		} );
	}

	onFinish() {
		if ( this.success.length === Object.keys( this.list_screens ).length ) {
			this.events.emit( 'success' );
		}

		if ( this.errors.length > 0 ) {
			this.events.emit( 'error' );
		}
	}

	checkFinish() {
		if ( this.processed.length === Object.keys( this.list_screens ).length ) {
			this.onFinish();
		}
	}

	processListScreen( list_screen ) {
		return this.doAjaxCall( list_screen )
			.done( r => {
				if ( r === 'ac_success' ) {
					this.success.push( list_screen );
				} else {
					this.errors.push( list_screen );
				}
			} )
			.fail( () => {
				this.errors.push( list_screen );
			} )
			.always( () => {
				this.processed.push( list_screen );
				this.checkFinish();
			} );
	}

}

export default class ListScreenInitializeController {

	constructor( list_screens ) {
		this.list_screens = list_screens;
		this.run();
	}

	run() {
		if ( Object.keys( this.list_screens ).length > 0 ) {
			// Only load main screen first if unitialized, otherwise do the rest in background
			if ( this.list_screens.hasOwnProperty( AC.list_screen ) ) {
				let main_initializer = new ListscreenInitialize( { [ AC.list_screen ] : this.list_screens[ AC.list_screen ] } );

				main_initializer.run();

				main_initializer.events.on( 'error', () => {
					document.querySelectorAll( '.ac-loading-msg-wrapper' ).forEach( el => el.remove() );
					document.querySelectorAll( '.menu' ).forEach( el => el.classList.remove( 'hidden' ) );
				} );

				main_initializer.events.on( 'success', () => {
					window.location = `${location.href}&t=${Date.now()}`;
				} );

			} else {
				let background_initializer = new ListscreenInitialize( this.list_screens );
				background_initializer.run();
			}

		}
	}

}