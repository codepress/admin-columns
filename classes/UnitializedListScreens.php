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

	private function get_network_list_screens() {
		return ListScreenTypes::instance()->get_list_screens( [ 'network_only' => true ] );
	}

	private function get_site_list_screens() {
		return ListScreenTypes::instance()->get_list_screens( [ 'site_only' => true ] );
	}

	/**
	 * @return ListScreen[]
	 */
	public function get_list_screens() {
		$list_screens = $this->is_network
			? $this->get_network_list_screens()
			: $this->get_site_list_screens();

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