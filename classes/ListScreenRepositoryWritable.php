<?php

namespace AC;

interface ListScreenRepositoryWritable extends ListScreenRepository {

	/**
	 * @param ListScreen $list_screen
	 */
	public function save( ListScreen $list_screen );

	/**
	 * @param ListScreen $list_screen
	 */
	public function delete( ListScreen $list_screen );

}