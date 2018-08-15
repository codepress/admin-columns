let indicator = function( column ) {
	let $column = column.$el;

	$column.find( '.ac-column-header [data-indicator-toggle]' ).each( function() {
		let $indicator = $( this );
		let setting = $( this ).data( 'setting' );
		let $setting = $column.find( '.ac-column-setting[data-setting=' + setting + ']' );
		let $input = $setting.find( '.col-input:first .ac-setting-input:first input[type=radio]' );

		$indicator.unbind( 'click' ).on( 'click', function( e ) {
			e.preventDefault();

			if ( $column.hasClass( 'disabled' ) ) {
				return;
			}

			$indicator.toggleClass( 'on' );
			if ( $( this ).hasClass( 'on' ) ) {
				$input.filter( '[value=on]' ).prop( 'checked', true ).trigger( 'click' ).trigger( 'change' );
			}
			else {
				$input.filter( '[value=off]' ).prop( 'checked', true ).trigger( 'click' ).trigger( 'change' );
			}
		} );

		$input.on( 'change', function() {
			if ( $column.hasClass( 'disabled' ) ) {
				return;
			}

			let value = $input.filter( ':checked' ).val();
			if ( 'on' === value ) {
				$indicator.addClass( 'on' );
			} else {
				$indicator.removeClass( 'on' );
			}
		} );
	} );
};

module.exports = indicator;