<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;

interface Rule {

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return bool
	 */
	public function match( ListScreen $list_screen );

}