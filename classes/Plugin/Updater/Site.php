<?php

namespace AC\Plugin\Updater;

use AC\Plugin\Updater;

// TODO remove
class Site extends Updater {

	public function is_new_install() {
		global $wpdb;

		if ( ! $this->stored_version->get_previous()->is_valid() ) {
			return true;
		}

		if ( $this->stored_version->get()->is_valid() ) {
			return false;
		}

		// Before version 3.0.5
		$results = $wpdb->get_results( "SELECT option_id FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%' LIMIT 1" );

		return empty( $results );
	}

}