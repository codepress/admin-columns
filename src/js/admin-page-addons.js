import WPNotice from './modules/notice';

require( 'admin-columns-js/polyfill/nodelist' );

class AddonDownload {

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

		if ( button ) {
			button.insertAdjacentHTML( 'afterend', '<span class="spinner" style="visibility: visible;"></span>' );
			button.classList.add( 'button-disabled' );
		}

		this.loadingState = true;
	}

	removeLoadingState() {
		const button = this.getDownloadButton();
		const spinner = this.element.querySelector( '.spinner' );

		if ( spinner ) {
			spinner.remove();
		}

		if ( button ) {
			button.classList.remove( 'button-disabled' );
		}

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

	success( status ) {
		const button = this.getDownloadButton();
		const title = this.element.querySelector( 'h3' );
		const notice = new WPNotice();

		notice.setMessage( `<p>The Add-on <strong>${title.innerHTML}</strong> is installed.</p>` )
			.makeDismissable()
			.addClass( 'updated' );

		document.querySelector( '.ac-addons' ).insertAdjacentElement( 'beforebegin', notice.render() );

		if ( button ) {
			button.insertAdjacentHTML( 'beforebegin', `<span class="active">${status}</span>` );
			button.remove();
		}

	}

	static scrollToTop( ms ) {
		jQuery( 'html, body' ).animate( {
			scrollTop : 0
		}, ms );
	}

	failure( message ) {
		const title = this.element.querySelector( 'h3' );
		const notice = new WPNotice();

		notice.setMessage( `<p><strong>${title.innerHTML}</strong>: ${message}</p>` )
			.makeDismissable()
			.addClass( 'notice-error' );

		document.querySelector( '.ac-addons' ).insertAdjacentElement( 'beforebegin', notice.render() );
		AddonDownload.scrollToTop( 200 );
	}

	download() {
		let request = this.request();

		request.done( response => {
			this.removeLoadingState();
			if ( response.success ) {
				this.success( response.data.status );
			} else {
				this.failure( response.data );
			}
		} );
	}

	request() {
		let data = {
			action : 'acp-install-addon',
			plugin_name : this.slug,
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
		AC_Addons[ element.dataset.slug ] = new AddonDownload( element, element.dataset.slug );
	} );

} );