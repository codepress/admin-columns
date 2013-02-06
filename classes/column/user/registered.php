<?php

/**
 * CPAC_Column_User_Registered
 *
 * @since 2.0.0
 */
class CPAC_Column_User_Registered extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 = 'column-user-registered';
		$this->properties['label']	 = __( 'Registered', CPAC_TEXTDOMAIN );
			
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $user_id ) {
		
		$userdata = get_userdata( $user_id );
		
		return $userdata->user_registered;
	}
}