<?php
namespace AC;

class Screen {

	/**
	 * @var \WP_Screen
	 */
	protected $screen;

	/**
	 * @var bool
	 */
	protected $ready = false;

	public function __construct() {
		add_action( 'current_screen', array( $this, 'init' ) );
	}

	/**
	 * @param \WP_Screen $screen
	 */
	public function init( \WP_Screen $screen ) {
		$this->screen = $screen;
		$this->ready = true;

		do_action( 'ac/screen', $this, $this->screen->id );
	}

	/**
	 * @return bool
	 */
	public function is_ready() {
		return $this->ready;
	}

	/**
	 * @param $id
	 *
	 * @return bool
	 */
	public function is_screen( $id ) {
		return $this->screen->id === $id;
	}

	/**
	 * @return \WP_Screen
	 */
	public function get_screen() {
		return $this->screen;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->screen->id;
	}

	/**
	 * @return ListScreen|false
	 */
	public function get_list_screen() {
		foreach ( AC()->get_list_screens() as $list_screen ) {
			if ( $list_screen->is_current_screen( $this->screen ) ) {
				return $list_screen;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function is_list_screen() {
		return false !== $this->get_list_screen();
	}

	/**
	 * Check if current screen is plugins screen
	 *
	 * @return bool
	 */
	public function is_plugin_screen() {
		return $this->is_screen( 'plugins' );
	}

	/**
	 * @param string|null $slug
	 *
	 * @return bool
	 */
	public function is_admin_screen( $slug = null ) {
		if ( null !== $slug ) {
			return AC()->admin()->is_current_page( $slug );
		}

		return AC()->admin()->is_admin_screen();
	}

}