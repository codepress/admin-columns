class Modal {
	constructor( el ) {
		this.el = el;
		this.dialog = el.querySelector( '.ac-modal__dialog' );

		this.initEvents();
	}

	static initGlobalEvents() {
		let buttons = document.querySelectorAll( '[data-ac-open-modal]' );
		if ( buttons.length ) {
			buttons.forEach( ( button ) => {

				button.addEventListener( 'click', ( e ) => {
					let target = e.target.dataset.acOpenModal;
					let el = document.querySelector( target );

					if ( el && el.AC_MODAL ) {
						el.AC_MODAL.open();
					}
				} );

			} );
		}
		//document.addEventListener( 'click' )
	}

	initEvents() {
		let self = this;

		document.addEventListener( 'keydown', ( e ) => {
			const keyName = event.key;

			if ( !this.isOpen() ) {
				return;
			}

			if ( 'Escape' === keyName ) {
				this.close();
			}
		} );

		let dismissButtons = this.el.querySelectorAll( '[data-dismiss="modal"]' );
		if ( dismissButtons.length > 0 ) {
			dismissButtons.forEach( ( b ) => {
				b.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					self.close();
				} );
			} );
		}

		this.el.addEventListener( 'click', () => {
			self.close();
		} );

		this.el.querySelector( '.ac-modal__dialog' ).addEventListener( 'click', ( e ) => {
			e.stopPropagation();
		} );

		if ( typeof document.querySelector( 'body' ).dataset.ac_modal_init === 'undefined' ) {
			Modal.initGlobalEvents();
			document.querySelector( 'body' ).dataset.ac_modal_init = 1;
		}

		this.el.AC_MODAL = self;
	}

	isOpen() {
		return this.el.classList.contains( '-active' );
	}

	close() {
		this.el.classList.remove( '-active' );
	}

	open() {
		this.el.classList.add( '-active' );
	}
}

module.exports = Modal;