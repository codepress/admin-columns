<?php
namespace AC;

interface Data {

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
	 * @param int        $id
	 * @param ListScreen $data
	 *
	 * @return bool
	 */
	public function save( ListScreen $data );

	/**
	 * @param int $id
	 *
	 * @return bool
	 */
	public function delete( $id );

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function exists( $id );

}