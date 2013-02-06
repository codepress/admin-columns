<?php

/**
 * CPAC_Column_User_Last_Name
 *
 * @since 2.0.0
 */
class CPAC_Column_User_Last_Name extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 = 'column-last-name';
		$this->properties['label']	 = __( 'Last name', CPAC_TEXTDOMAIN );
			
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $user_id ) {
		
		$userdata = get_userdata( $user_id );
		
		return $userdata->last_name;
	}
}