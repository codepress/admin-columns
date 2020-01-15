<?php

namespace AC;

class UnitializedListScreens {

	/** @var DefaultColumns */
	private $default_columns;

	public function __construct( DefaultColumns $default_columns ) {
		$this->default_columns = $default_columns;
	}

	public function get_list_screens() {
		$list_screens = ListScreenTypes::instance()->get_list_screens();

		foreach ( $list_screens as $key => $list_screen ) {
			if ( $this->default_columns->exists( $list_screen->get_key() ) ) {
				unset( $list_screens[ $key ] );
			}
		}

		return $list_screens;
	}

}