<?php

namespace AC\Capabilities;

use AC\Capabilities;
use WP_Roles;

class Manage extends Capabilities {

	public function has_manage() {
		return $this->has_cap( self::MANAGE );
	}

	public function set_default_caps( WP_Roles $roles ) {
		$roles->add_cap( 'administrator', self::MANAGE );
	}

}