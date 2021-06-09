<?php

namespace AC\Admin;

use AC\Request;

interface RequestHandlerInterface {

	const PARAM_PAGE = 'page';
	const PARAM_TAB = 'tab';

	/**
	 * @param Request $request
	 *
	 * @return Page|null
	 */
	public function handle( Request $request );

}