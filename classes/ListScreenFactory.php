<?php

namespace AC;

use AC\Storage;
use LogicException;

class ListScreenFactory {

	/** @var Storage\ListScreen */
	private $storage;

	public function __construct() {
		// todo add interface + inject
		$this->storage = new Storage\ListScreen;
	}

	/**
	 * @param string $type
	 *
	 * @return ListScreen|false
	 */
	// todo: remove static and add inject Storage\ListScreen to __constructor
	public function create( $key ) {
		$list_screen = $this->get_list_screen_by_key( $key );

		if ( ! $list_screen ) {
			return false;
		}

		$data_objects = $this->storage->query( [
			'type' => $list_screen->get_key(),
		] );

		// todo
		if ( $data_objects ) {
			$data = $data_objects[0];
			$list_screen->set_settings( $data->columns );
		}

		return $list_screen;
	}

	/**
	 * @param string $key
	 *
	 * @return ListScreen|false
	 */
	private function get_list_screen_by_key( $key ) {
		foreach ( ac_get_list_screen_types() as $list_screen ) {
			if ( $key === $list_screen->get_key() ) {
				return $list_screen;
			}
		}

		return false;
	}

	/**
	 * @param $id
	 *
	 * @return ListScreen|bool
	 */
	public function create_by_id( $id ) {
		$data = $this->storage->read( $id );

		if ( $data->is_empty() ) {
			throw new LogicException( sprintf( 'ListScreen ID:%s not found.', $id ) );
		}

		$list_screen = $this->get_list_screen_by_key( $data->type );

		if ( ! $list_screen ) {
			throw new LogicException( sprintf( 'ListScreen Type:%s not found.', $data->type ) );
		}

		$list_screen->set_settings( $data->columns );

		return $list_screen;
	}

	public function create_by_screen( \WP_Screen $screen ) {
		foreach ( ac_get_list_screen_types() as $list_screen ) {
			if ( $list_screen->is_current_screen( $screen ) ) {
				return $this->create( $list_screen->get_key() );
			}
		}

		return null;
	}

	/**
	 * @param Request $request
	 *
	 * @return ListScreen|false
	 */
	public function create_from_request( Request $request ) {
		$type = $request->filter( 'list_screen', '', FILTER_SANITIZE_STRING );
		$id = $request->filter( 'layout', null, FILTER_SANITIZE_STRING );

		// todo
		return $this->create( $type, $id );
	}

}