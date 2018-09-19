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
			jQuery( this ).toggleClass( '-open' );
		} );
	}

}

module.exports = Actions;