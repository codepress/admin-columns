<?php
/**
 * CPAC_Column_Media_Full_Path
 *
 * @since 2.0
 */
class CPAC_Column_Media_Full_Path extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-full_path';
		$this->properties['label']	 = __( 'Full path', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
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