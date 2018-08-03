let subsetting = function( column ) {
	let settings = {
		value_show : "on",
		subfield : '.ac-column-setting'
	};

	function initState( $setting, $input ) {
		let value = $input.filter( ':checked' ).val();
		let $subfields = $setting.find( settings.subfield );

		if ( settings.value_show === value ) {
			$subfields.show();
		} else {
			$subfields.hide();
		}
	}

	let $column = column.$el;
	let $settings = $column.find( '.ac-column-setting--filter,.ac-column-setting--sort,.ac-column-setting--edit' );

	$settings.each( function() {
		let $setting = $( this );
		let $input = $( this ).find( '.ac-setting-input input[type="radio"]' );

		initState( $setting, $input );
		$input.on( 'change', function() {
			initState( $setting, $input );
		} );

	} );
};

module.exports = subsetting;