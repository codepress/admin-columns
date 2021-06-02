<?php

namespace AC;

use AC\Request;

interface PageRequestHandler {

	/**
	 * @param Request $request
	 *
	 * @return Page
	 */
	public function handle( Request $request );

}