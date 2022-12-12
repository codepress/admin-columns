<?php

namespace AC\Admin\Preference;

use AC\Preferences\Site;

class ListScreen extends Site {

	private const OPTION_LAST_VISITED = 'last_visited_list_key';

	public function __construct( $is_network = false ) {
		parent::__construct( $is_network ? 'network_settings' : 'settings' );
	}

	public function get_last_visited_list_key() {
		return $this->get( self::OPTION_LAST_VISITED );
	}

	public function set_last_visited_list_key( $list_key ) {
		$this->set( self::OPTION_LAST_VISITED, $list_key );
	}

	public function set_list_id( $list_key, $list_id ) {
		$this->set( (string) $list_key, (string) $list_id );
	}

	public function get_list_id( $list_key ) {
		return $this->get( $list_key );
	}

}