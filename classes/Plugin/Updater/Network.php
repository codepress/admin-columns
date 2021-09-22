<?php

namespace AC\Plugin\Updater;

use AC\Plugin\Updater;
use AC\Plugin\Version;
use AC\Plugin\VersionStorage;

class Network extends Updater {

	/**
	 * @var Version
	 */
	private $version;

	/**
	 * @var VersionStorage
	 */
	private $storage;

	public function __construct( $version_key, Version $version ) {
		$this->version = $version;
		$this->storage = new VersionStorage( $version_key, true );
	}

	/**
	 * Current and before version 5 check
	 * @return bool
	 */
	public function is_new_install() {
		if ( $this->storage->get()->is_valid() ) {
			return false;
		}

		return empty( get_site_option( 'cpupdate_cac-pro' ) );
	}

	/**
	 * @param Version|null $version
	 *
	 * @return bool
	 */
	protected function update_stored_version( Version $version = null ) {
		return $this->storage->save( $version ?: $this->version );
	}

	/**
	 * @return Version
	 */
	public function get_stored_version() {
		return $this->storage->get();
	}

}