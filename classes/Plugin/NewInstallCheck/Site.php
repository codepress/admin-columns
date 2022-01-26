<?php

namespace AC\Plugin\NewInstallCheck;

use AC\Plugin\NewInstallCheck;
use AC\Plugin\VersionStorage;

// TODO this seems isolated from some 'more' intelligent class that handles this
class Site implements NewInstallCheck {

	/**
	 * @var VersionStorage
	 */
	private $version_storage;

	// TODO VersionStorage is a dangerous object. It should not be decoupled from it's intelligence
	public function __construct( VersionStorage $version_storage ) {
		$this->version_storage = $version_storage;
	}

	public function is_new_install() {
		global $wpdb;

		$results = $wpdb->get_results( "SELECT option_id FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%' LIMIT 1" );

		if ( $results ) {
			return false;
		}

		return ! $this->version_storage->get()->is_valid();
	}
}