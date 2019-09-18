export default class ListscreenInitialize {

	constructor( list_screens ) {
		this.list_screens = list_screens;
		this.treads = 6;
		this.processing = [];
	}

	initListScreen( list_screen ) {
		return jQuery.ajax( {
			url : list_screen.screen_link,
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

	checkFinish(){

		if( this.processing.length > 0 ){
			return;
		}

		location.reload(true);
	}

	processNext(){
		let list_screen = this.getNextItem();

		if( ! list_screen ){
			return this.checkFinish();
		}

		this.processing.push( list_screen.label );

		this.initListScreen( list_screen ).done( d => {
			this.processing.shift();
			this.processNext();
		})
	}
}