export default class AcSection {

	constructor( el ) {
		this.element = el;
		this.init();
	}

	init() {
		if ( this.element.classList.contains( '-closable' ) ) {
			const header = this.element.querySelector( '.ac-section__header' );

			if ( header ) {
				header.addEventListener( 'click', () => {
					this.toggle();
				} );
			}
		}

	}

	toggle() {
		this.isOpen() ? this.close() : this.open();
	}

	isOpen() {
		return !this.element.classList.contains( '-closed' );
	}

	open() {
		this.element.classList.remove( '-closed' );
	}

	close() {
		this.element.classList.add( '-closed' );
	}

}