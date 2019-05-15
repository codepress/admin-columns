require( 'admin-columns-js/polyfill/nodelist' );

class WPNotice {
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

class AddonDownloader {

	constructor( el, slug ) {
		this.element = el;
		this.slug = slug;
		this.loadingState = false;

		this.initEvents();
	}

	getDownloadButton() {
		return this.element.querySelector( '[data-install]' );
	}

	setLoadingState() {
		const button = this.getDownloadButton();

		button.insertAdjacentHTML( 'afterend', '<span class="spinner" style="visibility: visible;"></span>' );
		button.classList.add( 'button-disabled' );
		this.loadingState = true;
	}

	removeLoadingState() {
		const button = this.getDownloadButton();
		const spinner = this.element.querySelector( '.spinner' );

		if ( spinner ) {
			spinner.remove();
		}

		button.classList.remove( 'button-disabled' );
		this.loadingState = false;
	}

	initEvents() {
		const button = this.getDownloadButton();

		if ( button ) {
			button.addEventListener( 'click', e => {
				e.preventDefault();

				if ( this.loadingState ) {
					return;
				}

				this.setLoadingState();
				this.download();
			} );
		}
	}

	success() {
		const title = this.element.querySelector( 'h3' );
		const notice = new WPNotice();
		notice.setMessage( `<p>The Add-on <strong>${title.innerHTML}</strong> is installed.</p>` )
			.makeDismissable()
			.addClass( 'updated' );

		document.querySelector( '.ac-addons' ).insertAdjacentElement( 'beforebegin', notice.render() );
	}

	failure( message ){
		const title = this.element.querySelector( 'h3' );
		const notice = new WPNotice();
		notice.setMessage( `<p>The installation of the Add-on <strong>${title.innerHTML}</strong> has failed with the following message: ${message}</p>` )
			.makeDismissable()
			.addClass( 'notice-error' );

		document.querySelector( '.ac-addons' ).insertAdjacentElement( 'beforebegin', notice.render() );
	}

	download() {
		let request = this.request();

		request.done( response => {
			this.removeLoadingState();
			if( response.success ){
				this.success();
			} else {
				this.failure( response.data );
			}
		} );
	}

	request() {
		let data = {
			action : 'acp-install-addon',
			plugin_name : 'ac-addon-ninjaforms',
			_ajax_nonce : AC.ajax_nonce
		};

		return jQuery.ajax( {
			url : ajaxurl,
			method : 'post',
			data : data
		} );
	}

}

document.addEventListener( "DOMContentLoaded", function() {
	global.AC_Addons = [];

	document.querySelectorAll( '.ac-addon' ).forEach( element => {
		AC_Addons[ element.dataset.slug ] = new AddonDownloader( element, element.dataset.slug );
	} );

} );