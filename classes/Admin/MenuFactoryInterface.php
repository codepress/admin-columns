<?php

namespace AC\Admin;

use AC;

interface MenuFactoryInterface {

	const QUERY_ARG_PAGE = 'page';
	const QUERY_ARG_TAB = 'tab';

	/**
	 * @return AC\Admin\Menu
	 */
	public function create();

}