<?php

namespace AC\Admin;

use AC\Renderable;
use AC\Request;

interface RequestHandlerInterface {

	const PARAM_PAGE = 'page';
	const PARAM_TAB = 'tab';

	/**
	 * @param Request $request
	 *
	 * @return Renderable
	 */
	public function handle( Request $request );

}