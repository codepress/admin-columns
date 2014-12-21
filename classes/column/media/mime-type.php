<?php
/**
 * CPAC_Column_Media_Mime_Type
 *
 * @since 2.0
 */
class CPAC_Column_Media_Mime_Type extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-mime_type';
		$this->properties['label'] = __( 'Mime type', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {

		return $this->get_raw_value( $id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.2
	 */
	public function get_raw_value( $id ) {

		return get_post_field( 'post_mime_type', $id );
	}
}