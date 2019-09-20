var nanobus = require( 'nanobus' );

class ListscreenInitialize {

	constructor( list_screens ) {
		this.list_screens = list_screens;
		this.processing = [];
		this.errors = [];
		this.events = nanobus();
	}

	initListScreen( list_screen ) {
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

	getNextItem() {
		return this.list_screens.shift();
	}

	checkFinish() {
		if ( Object.keys( this.processing ).length > 0 ) {
			return;
		}

		if ( Object.keys( this.errors ).length > 0 ) {
			this.events.emit( 'error' );
			return;
		}

		this.events.emit( 'success' );
	}

	processListScreen( list_screen ) {
		this.processing.push( list_screen.label );
		this.initListScreen( list_screen ).done( ( r ) => {
			this.processing.shift();

			if ( r !== '1' ) {
				this.errors.push( list_screen );
			}
			this.checkFinish();

		} ).error( () => {
			this.processing.shift();
			this.errors.push( list_screen );
		} )
	}

}

export default class ListScreenInitializeController {

	constructor( list_screens ) {
		this.list_screens = list_screens;
		this.run();
	}

	run() {
		if ( Object.keys( this.list_screens ).length > 0 ) {

			if ( this.list_screens.hasOwnProperty( AC.list_screen ) ) {
				let main_initializer = new ListscreenInitialize( [ this.list_screens[ AC.list_screen ] ] );
				main_initializer.run();
				main_initializer.events.on( 'error', () => {
					let notice = document.querySelector( '.ac-notice.visit-ls' );
					let loading = document.querySelector( '.ac-loading-msg-wrapper' );
					let menu = document.querySelector( '.menu' );

					if ( notice ) {
						notice.style.display = 'block';
					}

					if ( loading ) {
						loading.remove();
					}

					if ( menu ) {
						menu.classList.remove( 'hidden' );
					}
				} );

				main_initializer.events.on( 'success', () => {
					location.reload( true );
				} );
			}

			let background_initializer = new ListscreenInitialize( this.list_screens );
			background_initializer.run();
		}
	}

}