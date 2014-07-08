<?php
/**
 * CPAC_Column_Link_Length
 *
 * @since 2.0
 */
class CPAC_Column_Link_Length extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-length';
		$this->properties['label']	 	= __( 'Length', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return strlen( $bookmark->link_name );
	}
}