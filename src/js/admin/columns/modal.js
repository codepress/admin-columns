class Modal {

	init() {
		$( document ).on( 'click', '[data-ac-open-modal]', function( e ) {
			e.preventDefault();

			$( $( this ).data( 'ac-open-modal' ) ).addClass( '-active' );
		} );

		$( '.ac-modal__dialog__close' ).on( 'click', function( e ) {
			e.preventDefault();

			$( this ).closest( '.ac-modal' ).removeClass( '-active' );
		} );

		$( '.ac-modal' ).on( 'click', function( e ) {
			$( this ).removeClass( '-active' );
		} );

		// Prevent bubbling
		$( '.ac-modal__dialog' ).on( 'click', function( e ) {
			e.stopPropagation();
		} );

		$( document ).keyup( function( e ) {
			if ( e.keyCode === 27 ) {
				$( '.ac-modal' ).removeClass( '-active' );
			}
		} );

	}

}

module.exports = Modal;