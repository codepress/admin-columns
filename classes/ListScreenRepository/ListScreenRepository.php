<?php

namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;

interface ListScreenRepository {

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] );

	/**
	 * @param string $id
	 *
	 * @return ListScreen
	 */
	public function find( $id );

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function exists( $id );

}