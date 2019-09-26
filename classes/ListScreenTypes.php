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
		$this->list_screens[] = $list_screen;

		return $this;
	}

	/**
	 * @return ListScreen[]
	 */
	public function get_list_screens() {
		return $this->list_screens;
	}

}