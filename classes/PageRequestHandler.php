<?php

namespace AC;

use AC\Admin\Page;

interface PageRequestHandler {

	const PARAM_PAGE = 'page';
	const PARAM_TAB = 'tab';

	/**
	 * @param Request $request
	 *
	 * @return Page
	 */
	public function handle( Request $request );

}