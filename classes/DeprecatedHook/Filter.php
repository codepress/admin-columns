<?php

namespace AC\DeprecatedHook;

use AC\DeprecatedHook;

class Filter extends DeprecatedHook {

	public function has_hook() {
		return has_filter( $this->get_name() );
	}

}