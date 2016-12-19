<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Roles extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-roles' );
		$this->set_label( __( 'Roles', 'codepress-admin-columns' ) );
	}

	public function get_roles() {
		$roles = array();
		foreach ( wp_roles()->roles as $k => $role ) {
			$roles[ $k ] = translate_user_role( $role['name'] );
		}

		return $roles;
	}

	public function get_value( $post_id ) {
		$roles = $this->get_roles();

		$role_names = array();
		foreach ( $this->get_raw_value( $post_id ) as $role ) {
			if ( isset( $roles[ $role ] ) ) {
				$role_names[ $role ] = $roles[ $role ];
			}
		}

		return implode( ', ', $role_names );
	}

	public function get_raw_value( $post_id ) {
		$userdata = get_userdata( get_post_field( 'post_author', $post_id ) );

		return empty( $userdata->roles[0] ) ? array() : $userdata->roles;
	}

}