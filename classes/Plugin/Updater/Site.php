<?php

namespace AC\Plugin\Updater;

use AC\Autoloader;
use AC\Plugin\StoredVersion;
use AC\Plugin\Updater;
use AC\Plugin\Version;

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

	// TODO maybe factory
	public static function create_by_namespace( $namespace, StoredVersion $stored_version, Version $version ) {
		$updates = [];

		foreach ( Autoloader::instance()->get_class_names_from_dir( $namespace ) as $class ) {
			$updates[] = new $class( $version );
		}

		return new self( $stored_version, $version, $updates );
	}

}