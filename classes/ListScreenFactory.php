<?php

namespace AC;

class ListScreenFactory {

	/**
	 * @param string $type
	 * @param int    $id Optional (layout) ID
	 *
	 * @return ListScreen|false
	 */
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
	 * @param \WP_Screen $wp_screen
	 * @param int        $id Optional (layout) ID
	 *
	 * @return false|ListScreen
	 */
	public static function create_by_screen( $wp_screen, $id = null ) {
		if ( ! $wp_screen ) {
			return false;
		}

		foreach ( AC()->get_list_screens() as $list_screen ) {
			if ( $wp_screen->id === $list_screen->get_screen_id() &&  $wp_screen->base === $list_screen->get_screen_base() ) {
				return self::create( $list_screen->get_key(), $id );
			}
		}

		return false;
	}

}