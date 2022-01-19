<?php

namespace AC\Admin;

use AC;
use AC\Renderable;

interface PageFactoryInterface {

	/**
	 * @return Renderable
	 */
	public function create();

}