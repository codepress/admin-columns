<?php

namespace AC\Admin;

use AC;

interface PageFactoryInterface {

	/**
	 * @param string $slug
	 *
	 * @return Page
	 */
	public function create( $slug );

}