<?php
/**
 * CPAC_Column_Media_Mime_Type
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Mime_Type extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-mime_type';
		$this->properties['label']	 	= __( 'Mime type', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		return get_post_field( 'post_mime_type', $id );
	}
}