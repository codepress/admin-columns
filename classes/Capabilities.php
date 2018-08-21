<?php

namespace AC;

use WP_User;

class Capabilities {

	const MANAGE = 'manage_admin_columns';

	/**
	 * @var WP_User
	 */
	protected $user;

	public function __construct( WP_User $user = null ) {
		if ( null === $user ) {
			$user = wp_get_current_user();
		}

		$this->user = $user;
	}

	/**
	 * @return bool
	 */
	public function is_administrator() {
		return is_super_admin( $this->user->ID ) || $this->user->has_cap( 'administrator' );
	}

	/**
	 * Check if user can manage Admin Columns
	 * @return bool
	 */
	public function has_manage() {
		return $this->user->has_cap( self::MANAGE );
	}

	/**
	 * Add the capability to manage admin columns.
	 */
	public function add_manage() {
		/** @var \WP_Roles $wp_roles */
		global $wp_roles;

		$wp_roles->add_cap( 'administrator', self::MANAGE );
	}
}