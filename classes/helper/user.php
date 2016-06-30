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

}