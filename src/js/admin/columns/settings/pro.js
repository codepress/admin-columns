let pro = function( column ) {
	let $column = column.$el;
	let $setting = $column.find( '.ac-column-setting--pro' );

	$setting.each( function() {
		let $container = jQuery( this );

		$container.find( 'input' ).on( 'click', function( e ) {
			e.preventDefault();

			$container.find( '[data-ac-open-modal]' ).trigger( 'click' );
		} )

	} );
};

export default pro;