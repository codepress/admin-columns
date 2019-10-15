<?php
namespace AC\ListScreenRepository;

use AC\ListScreen;

interface Write {

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return bool
	 */
	public function save( ListScreen $list_screen );

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return bool
	 */
	public function delete( ListScreen $list_screen );

}