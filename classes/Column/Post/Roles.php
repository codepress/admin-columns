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
		$names = ac_helper()->user->get_role_names( $this->get_raw_value( $post_id ) );

		if ( ! $names ) {
			return $this->get_empty_char();
		}

		return implode( __( ', ' ), $names );
	}

	/**
	 * @param int $post_id
	 *
	 * @return array
	 */
	public function get_raw_value( $post_id ) {
		$userdata = get_userdata( get_post_field( 'post_author', $post_id ) );

		if ( empty( $userdata->roles[0] ) ) {
			return array();
		}

		return $userdata->roles;
	}

}