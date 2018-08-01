class Actions {
	constructor( el ) {
		this.$el = jQuery( el );

		this.init();
	}

	init() {
		let self = this;

		this.$el.on( 'update', function() {
			self.refresh();
		} ).insertAfter( jQuery( '.tablenav.top .actions:last' ) ).trigger( 'update' );
	}

	refresh() {
		let $buttons = this.$el.find( '.ac-table-actions-buttons' );

		$buttons.find( '> a' ).removeClass( 'last' );
		$buttons.find( '> a:visible:last' ).addClass( 'last' );
	}
}

module.exports = Actions;