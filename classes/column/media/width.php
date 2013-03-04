<?php
/**
 * CPAC_Column_Media_Width
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Width extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-width';
		$this->properties['label']	 	= __( 'Width', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$value = '';

		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		if( ! empty( $meta['width'] ) )
			$value = $meta['width'];

		return $value;
	}
}