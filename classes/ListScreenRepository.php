<?php

namespace AC;

use AC\Type\ListScreenId;

interface ListScreenRepository {

	const KEY = 'key';

	/**
	 * @param ListScreenId $id
	 *
	 * @return ListScreen|null
	 */
	public function find( ListScreenId $id );

	/**
	 * @param ListScreenId $id
	 *
	 * @return bool
	 */
	public function exists( ListScreenId $id );

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] );

}