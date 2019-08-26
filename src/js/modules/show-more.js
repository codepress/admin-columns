export default class ShowMore {

	constructor( el ) {
		this.el = el;

		this.initEvents();
	}

	initEvents() {
		if ( this.isInited() ) {
			return;
		}

		if( this.getToggler() ){
			this.getToggler().addEventListener( 'click', event => {
				event.preventDefault();
				event.stopPropagation();
				this.toggle();
			} );
		}

		this.el.dataset.showMoreInit = true;
	}

	getToggler() {
		return this.el.querySelector( '.ac-show-more__toggle' );
	}

	isInited() {
		return this.el.dataset.showMoreInit;
	}

	toggle() {
		if ( this.el.classList.contains( '-on' ) ) {
			this.hide();
		} else {
			this.show();
		}
	}

	show() {
		this.el.classList.add( '-on' );
		this.getToggler().innerHTML = this.getToggler().dataset.less;
	}

	hide() {
		this.el.classList.remove( '-on' );
		this.getToggler().innerHTML = this.getToggler().dataset.more;
	}

}