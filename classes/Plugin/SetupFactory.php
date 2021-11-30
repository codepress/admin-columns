<?php

namespace AC\Plugin;

use AC\Storage\NetworkOptionFactory;
use AC\Storage\OptionFactory;

class SetupFactory {

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

	public function create_network( UpdateCollection $updates = null, InstallCollection $installs = null ) {
		$version_storage = new VersionStorage( $this->version_key, new NetworkOptionFactory() );

		$updater = $updates
			? new Updater( $updates, $version_storage )
			: null;

		return new Setup(
			$version_storage,
			$this->version,
			new NewInstallCheck\Network( $version_storage ),
			$updater,
			new Installer( $installs )
		);
	}

	public function create_site( UpdateCollection $updates = null, InstallCollection $installs = null ) {
		$version_storage = new VersionStorage( $this->version_key, new OptionFactory() );

		$updater = $updates
			? new Updater( $updates, $version_storage )
			: null;

		return new Setup(
			$version_storage,
			$this->version,
			new NewInstallCheck\Site( $version_storage ),
			$updater,
			new Installer( $installs )
		);
	}

}