<?php

namespace AC\Plugin\Setup;

use AC\Plugin\Setup;
use AC\Plugin\Version;

final class Site extends Setup {

	public function __construct( Definition\Site $definition, Version $version ) {
		parent::__construct( $definition, $version );
	}

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