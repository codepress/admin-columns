<?php

/**
 * CPAC_Column_User_Description
 *
 * @since 2.0.0
 */
class CPAC_Column_User_Description extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		// define properties		
		$this->properties['type']	 = 'column-user-description';
		$this->properties['label']	 = __( 'Description', 'cpac' );
			
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $user_id ) {

		return $this->get_shortened_string( get_the_author_meta( 'user_description', $user_id ), 20 );
	}
}