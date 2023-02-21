<?php
declare( strict_types=1 );

namespace AC\Admin;

class ListMenuFactory {

	// TODO return Select\Options
	public function create( bool $network ): ListMenu {
		$items = [];

		foreach ( TableScreens::get_screens() as $table_screen ) {
			$items[ $table_screen->get_key() ] = $table_screen->get_label();
		}

		return new ListMenu( $items );
	}

}