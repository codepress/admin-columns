<?php

namespace AC;

use WP_Roles;

abstract class Capabilities implements Registerable {

	// backwards compat
	const MANAGE = 'manage_admin_columns';

	public function register() {
		add_action( 'ac/capabilities/init', [ $this, 'set_default_caps' ] );
	}

	/**
	 * @param WP_Roles $roles
	 *
	 * @return void
	 */
	abstract public function set_default_caps( WP_Roles $roles );

}