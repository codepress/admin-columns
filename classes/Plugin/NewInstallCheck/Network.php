<?php

namespace AC\Plugin\NewInstallCheck;

use AC\Plugin\NewInstallCheck;
use AC\Plugin\VersionStorage;

// TODO this seems isolated from some 'more' intelligent class that handles this
class Network implements NewInstallCheck {

	/**
	 * @var VersionStorage
	 */
	private $version_storage;

	public function __construct( VersionStorage $version_storage ) {
		$this->version_storage = $version_storage;
	}

	public function is_new_install() {
		$result = get_site_option( 'cpupdate_cac-pro' );

		if ( $result ) {
			return false;
		}

		return ! $this->version_storage->get()->is_valid();
	}
}