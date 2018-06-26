<?php

namespace AC\Admin;

abstract class Help {

	abstract public function get_title();

	abstract public function get_content();

	public function __construct() {
		$this->register_tab();
	}

	/**
	 * @return string Based on class name
	 */
	public function get_id() {
		$parts = explode( '_', get_class( $this ) );

		return sanitize_key( strtolower( end( $parts ) ) );
	}

	/**
	 * Register tabs on current screen
	 */
	public function register_tab() {
		$wp_screen = get_current_screen();

		if ( ! $wp_screen ) {
			return;
		}

		$wp_screen->add_help_tab( array(
			'id'      => 'ac-tab-' . $this->get_id(),
			'title'   => $this->get_title(),
			'content' => $this->get_content(),
		) );
	}

}