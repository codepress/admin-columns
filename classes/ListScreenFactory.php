<?php

namespace AC;

use AC\Storage;
use LogicException;

class ListScreenFactory {

	/**
	 * @param string $type
	 * @param int    $id Optional (layout) ID
	 *
	 * @return ListScreen|false
	 */
	// todo: remove static and add inject Storage\ListScreen to __constructor
	public static function create( $type, $id = null ) {
		$list_screen = self::get_list_screen_by_type( $type );

		if ( ! $list_screen ) {
			return false;
		}
		//$list_screen->set_layout_id( $id );

		$data_objects = ( new Storage\ListScreen )->query( [
			'type' => $list_screen->get_key(),
		]);

		// todo
		if ( $data_objects ) {
			$data = $data_objects[0];
			$list_screen->set_settings( $data->columns );
		}

		return $list_screen;
	}

	private static function get_list_screen_by_type( $type ) {
		$list_screens = AC()->get_list_screens();

		if ( ! isset( $list_screens[ $type ] ) ) {
			return false;
		}

		return clone $list_screens[ $type ];
	}

	/**
	 * @param $id
	 *
	 * @return ListScreen|bool
	 */
	public static function create_by_id( $id ) {
		$data = ( new Storage\ListScreen )->read( $id );

		if ( $data->is_empty() ) {
			throw new LogicException( sprintf( 'ListScreen ID:%s not found.', $id ) );
		}

		$list_screen = self::get_list_screen_by_type( $data->type );

		if ( ! $list_screen ) {
			throw new LogicException( sprintf( 'ListScreen Type:%s not found.', $data->type ) );
		}

		$list_screen->set_settings( $data->columns );

		return $list_screen;
	}

	/**
	 * @param Request $request
	 *
	 * @return ListScreen|false
	 */
	public static function create_from_request( Request $request ) {
		$type = $request->filter( 'list_screen', '', FILTER_SANITIZE_STRING );
		$id = $request->filter( 'layout', null, FILTER_SANITIZE_STRING );

		return self::create( $type, $id );
	}

}