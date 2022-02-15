<?php

namespace AC\Plugin;

use AC\Storage\Option;
use AC\Storage\SiteOption;
use InvalidArgumentException;

final class SetupFactory {

	const SITE = 'site';
	const NETWORK = 'network';

	/**
	 * @var string
	 */
	private $version_key;

	/**
	 * @var Version
	 */
	private $version;

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
		InstallCollection $installers,
		UpdateCollection $updates
	) {
		$this->version_key = (string) $version_key;
		$this->version = $version;
		$this->installers = $installers;
		$this->updates = $updates;
	}

	/**
	 * @return Setup
	 */
	public function create( $type ) {
		switch ( $type ) {
			case self::NETWORK:
				return new Setup\Network(
					new SiteOption( $this->version_key ),
					$this->version,
					$this->installers,
					$this->updates
				);

			case self::SITE:
				return new Setup\Site(
					new Option( $this->version_key ),
					$this->version,
					$this->installers,
					$this->updates
				);

			default:
				throw new InvalidArgumentException( 'Expected valid setup type.' );
		}

	}

}