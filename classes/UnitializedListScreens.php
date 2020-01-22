<?php

namespace AC;

class UnitializedListScreens {

	/** @var DefaultColumns */
	private $default_columns;

	/** @var bool */
	private $is_network;

	public function __construct( DefaultColumns $default_columns, $is_network = false ) {
		$this->default_columns = $default_columns;
		$this->is_network = (bool) $is_network;
	}

	/**
	 * @return ListScreen[]
	 */
	public function get_list_screens() {
		$args = [];

		if ( $this->is_network ) {
			$args['network_only'] = $this->is_network;
		}

		$list_screens = ListScreenTypes::instance()->get_list_screens( $args );

		foreach ( $list_screens as $key => $list_screen ) {
			if ( $this->default_columns->exists( $list_screen->get_key() ) ) {
				unset( $list_screens[ $key ] );
			}
		}

		return $list_screens;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has_list_screen( $key ) {
		$list_screens = $this->get_list_screens();

		return array_key_exists( $key, $list_screens );
	}

}