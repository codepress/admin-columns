<?php

namespace AC\Deprecated\Hook;

use AC\Deprecated\Hook;

class Filter extends Hook {

	public function has_hook() {
		return has_filter( $this->get_name() );
	}

}