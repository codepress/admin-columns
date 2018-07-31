let image = function( column ) {
	function initState( $setting, $select ) {
		if ( 'cpac-custom' === $select.val() ) {
			$setting.find( '.ac-column-setting' ).show();
		} else {
			$setting.find( '.ac-column-setting' ).hide();
		}
	}

	column.$el.find( '.ac-column-setting--image' ).each( function() {
		let $setting = $( this );
		let $select = $( this ).find( '.ac-setting-input select' );

		initState( $setting, $select );
		$select.on( 'change', function() {
			initState( $setting, $( this ) );
		} );

	} );
};

module.exports = image;