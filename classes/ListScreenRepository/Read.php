<?php
namespace AC\ListScreenRepository;

use AC\ListScreen;

interface Read {

	/**
	 * @param array $args
	 *
	 * @return ListScreen[]
	 */
	public function query( array $args );

	/**
	 * @param int $id
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