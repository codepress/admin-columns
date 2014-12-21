<?php
/**
 * CPAC_Column_Media_ID
 *
 * @since 2.0
 */
class CPAC_Column_Media_ID extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-mediaid';
		$this->properties['label'] = __( 'ID', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $media_id ) {

		return $media_id;
	}
}