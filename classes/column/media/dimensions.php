<?php
/**
 * CPAC_Column_Media_Dimensions
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Dimensions extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-dimensions';
		$this->properties['label']	 	= __( 'Dimensions', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$value = '';

		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		if ( ! empty( $meta['width'] ) && ! empty( $meta['height'] ) )
			$value = "{$meta['width']} x {$meta['height']}";

		return $value;
	}
}