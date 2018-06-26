<?php

namespace AC\Admin;

abstract class Page {

	/**
	 * Should this page be displayed when no page is selected
	 *
	 * @var bool
	 */
	private $default;

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var bool False when menu page is hidden in menu
	 */
	private $show_in_menu = true;

	/**
	 * Display pages
	 *
	 * @return void
	 */
	public abstract function display();

	/**
	 * Is this the default to to display when no active page is present
	 *
	 * @return bool
	 */
	public function is_default() {
		return (bool) $this->default;
	}

	public function set_default( $default ) {
		$this->default = (bool) $default;

		return $this;
	}

	public function get_slug() {
		return $this->slug;
	}

	public function set_slug( $slug ) {
		$this->slug = sanitize_key( $slug );

		return $this;
	}

	public function show_in_menu() {
		return $this->show_in_menu;
	}

	public function set_show_in_menu( $show ) {
		$this->show_in_menu = (bool) $show;

		return $this;
	}

	public function get_label() {
		return $this->label;
	}

	public function set_label( $label ) {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return string URL
	 */
	public function get_link() {
		return add_query_arg( array( 'tab' => $this->slug ), AC()->admin()->get_settings_url() );
	}

	public function is_current_screen() {
		return AC()->admin()->is_current_page( $this->get_slug() );
	}

	/**
	 * Cast page to an array
	 *
	 * @return array
	 */
	public function to_array() {
		return array(
			'slug'    => $this->get_slug(),
			'label'   => $this->get_label(),
			'default' => $this->is_default(),
		);
	}

	/**
	 * Show the label of the page
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->get_label();
	}

	/**
	 * @param string $action
	 *
	 * @return bool
	 */
	public function verify_nonce( $action ) {
		return wp_verify_nonce( filter_input( INPUT_POST, '_ac_nonce' ), $action );
	}

	/**
	 * Nonce Field
	 *
	 * @param string $action
	 */
	public function nonce_field( $action ) {
		wp_nonce_field( $action, '_ac_nonce', false );
	}

	public function register() {
		// Register Hooks
	}

}