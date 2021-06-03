<?php

namespace AC\Admin;

use AC;

interface PageFactoryInterface {

	/**
	 * @param string $slug
	 *
	 * @return Page|null
	 */
	public function create( $slug );

}