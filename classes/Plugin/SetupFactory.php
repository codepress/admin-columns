<?php

namespace AC\Plugin;

use AC\Storage\Option;
use AC\Storage\SiteOption;
use InvalidArgumentException;

class SetupFactory {

	public const SITE = 'site';
	public const NETWORK = 'network';

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
	protected $installers;

	/**
	 * @var UpdateCollection
	 */
	protected $updates;

	public function __construct(
		$version_key,
		Version $version,
		InstallCollection $installers = null,
		UpdateCollection $updates = null
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
		$installers = $this->installers ?: new InstallCollection();
		$updates = $this->updates ?: new UpdateCollection();

		switch ( $type ) {
			case self::NETWORK:
				return new Setup\Network(
					new SiteOption( $this->version_key ),
					$this->version,
					$installers,
					$updates
				);

			case self::SITE:
				return new Setup\Site(
					new Option( $this->version_key ),
					$this->version,
					$installers,
					$updates
				);

			default:
				throw new InvalidArgumentException( 'Expected valid setup type.' );
		}
	}

}