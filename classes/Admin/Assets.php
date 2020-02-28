<?php

namespace AC\Admin;

use AC\Asset\Enqueueable;

interface Assets {

	/**
	 * @return Enqueueable[]
	 */
	public function get_assets();

}