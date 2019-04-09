<?php

namespace AC\Capabilities;

use AC\Capabilities;
use WP_Roles;

class Manage extends Capabilities {

	const MANAGE = 'manage_admin_columns';

	public function is_administrator() {
		return is_super_admin( $this->user->ID ) || $this->has_cap( 'administrator' );
	}

	public function has_manage() {
		return $this->has_cap( self::MANAGE );
	}

	public function set_default_caps( WP_Roles $roles ) {
		$roles->add_cap( 'administrator', self::MANAGE );
	}

}