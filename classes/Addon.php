<?php

namespace AC;

abstract class Addon {

	/**
	 * Return the file from this plugin
	 *
	 * @return string
	 */
	abstract protected function get_file();

	/**
	 * @return string
	 */
	public function get_basename() {
		return plugin_basename( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_dir() {
		return plugin_dir_path( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return plugin_dir_url( $this->get_file() );
	}

	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function get_plugin_url() {
		_deprecated_function( __METHOD__, '3.2', 'AC\Addon::get_url()' );

		return $this->get_url();
	}

	/**
	 * @return string
	 *
	 * @deprecated
	 */
	public function get_plugin_dir() {
		_deprecated_function( __METHOD__, '3.2', 'AC\Addon::get_dir()' );

		return $this->get_dir();
	}
}