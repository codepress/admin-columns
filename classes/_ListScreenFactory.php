<?php

namespace AC;

class _ListScreenFactory {

	/**
	 * @param string $type
	 * @param int    $id Optional (layout) ID
	 *
	 * @return ListScreen|false
	 */
	// todo: remove static and add inject Storage\ListScreen to __constructor
	public static function create( $type, $id = null ) {
		$list_screens = AC()->get_list_screens();

		if ( ! isset( $list_screens[ $type ] ) ) {
			return false;
		}

		$list_screen = clone $list_screens[ $type ];
		$list_screen->set_layout_id( $id );

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