let date = function( column ) {
	let $ = jQuery;
	let $column = column.$el;
	let $setting = $column.find( '.ac-column-setting--date' );

	$setting.each( function() {

		let $container = $( this );

		// Custom input
		let $radio_custom = $container.find( 'input.custom' );
		let $input_custom = $container.find( '.ac-setting-input-date__custom' );
		let $input_value = $container.find( '.ac-setting-input-date__value' );
		let $example_custom = $container.find( '.ac-setting-input-date__example' );
		let $selected = $container.find( 'input[type=radio]:checked' );
		let $help_msg = $container.find( '.help-msg' );

		// Click Event
		$container.find( 'input[type=radio]' ).on( 'change', function() {

			let $input = $( this );
			let $input_container = $input.closest( 'label' );
			let date_format = $input_container.find( 'code' ).text();
			let description = $input_container.find( '.ac-setting-input-date__more' ).html();

			if ( date_format ) {
				$input_custom.val( date_format ).trigger( 'change' );
			}

			if ( $input.hasClass( 'diff' ) ) {
				$input_custom.val( '' );
				$example_custom.text( '' );
			}

			$input_custom.prop( 'disabled', true );

			// Custom input selected
			if ( $input.hasClass( 'custom' ) ) {
				$input.val( $input_custom.val() );
				$input_custom.prop( 'disabled', false );
				$help_msg.show();
			}

			// Show more description
			$help_msg.hide();
			if ( description ) {
				$help_msg.html( description ).show();
			}

			$input_value.val( $input.val() );
		} );

		// Custom input
		$input_custom.on( 'change', function() {

			$example_custom.html( '<span class="spinner is-active"></span>' );
			$radio_custom.val( $input_custom.val() );

			let $custom_value = $( this ).val();

			if ( !$custom_value ) {
				$example_custom.text( '' );
				return;
			}

			$.ajax( {
				url : ajaxurl,
				method : 'post',
				data : {
					action : 'date_format',
					date : $custom_value
				}
			} ).done( function( date ) {
				$example_custom.text( date );
			} );

			$input_value.val( $custom_value );
		} );

		// Update date example box
		$selected.trigger( 'change' );

		// Select custom input as a default
		if ( 0 === $selected.length ) {
			$radio_custom.trigger( 'click' );
		}

	} );
};

export default date;