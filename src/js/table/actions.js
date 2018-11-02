class Actions {
	constructor( id ) {
		this.container = document.getElementById( id );
		this.buttons = this.container.querySelector( '.ac-table-actions-buttons' );

		this.init();
	}

	init() {
		let self = this;

		this.dropDownEvents();

		jQuery( this.container ).on( 'update', function() {
			self.refresh();
		} ).insertAfter( jQuery( '.tablenav.top .actions:last' ) ).addClass( '-init' ).trigger( 'update' );
	}

	refresh() {
		let $buttons = jQuery( this.buttons );

		$buttons.find( '> a' ).removeClass( 'last' );
		$buttons.find( '> a:visible:last' ).addClass( 'last' );
	}

	dropDownEvents() {
		jQuery( this.buttons ).on( 'click', '[data-dropdown]', function() {
			let $button = jQuery( this );
			$button.toggleClass( '-open' );

			if ( $button.hasClass( '-open' ) ) {
				$button[ 0 ].dispatchEvent( new CustomEvent( 'open' ) );
			} else {
				$button[ 0 ].dispatchEvent( new CustomEvent( 'closed' ) );
			}
		} );
	}

}

module.exports = Actions;