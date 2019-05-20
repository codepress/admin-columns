export default class Notice {
	constructor() {
		this.element = document.createElement( 'div' );
		this.element.classList.add( 'notice' );
	}

	setMessage( message ) {
		this.message = message;

		return this;
	}

	renderDimiss() {
		const button = document.createElement( 'button' );

		button.classList.add( 'notice-dismiss' );
		button.setAttribute( 'type', 'button' );
		button.insertAdjacentHTML( 'beforeend', `<span class="screen-reader-text">Dismiss this notice.</span>` );

		button.addEventListener( 'click', e => {
			e.preventDefault();
			this.element.remove();
		} );

		this.element.classList.add( 'is-dismissible' );
		this.element.insertAdjacentElement( 'beforeend', button );
	}

	renderContent() {
		this.element.insertAdjacentHTML( 'afterbegin', this.message );
	}

	makeDismissable() {
		this.dismissible = true;

		return this;
	}

	addClass( className ) {
		this.element.classList.add( className );

		return this;
	}

	render() {
		this.element.innerHTML = '';
		this.renderContent();
		if ( this.dismissible ) {
			this.renderDimiss();
		}

		return this.element;
	}

}