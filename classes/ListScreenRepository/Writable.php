<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenRepository;

interface Writable extends ListScreenRepository {

	/**
	 * @param ListScreen $list_screen
	 */
	public function save( ListScreen $list_screen );

	/**
	 * @param ListScreen $list_screen
	 */
	public function delete( ListScreen $list_screen );

}