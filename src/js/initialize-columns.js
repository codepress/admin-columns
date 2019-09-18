class InitializeColumns {

	constructor( list_screens ) {
		this.list_screens = list_screens;
		this.treads = 6;
	}

	initListScreen( list_screen ) {
		return jQuery.ajax( {
			//url : list_screen.screen_link,
			url: 'https://firstvisit.test',
			method : 'get',
		} );
	}

	run(){
		for( let i=0; i<this.treads; i++ ){
			this.processNext();
		}

	}

	getNextItem(){
		return this.list_screens.shift();
	}

	processNext(){
		let list_screen = this.getNextItem();

		if( ! list_screen ){
			return;
		}

		this.initListScreen( list_screen ).done( d => {
			this.processNext();
		})
	}
}

jQuery( document ).ready( function() {


	let initializer = new InitializeColumns( AC_INIT_LISTSCREENS );
	initializer.run();

	let overlay = document.createElement('div' );
	overlay.classList.add('ac-modal','-active', '-dblocking');

	//document.getElementById('wpcontent' ).insertAdjacentElement( 'beforebegin', overlay );
	//document.body.appendChild( overlay );





});


