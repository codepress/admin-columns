<?php

namespace AC\Settings;

use AC\View;

interface Header {

	/**
	 * @return View|false
	 */
	public function create_header_view();

}