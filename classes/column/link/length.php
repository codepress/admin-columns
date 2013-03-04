<?php
/**
 * CPAC_Column_Link_Length
 *
 * @since 2.0.0
 */
class CPAC_Column_Link_Length extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-length';
		$this->properties['label']	 	= __( 'Length', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return strlen( $bookmark->link_name );
	}
}