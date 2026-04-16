/// <reference types="jquery" />

declare const ajaxurl: string;

interface AcfFieldSettingsI18n {
	nonce: string;
	adding: string;
	added: string;
	edit_column: string;
	add_as_column: string;
}

interface AddColumnResponse {
	success: boolean;
	data?: {
		editor_url?: string;
	};
}

( function( $: JQueryStatic ) {

	const i18n: Partial<AcfFieldSettingsI18n> = ( window as any ).ac_acf_field_settings || {};

	$( document ).on( 'click', '.ac-acf-add-column', function() {
		const $button = $( this );
		const $card = $button.closest( '.ac-acf-card' );
		const tableId = $card.data( 'table-id' );

		const $fieldObject = $button.closest( '.acf-field-object' );
		const metaKey = $fieldObject.find( '.acf-field[data-name="name"] input' ).val() || $fieldObject.data( 'field-name' ) || '';

		if ( ! metaKey ) {
			return;
		}

		const fieldData = {
			name: metaKey,
			type: $fieldObject.data( 'type' ) || '',
			label: $fieldObject.find( '.acf-field[data-name="label"] input' ).val() || '',
			prepend: $fieldObject.find( '.acf-field[data-name="prepend"] input' ).val() || '',
			append: $fieldObject.find( '.acf-field[data-name="append"] input' ).val() || ''
		};

		$button.prop( 'disabled', true ).text( i18n.adding || 'Adding...' );

		$.post( ajaxurl, {
			action: 'ac-acf-add-column',
			_ajax_nonce: i18n.nonce || '',
			table_id: tableId,
			meta_key: metaKey,
			field_data: JSON.stringify( fieldData )
		} ).done( function( response: AddColumnResponse ) {
			if ( ! response.success ) {
				$button.prop( 'disabled', false ).text( i18n.add_as_column || 'Add as column' );
				return;
			}

			const editorUrl = response.data && response.data.editor_url ? response.data.editor_url : '#';
			const label = $card.find( '.ac-acf-card-name' ).text();

			$card.addClass( 'ac-acf-card--added' ).attr( 'data-state', 'added' ).empty()
				.append(
					$( '<div>', { 'class': 'ac-acf-card-main' } )
						.append( $( '<span>', { 'class': 'ac-acf-card-name', text: label } ) )
						.append(
							$( '<span>', { 'class': 'ac-acf-status' } )
								.append( $( '<span>', { 'class': 'ac-acf-badge', html: '&#10003;' } ) )
								.append( $( '<span>', { 'class': 'ac-acf-card-status', text: i18n.added || 'Added' } ) )
						)
				)
				.append(
					$( '<div>', { 'class': 'ac-acf-card-actions' } )
						.append( $( '<a>', {
							href: editorUrl,
							target: '_blank',
							'class': 'ac-acf-link',
							text: i18n.edit_column || 'Edit column \u2192'
						} ) )
				);
		} ).fail( function() {
			$button.prop( 'disabled', false ).text( i18n.add_as_column || 'Add as column' );
		} );
	} );

} )( jQuery );
