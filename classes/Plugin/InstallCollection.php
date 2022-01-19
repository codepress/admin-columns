<?php

namespace AC\Plugin;

use AC;
use AC\ArrayIterator;

final class InstallCollection extends ArrayIterator {

	/**
	 * @return Install[]
	 */
	public function get_copy() {
		return parent::get_copy();
	}

}