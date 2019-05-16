<?php
namespace AC;

/**
 * Manage default column headings set by WordPress
 */
class DefaultColumns {

	private function get_option_name( $list_screen_key ) {
		return ListScreen::OPTIONS_KEY . $list_screen_key . "__default";
	}

	public function update( $list_screen_key, array $columns ) {
		update_option( $this->get_option_name( $list_screen_key ), $columns, false );
	}

	/**
	 * @return array
	 */
	public function get( $list_screen_key ) {
		return get_option( $this->get_option_name( $list_screen_key ), array() );
	}

	public function delete( $list_screen_key ) {
		delete_option( $this->get_option_name( $list_screen_key ) );
	}

}