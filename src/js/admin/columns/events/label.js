let label = function( column ) {
	let $column = column.$el;

	/**
	 * Populates the main Label with the selected label from the dropdown,
	 */
	$column.find( 'select[data-label="update"]' ).change( function() {
		let $label = $column.find( 'input.ac-setting-input_label' );
		let field_label = jQuery( this ).find( 'option:selected' ).text();

		// Set new label
		$label.val( field_label );
		$label.trigger( 'change' );
	} );

	/** When an label contains an icon or span, the displayed label can appear empty. In this case we show the "type" label. */
	setTimeout( function() {
		let column_label = $column.find( '.column_label .toggle' );

		if ( jQuery.trim( column_label.html() ) && column_label.width() < 1 ) {
			column_label.html( $column.find( '.column_type .inner' ).html() );
		}
	}, 50 );
};

let settingLabel = function( column ) {
	let $column = column.$el;

	/** change label */
	$column.find( '.ac-column-setting--label input' ).bind( 'keyup change', function() {
		let value = jQuery( this ).val();
		jQuery( this ).closest( '.ac-column' ).find( 'td.column_label .inner > a.toggle' ).html( value );
	} ).trigger( 'change' );

	/** tooltip */
	$column.find( '.ac-column-body .col-label .label' ).hover( function() {
		jQuery( this ).parents( '.col-label' ).find( 'div.tooltip' ).show();
	}, function() {
		jQuery( this ).parents( '.col-label' ).find( 'div.tooltip' ).hide();
	} );
};

module.exports = {
	label : label,
	setting : settingLabel
};