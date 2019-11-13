<?php

namespace AC;

/**
 * @deprecated NEWVERSION
 */
class ListScreenFactory {

	public static function create( $key, $id = null ) {
		$list_screen = ListScreenTypes::instance()->get_list_screen_by_key( $key );

		if ( $list_screen ) {
			return clone $list_screen;
		}

		return null;
	}

}