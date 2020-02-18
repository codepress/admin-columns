<?php

namespace AC;

interface ListScreenRepository {

	const ID = 'id';
	const KEY = 'key';
	const LIMIT = 'limit';

	/**
	 * @param string $id
	 *
	 * @return ListScreen|null
	 */
	public function find( $id );

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function exists( $id );

	/**
	 * @param array $args
	 *
	 * @return ListScreenCollection
	 */
	public function find_all( array $args = [] );

	/**
	 * @param ListScreen $list_screen
	 */
	public function save( ListScreen $list_screen );

	/**
	 * @param ListScreen $list_screen
	 */
	public function delete( ListScreen $list_screen );

}