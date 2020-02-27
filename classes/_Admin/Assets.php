<?php
namespace AC\_Admin;

use AC\Asset\Enqueueable;

interface Assets {

	/**
	 * @return Enqueueable[]
	 */
	public function get_assets();

}