<?php
/**
 * CPAC_Column_Link_Target
 *
 * @since 2.0.0
 */
class CPAC_Column_Link_Target extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-target';
		$this->properties['label']	 	= __( 'Target', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$bookmark = get_bookmark( $id );

		return $bookmark->link_target;
	}
}