<?php

namespace AC\Admin;

use AC\Renderable;
use AC\Request;

interface RequestHandlerInterface {

	public const PARAM_PAGE = 'page';
	public const PARAM_TAB = 'tab';

	/**
	 * @param Request $request
	 *
	 * @return Renderable|null
	 */
	public function handle( Request $request );

}