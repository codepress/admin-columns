<?php
namespace AC\ListScreenRepository;

use AC\ListScreen;
use AC\ListScreenCollection;

interface Read {

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function query( array $args );

	/**
	 * @param string $id
	 *
	 * @return ListScreen
	 */
	public function find_by_id( $id );

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function exists( $id );

}