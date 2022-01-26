<?php

namespace AC\Plugin\Setup;

use AC\Plugin\InstallCollection;
use AC\Plugin\Setup;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Version;
use AC\Storage\KeyValuePair;
use AC\Storage\Option;

class Site extends Setup {

	/**
	 * @var KeyValuePair
	 */
	private $storage;

	public function __construct(
		$version_key, // TODO David something more semantic might work better here. e.g. Plugin?
		Version $version,
		InstallCollection $installers,
		UpdateCollection $updates
	) {
		parent::__construct( $version, $installers, $updates );

		$this->storage = new Option( $version_key );
	}

	protected function update_stored_version( Version $version ) {
		$this->storage->save( (string) $version );
	}

	protected function get_stored_version() {
		return new Version( (string) $this->storage->get() );
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