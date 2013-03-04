<?php
/**
 * CPAC_Column_Media_ID
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_ID extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-mediaid';
		$this->properties['label']	 	= __( 'ID', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $media_id ) {

		return $media_id;
	}
}