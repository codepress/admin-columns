<?php

namespace AC\Deprecated\Hook;

use AC\Deprecated\Hook;

class Action extends Hook {

	public function has_hook() {
		return has_action( $this->get_name() );
	}

}