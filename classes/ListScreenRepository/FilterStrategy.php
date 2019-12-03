<?php
namespace AC\ListScreenRepository;

use AC\ListScreen;

interface FilterStrategy {

	/**
	 * @param ListScreen $list_screens
	 *
	 * @return ListScreen|null
	 */
	public function filter( ListScreen $list_screen );

}