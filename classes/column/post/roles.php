<?php

/**
 * CPAC_Column_Post_Roles
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Roles extends CPAC_Column {

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 	= 'column-roles';
		$this->properties['label']	 	= __( 'Roles', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		return implode( ', ', $this->get_raw_value( $post_id ) );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		$userdata = get_userdata( get_post_field( 'post_author', $post_id ) );

		if ( empty( $userdata->roles[0] ) )
			return array();

		return $userdata->roles;
	}
}