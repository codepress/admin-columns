<?php

class AC_Helper_User {

	/**
	 * @param string $field
	 * @param int $user_id
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

		return $user && is_a( $user, 'WP_User' ) ? $user : false;
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
	 * @param $user
	 * @param bool $format
	 *
	 * @return false|string
	 */
	public function get_display_name( $user, $format = false ) {
		$name = false;

		if ( $user = $this->get_user( $user ) ) {
			$name = $user->display_name;

			if ( ! empty( $user->{$format} ) ) {
				$name = $user->{$format};
			}

			if ( 'first_last_name' == $format ) {
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
			}
		}

		return $name;
	}

	/**
	 * @since 3.4.4
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
	 * @return array
	 */
	public function get_ids() {
		global $wpdb;

		return $wpdb->get_col( "SELECT {$wpdb->users}.ID FROM {$wpdb->users}" );
	}

	/**
	 * Store current user meta data that is compatible with multi sites
	 *
	 * @param string $key
	 * @param array|string $value
	 */
	public function update_meta_site( $key, $value, $prev_value = '' ) {
		return update_user_meta( get_current_user_id(), $key . get_current_blog_id(), $value, $prev_value );
	}

	/**
	 * Get current user meta data
	 * @param string $key
	 */
	public function get_meta_site( $key, $single = false ) {
		return get_user_meta( get_current_user_id(), $key . get_current_blog_id(), $single );
	}

	/**
	 * Get current user meta data
	 * @param string $key
	 */
	public function delete_meta_site( $key, $value = '' ) {
		return delete_user_meta( get_current_user_id(), $key . get_current_blog_id(), $value );
	}

}