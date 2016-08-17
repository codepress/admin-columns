<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_User {

	/**
	 * @param string $field
	 * @param int $user_id
	 *
	 * @return bool|string
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
	 * @param $user
	 * @param bool $format
	 *
	 * @return false|string
	 */
	public function get_display_name( $user, $format = false ) {
		$user = $this->get_user( $user );

		if ( ! $user ) {
			return false;
		}

		$name = $user->display_name;

		if ( 'first_last_name' == $format ) {
			$first = ! empty( $user->first_name ) ? $user->first_name : '';
			$last = ! empty( $user->last_name ) ? " {$user->last_name}" : '';
			if ( $first || $last ) {
				$name = $first . $last;
			}
		}
		elseif ( ! empty( $user->{$format} ) ) {
			$name = $user->{$format};
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

}