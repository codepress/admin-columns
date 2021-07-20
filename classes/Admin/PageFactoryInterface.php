<?php

namespace AC\Admin;

use AC;
use AC\Renderable;

interface PageFactoryInterface {

	/**
	 * @param string $slug
	 *
	 * @return Renderable|null
	 */
	public function create( $slug );

}