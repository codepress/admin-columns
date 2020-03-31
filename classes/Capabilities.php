<?php

namespace AC;

use WP_Roles;
use WP_User;

abstract class Capabilities implements Registrable {

	// backwards compat
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
		return is_super_admin( $this->user->ID ) || $this->has_cap( 'administrator' );
	}

	public function register() {
		add_action( 'ac/capabilities/init', [ $this, 'set_default_caps' ] );
	}

	/**
	 * @param string $cap
	 *
	 * @return bool
	 */
	public function has_cap( $cap ) {
		return $this->user->has_cap( $cap );
	}

	/**
	 * @param WP_Roles $roles
	 *
	 * @return void
	 */
	abstract public function set_default_caps( WP_Roles $roles );

}