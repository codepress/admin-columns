<?php

abstract class AC_Addon {

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
	public function get_plugin_dir() {
		return plugin_dir_path( $this->get_file() );
	}

	/**
	 * @return string
	 */
	public function get_plugin_url() {
		return plugin_dir_url( $this->get_file() );
	}

}