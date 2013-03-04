<?php
/**
 * CPAC_Column_Media_Height
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Height extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-height';
		$this->properties['label']	 	= __( 'Height', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$value = '';

		$meta = get_post_meta( $id, '_wp_attachment_metadata', true );

		if( ! empty( $meta['height'] ) )
			$value = $meta['height'];

		return $value;
	}
}