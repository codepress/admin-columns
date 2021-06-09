<?php

namespace AC\Admin;

use AC;
use AC\Renderable;

interface MainFactoryInterface {

	/**
	 * @param string $slug
	 *
	 * @return Renderable|null
	 */
	public function create( $slug );

}