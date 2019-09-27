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
	 * @param int $id
	 *
	 * @return bool
	 */
	public function delete( $id );

}