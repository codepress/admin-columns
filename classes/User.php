<?php

class AC_User {

	const MANAGE_CAP = 'manage_admin_columns';

	/**
	 * @var \WP_User
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
		return $this->user->has_cap( 'administrator' );
	}

	/**
	 * Check if user can manage Admin Columns
	 *
	 * @return bool
	 */
	public function can_manage() {
		return $this->is_administrator() || $this->user->has_cap( self::MANAGE_CAP );
	}

	/**
	 * Add the capability to manage admin columns.
	 */
	public function add_manage_cap() {
		$this->user->add_cap( self::MANAGE_CAP );
	}
}