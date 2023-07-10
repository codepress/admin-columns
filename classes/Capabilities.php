<?php

namespace AC;

use WP_Roles;

abstract class Capabilities implements Registerable {

	// backwards compat
	public const MANAGE = 'manage_admin_columns';

	public function register(): void
    {
		add_action( 'ac/capabilities/init', [ $this, 'set_default_caps' ] );
	}

	abstract public function set_default_caps( WP_Roles $roles ): void;

}