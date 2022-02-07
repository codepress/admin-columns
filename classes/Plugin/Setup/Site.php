<?php

namespace AC\Plugin\Setup;

use AC\Plugin\InstallCollection;
use AC\Plugin\Setup;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;
use AC\Storage\Option;

final class Site extends Setup {

	public function __construct( Option $storage, Version $version, InstallCollection $installers = null, UpdateCollection $updates = null ) {
		parent::__construct( $storage, $version, $installers, $updates );
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