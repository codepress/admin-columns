<?php

namespace AC\Plugin\SetupFactory;

use AC\Plugin\InstallCollection;
use AC\Plugin\Installer;
use AC\Plugin\NewInstallCheck;
use AC\Plugin\Setup;
use AC\Plugin\SetupFactory;
use AC\Plugin\UpdateCollection;
use AC\Plugin\Updater;
use AC\Plugin\Version;
use AC\Plugin\VersionStorage;
use AC\Storage\NetworkOptionFactory;

class Network implements SetupFactory {

	/**
	 * @var string
	 */
	private $version_key;

	/**
	 * @var Version
	 */
	private $version;

	public function __construct( $version_key, Version $version ) {
		$this->version_key = $version_key;
		$this->version = $version;
	}

	public function create( UpdateCollection $updates = null, InstallCollection $installs = null ) {
		$version_storage = new VersionStorage(
			$this->version_key,
			new NetworkOptionFactory()
		);

		$updater = $updates
			? new Updater( $updates, $version_storage )
			: null;

		$installer = $installs
			? new Installer( $installs )
			: null;

		return new Setup(
			$version_storage,
			$this->version,
			new NewInstallCheck\Network( $version_storage ),
			$updater,
			$installer
		);
	}

}