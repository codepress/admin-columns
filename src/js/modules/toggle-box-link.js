import {insertAfter} from "../../../../src/editing/js/helpers/elements";

export default class ToggleBoxLink {

	constructor( el ) {
		this.element = el;
		//this.initEvents();

		this.contentBox = this.element.parentElement.querySelector( '.ac-toggle-box-contents' )
		if ( !this.contentBox ) {
			this.createContenBox();
		}
	}

	isAjax() {
		return parseInt( this.element.dataset.ajaxPopulate ) === 1;
	}

	isInited() {
		return this.element.dataset.toggleBoxInit;
	}

	createContenBox() {
		let contentBox = document.createElement( 'div' );

		contentBox.classList.add( 'ac-toggle-box-contents' );

		insertAfter( contentBox, this.element );

		this.contentBox = contentBox;

		return this.contentBox;
	}

	initEvents() {
		if ( this.isInited() ) {
			return;
		}

		this.element.addEventListener( 'click', ( e ) => {
			e.preventDefault();

			if ( this.isAjax() && !this.hasContent() ) {
				this.manageAjaxValue();
			}

			this.toggleContentBox();
		} );

		this.element.dataset.toggleBoxInit = true;
	}

	hasContent() {
		return this.getContentBox().innerHTML.length > 0
	}

	setContent( content ) {
		this.getContentBox().innerHTML = content;
	}

	getContentBox() {
		if ( !this.contentBox ) {

			return this.createContenBox();
		}

		return this.contentBox;
	}

	setLabel( open ) {
		let label = this.element.dataset.label;

		if ( open && this.element.dataset.labelClose ) {
			label = this.element.dataset.labelClose
		}

		this.element.innerHTML = label + '<span class="spinner"></span>';
	}

	toggleContentBox() {
		if ( this.getContentBox().classList.contains( '-open' ) ) {
			this.getContentBox().classList.remove( '-open' );
			this.setLabel( false );
		} else {
			this.getContentBox().classList.add( '-open' );
			this.setLabel( true );
		}
	}

	manageAjaxValue() {
		this.element.classList.add( 'loading' );
		this.retrieveAjaxValue().done( response => {
			this.setContent( response );

			jQuery( this.element.parentElement ).trigger( 'ajax_column_value_ready' );
			AdminColumns.Tooltips.init();

		} ).always( () => {
			this.element.classList.remove( 'loading' );
		} );

	}

	retrieveAjaxValue() {
		return jQuery.ajax( {
			url : ajaxurl,
			method : 'POST',
			data : {
				action : 'ac_get_column_value',
				list_screen : AC.list_screen,
				layout : AC.layout,
				column : this.element.dataset.column,
				pk : this.element.dataset.itemId,
				_ajax_nonce : AC.ajax_nonce
			}
		} );
	}

}