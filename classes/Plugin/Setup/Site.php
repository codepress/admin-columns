<?php

namespace AC\Plugin\Setup;

use AC\Plugin\Setup;

final class Site extends Setup {

	protected function is_new_install() {
		global $wpdb;

		$sql = "
			SELECT option_id 
			FROM $wpdb->options 
			WHERE option_name LIKE 'cpac_options_%' LIMIT 1
		";

		$results = $wpdb->get_results( $sql );

		if ( $results ) {
			return false;
		}

		return ! $this->get_stored_version()->is_valid();
	}

}