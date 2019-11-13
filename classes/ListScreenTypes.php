<?php
namespace AC;

class ListScreenTypes {

	/** @var ListScreenTypes */
	private static $instance = null;

	/** @var ListScreen[] */
	private $list_screens;

	/**
	 * @return ListScreenTypes
	 */
	static public function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param ListScreen $list_screen
	 *
	 * @return $this
	 */
	public function register_list_screen( ListScreen $list_screen ) {
		$this->list_screens[ $list_screen->get_key() ] = $list_screen;

		return $this;
	}

	public function get_list_screens() {
		return $this->list_screens;
	}

	/**
	 * @param string $key
	 *
	 * @return ListScreen|false
	 */
	public function get_list_screen_by_key( $key ) {
		foreach ( $this->list_screens as $list_screen ) {
			if ( $key === $list_screen->get_key() ) {
				return clone $list_screen;
			}
		}

		return null;
	}

}