<?php

namespace AC\Helper;

use WP_User;

class User {

	/**
	 * @param string $field
	 * @param int    $user_id
	 *
	 * @return bool|string|array
	 */
	public function get_user_field( $field, $user_id ) {
		$user = get_user_by( 'id', $user_id );

		return $user->{$field} ?? false;
	}

	/**
	 * @param mixed $user
	 *
	 * @return false|WP_User
	 */
	public function get_user( $user ) {
		if ( is_numeric( $user ) ) {
			return get_userdata( $user );
		}

		return $user instanceof WP_User
			? $user
			: false;
	}

	/**
	 * @param array $role_names
	 *
	 * @return array
	 */
	public function translate_roles( $role_names ) {
		$roles = [];

		$wp_roles = wp_roles()->roles;

		foreach ( (array) $role_names as $role ) {
			if ( isset( $wp_roles[ $role ] ) ) {
				$roles[ $role ] = translate_user_role( $wp_roles[ $role ]['name'] );
			}
		}

		return $roles;
	}

	/**
	 * @param int|WP_User  $user
	 * @param false|string $format WP_user var, 'first_last_name' or 'roles'
	 *
	 * @return false|string
	 */
	public function get_display_name( $user, $format = false ) {
		$user = $this->get_user( $user );

		if ( ! $user ) {
			return false;
		}

		if ( false === $format ) {
			return $user->display_name;
		}

		switch ( $format ) {

			case 'first_last_name' :
			case 'full_name' :
				$name_parts = [];

				if ( $user->first_name ) {
					$name_parts[] = $user->first_name;
				}
				if ( $user->last_name ) {
					$name_parts[] = $user->last_name;
				}

				return $name_parts
					? implode( ' ', $name_parts )
					: false;
			case 'roles' :
				return ac_helper()->string->enumeration_list( $this->get_roles_names( $user->roles ), 'and' );
			default :
				return $user->{$format} ?? $user->display_name;
		}
	}

	/**
	 * @param array $roles Role keys
	 *
	 * @return array Role nice names
	 */
	public function get_roles_names( $roles ) {
		$role_names = [];

		foreach ( $roles as $role ) {
			$name = $this->get_role_name( $role );

			if ( $name ) {
				$role_names[ $role ] = $name;
			}
		}

		return $role_names;
	}

	/**
	 * @param string $role
	 *
	 * @return string
	 */
	public function get_role_name( $role ) {
		$roles = $this->get_roles();

		if ( ! array_key_exists( $role, $roles ) ) {
			return false;
		}

		return $roles[ $role ];
	}

	/**
	 * @param int    $user_id
	 * @param string $post_type
	 *
	 * @return string
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
		$roles = [];
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
		$role_names = [];

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

	/**
	 * Fetches remote translations. Expires in 7 days.
	 * @return array[]
	 */
	public function get_translations_remote() {
		$translations = get_site_transient( 'ac_available_translations' );

		if ( false !== $translations ) {
			return $translations;
		}

		require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

		$translations = wp_get_available_translations();

		set_site_transient( 'ac_available_translations', wp_get_available_translations(), WEEK_IN_SECONDS );

		return $translations;
	}

}