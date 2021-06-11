<?php

namespace AC\Admin;

use AC;

interface MenuFactoryInterface {

	/**
	 * @param string $current
	 *
	 * @return Menu
	 */
	public function create( $current );

}