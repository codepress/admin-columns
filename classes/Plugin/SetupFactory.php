<?php

namespace AC\Plugin;

use AC\Storage\Option;
use AC\Storage\SiteOption;

final class SetupFactory {

	/**
	 * @var string
	 */
	private $version_key;

	/**
	 * @var Version
	 */
	private $version;

	/**
	 * @var bool
	 */
	private $is_network_admin;

	/**
	 * @var InstallCollection
	 */
	private $installers;

	/**
	 * @var UpdateCollection
	 */
	private $updates;

	public function __construct(
		$version_key,
		Version $version,
		$is_network_admin,
		InstallCollection $installers,
		UpdateCollection $updates
	) {
		$this->version_key = (string) $version_key;
		$this->version = $version;
		$this->is_network_admin = $is_network_admin;
		$this->installers = $installers;
		$this->updates = $updates;
	}

	/**
	 * @return Setup
	 */
	public function create() {
		if ( $this->is_network_admin ) {
			return new Setup\Network(
				new SiteOption( $this->version_key ),
				$this->version,
				$this->installers,
				$this->updates
			);
		}

		return new Setup\Site(
			new Option( $this->version_key ),
			$this->version,
			$this->installers,
			$this->updates
		);
	}

}