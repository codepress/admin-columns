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

class Modal {
	getMarkup(){
		`<div class="ac-modal -active">
			<div class="ac-modal__dialog">
				<div class="ac-modal__dialog__content">
					<p class="ac-modal__dialog__content__lead">
						Upgrade to PRO, and take Admin Columns to the next level:			</p>
					<ul class="ac-modal__dialog__list">
						<li>Sort &amp; Filter on all your content</li>
						<li>Directly edit your content from the overview</li>
						<li>Export all column data to CSV</li>
						<li>Create multiple column groups per overview</li>
						<li>Get add-ons for ACF, WooCommerce and many more</li>
					</ul>
				</div>
			
			</div>
		</div>`;
	}

	place(){
		document.createElement()
	}

	remove(){

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


