<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Roles extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-roles' );
		$this->set_label( __( 'Roles', 'codepress-admin-columns' ) );
	}

	/**
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function get_value( $post_id ) {
		return ac_helper()->user->get_display_name( $this->get_raw_value( $post_id ), 'roles' );
	}

	public function get_raw_value( $post_id ) {
		$userdata = get_userdata( get_post_field( 'post_author', $post_id ) );

		if ( empty( $userdata->roles[0] ) ) {
			return array();
		}

		return $userdata->roles;
	}

}