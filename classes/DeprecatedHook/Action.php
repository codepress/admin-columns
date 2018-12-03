<?php

namespace AC\DeprecatedHook;

use AC\DeprecatedHook;

class Action extends DeprecatedHook {

	public function has_hook() {
		return has_action( $this->get_name() );
	}

}