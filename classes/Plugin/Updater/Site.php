<?php

namespace AC\Plugin\Updater;

use AC\Plugin\Updater;
use AC\Plugin\Version;
use AC\Plugin\VersionStorage;

class Site extends Updater {

	/**
	 * @var Version
	 */
	protected $version;

	/**
	 * @var VersionStorage
	 */
	private $storage;

	public function __construct( $version_key, Version $version ) {
		$this->version = $version;
		$this->storage = new VersionStorage( (string) $version_key );

		// TODO add VersionStorage for previous storage
	}

	/**
	 * @return Version
	 */
	public function get_stored_version() {
		return $this->storage->get();
	}

	protected function update_stored_version( Version $version = null ) {
		$this->storage->save( $version ?: $this->version );
	}

	public function is_new_install() {
		global $wpdb;

		if ( $this->storage->get()->is_valid() ) {
			return false;
		}

		// Before version 3.0.5
		$results = $wpdb->get_results( "SELECT option_id FROM $wpdb->options WHERE option_name LIKE 'cpac_options_%' LIMIT 1" );

		return empty( $results );
	}

}