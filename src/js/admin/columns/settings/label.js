import Modal from "../../../modules/modal";

class Label {
	constructor( column ) {
		this.column = column;

		this.setting = column.el.querySelector( '.ac-column-setting--label' );
		this.iconpicker = this.setting.querySelector( '.-iconpicker' );

		if ( !this.iconpicker ) {
			return;
		}

		this._dashicon = false;
		this.modal = AC.Modals.register( new Modal( this.setting.querySelector( '.-iconpicker' ) ) );
		this.field = this.setting.querySelector( '.ac-setting-input_label' );

		this.initValue();
		this.bindEvents();
	}

	initValue() {
		let self = this;
		let html = document.createRange().createContextualFragment( this.getValue() );
		let dashicon = html.querySelector( '.dashicons' );

		if ( dashicon ) {
			let classList = dashicon.classList;

			classList.forEach( cls => {
				if ( cls.indexOf( 'dashicons-' ) !== -1 ) {
					let selector = '.' + cls;
					let icon = self.iconpicker.querySelector( selector );

					if ( icon ) {
						icon.parentElement.classList.add( 'active' );
						self.setIconSelection( icon.parentElement.dataset.dashicon );
					}
				}
			} );
		} else {
			self.setIconSelection( false );
		}

	}

	bindEvents() {
		let self = this;

		this.setting.querySelector( '.ac-setting-label-icon' ).addEventListener( 'click', function( e ) {
			e.preventDefault();
			self.modal.open();
		} );

		this.setting.querySelector( '[data-action="submit"]' ).addEventListener( 'click', function( e ) {

			e.preventDefault();
			if ( self.getIconSelection() ) {
				self.setDashicon( self.getIconSelection() );
			}

			self.modal.close();
		} );

		let icons = this.iconpicker.querySelectorAll( '.ac-ipicker__icon' );
		icons.forEach( icon => {
			icon.addEventListener( 'click', function( e ) {
				e.preventDefault();

				let dashicon = this.dataset.dashicon;
				if ( dashicon ) {
					self.setIconSelection( dashicon );
				}

				let icons = self.setting.querySelectorAll( '.ac-ipicker__icon' );
				icons.forEach( icon => {
					icon.classList.remove( 'active' );
				} );

				icon.classList.add( 'active' );
			} );
		} );

	}

	getValue() {
		return this.field.value;
	}

	setValue( value ) {
		this.field.value = value;

		let event = new Event( 'change' );
		this.field.dispatchEvent( event );
	}

	setIconSelection( dashicon ) {
		let selection = this.setting.querySelector( '.ac-ipicker__selection' );
		this._dashicon = dashicon;
		selection.innerHTML = `<span class="dashicons dashicons-${dashicon}"></span>`;

		if ( !dashicon ) {
			selection.style.visibility = 'hidden';
		} else {
			selection.style.visibility = 'visible';
		}
	}

	getIconSelection() {
		return this._dashicon;
	}

	setDashicon( dashicon ) {
		this.setValue( `<span class="dashicons dashicons-${dashicon}"></span>` );
	}

}

let label = function( column ) {
	column.settings.label = new Label( column );
};

module.exports = label;