<?php
/**
 * CPAC_Column_Media_Full_Path
 *
 * @since 2.0.0
 */
class CPAC_Column_Media_Full_Path extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-full_path';
		$this->properties['label']	 = __( 'Full path', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$value = '';

		$file 	= wp_get_attachment_url( $id );

		if ( $file ) {
			$value = $file;
		}

		return $value;
	}
}