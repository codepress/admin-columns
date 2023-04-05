<?php

namespace AC;

class DefaultColumnsRepository {

	private const OPTIONS_KEY = 'cpac_options_';

	/**
	 * @param string $list_screen_key
	 *
	 * @return string
	 */
	private function get_option_name( $list_screen_key ) {
		return self::OPTIONS_KEY . $list_screen_key . "__default";
	}

	/**
	 * @param string $list_screen_key
	 * @param array  $columns
	 *
	 * @return void
	 */
	public function update( $list_screen_key, array $columns ) {
		update_option( $this->get_option_name( $list_screen_key ), $columns, false );
	}

	/**
	 * @param string $list_screen_key
	 *
	 * @return bool
	 */
	public function exists( $list_screen_key ) {
		return false !== get_option( $this->get_option_name( $list_screen_key ) );
	}

	/**
	 * @param string $list_screen_key
	 *
	 * @return array
	 */
	public function get( $list_screen_key ) {
		return get_option( $this->get_option_name( $list_screen_key ), [] );
	}

	/**
	 * @param string $list_screen_key
	 *
	 * @return void
	 */
	public function delete( $list_screen_key ) {
		delete_option( $this->get_option_name( $list_screen_key ) );
	}

}