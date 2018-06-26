<?php

namespace AC\Helper;

class User {

	/**
	 * @param string $field
	 * @param int    $user_id
	 *
	 * @return bool|string|array
	 */
	public function get_user_field( $field, $user_id ) {
		$user = get_user_by( 'id', $user_id );

		return isset( $user->{$field} ) ? $user->{$field} : false;
	}

	public function get_user( $user ) {
		if ( is_numeric( $user ) ) {
			$user = get_userdata( $user );
		}

		if ( ! $user ) {
			return false;
		}

		if ( ! is_a( $user, 'WP_User' ) ) {
			return false;
		}

		return $user;
	}

	/**
	 * @param array $role_names
	 *
	 * @return array
	 */
	public function translate_roles( $role_names ) {
		$roles = array();

		$wp_roles = wp_roles()->roles;

		foreach ( (array) $role_names as $role ) {
			if ( isset( $wp_roles[ $role ] ) ) {
				$roles[ $role ] = translate_user_role( $wp_roles[ $role ]['name'] );
			}
		}

		return $roles;
	}

	/**
	 * @param int|\WP_User $user
	 * @param false|string $format WP_user var, 'first_last_name' or 'roles'
	 *
	 * @return false|string
	 */
	public function get_display_name( $user, $format = false ) {
		$user = $this->get_user( $user );

		if ( ! $user ) {
			return false;
		}

		$name = $user->display_name;

		switch ( $format ) {

			case 'first_last_name' :

				$name_parts = array();

				if ( $user->first_name ) {
					$name_parts[] = $user->first_name;
				}
				if ( $user->last_name ) {
					$name_parts[] = $user->last_name;
				}

				if ( $name_parts ) {
					$name = implode( ' ', $name_parts );
				}

				break;
			case 'roles' :
				$name = ac_helper()->string->enumeration_list( $this->get_roles_names( $user->roles ), 'and' );

				break;
			default :
				if ( ! empty( $user->{$format} ) ) {
					$name = $user->{$format};
				}
		}

		return $name;
	}

	/**
	 * @param array $roles Role keys
	 *
	 * @return array Role nice names
	 */
	public function get_roles_names( $roles ) {
		$translated = $this->get_roles();

		$role_names = array();
		foreach ( $roles as $role ) {
			if ( isset( $translated[ $role ] ) ) {
				$role_names[ $role ] = $translated[ $role ];
			}
		}

		return $role_names;
	}

	/**
	 * @since 3.4.4
	 *
	 * @param int    $user_id
	 * @param string $post_type
	 */
	public function get_postcount( $user_id, $post_type ) {
		global $wpdb;
		$sql = "
			SELECT COUNT(ID)
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			AND post_author = %d
			AND post_type = %s
		";

		return $wpdb->get_var( $wpdb->prepare( $sql, $user_id, $post_type ) );
	}

	/**
	 * @return array Translatable roles
	 */
	public function get_roles() {
		$roles = array();
		foreach ( wp_roles()->roles as $k => $role ) {
			$roles[ $k ] = translate_user_role( $role['name'] );
		}

		return $roles;
	}

	/**
	 * @param array $roles
	 *
	 * @return array Role Names
	 */
	public function get_role_names( $roles ) {
		$role_names = array();

		$labels = $this->get_roles();

		foreach ( $roles as $role ) {
			if ( isset( $labels[ $role ] ) ) {
				$role_names[ $role ] = $labels[ $role ];
			}
		}

		return $role_names;
	}

	/**
	 * @return array
	 */
	public function get_ids() {
		global $wpdb;

		return $wpdb->get_col( "SELECT {$wpdb->users}.ID FROM {$wpdb->users}" );
	}

}