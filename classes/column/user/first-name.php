<?php

/**
 * CPAC_Column_User_First_Name
 *
 * @since 2.0.0
 */
class CPAC_Column_User_First_Name extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 = 'column-first_name';
		$this->properties['label']	 = __( 'First name', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $user_id ) {

		return $this->get_raw_value( $user_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $user_id ) {

		$userdata = get_userdata( $user_id );

		return $userdata->first_name;
	}
}