<?php

namespace AC\Plugin;

use AC\Plugin;
use AC\Storage\Option;
use AC\Storage\SiteOption;
use InvalidArgumentException;

final class SetupBuilder {

	const SITE = 'site';
	const NETWORK = 'network';

	/**
	 * @var string
	 */
	private $version_key;

	/**
	 * @var Plugin\Version
	 */
	protected $version;

	/**
	 * @var array
	 */
	protected $network_installers = [];

	/**
	 * @var array
	 */
	protected $network_updates = [];

	/**
	 * @var array
	 */
	protected $site_installers = [];

	/**
	 * @var array
	 */
	protected $site_updates = [];

	public function __construct( $version_key, Plugin\Version $version ) {
		$this->version_key = $version_key;
		$this->version = $version;
	}

	/**
	 * @param array       $updates
	 * @param string|null $type
	 *
	 * @return $this
	 */
	public function set_updates( array $updates, $type = null ) {
		switch ( $type ) {
			case self::NETWORK:
				$this->network_updates = $updates;

				break;
			case self::SITE:
				$this->site_updates = $updates;

				break;
			case null:
				$this->network_updates = $updates;
				$this->site_updates = $updates;

				break;
			default:
				$this->throwInvalidTypeException();
		}

		return $this;
	}

	/**
	 * @param array       $installers
	 * @param string|null $type
	 *
	 * @return $this
	 */
	public function set_installers( array $installers, $type = null ) {
		switch ( $type ) {
			case self::NETWORK:
				$this->network_updates = $installers;

				break;
			case self::SITE:
				$this->site_updates = $installers;

				break;
			case null:
				$this->network_updates = $installers;
				$this->site_updates = $installers;

				break;
			default:
				$this->throwInvalidTypeException();
		}

		return $this;
	}

	private function throwInvalidTypeException() {
		throw new InvalidArgumentException( 'Expected null or a valid setup type.' );
	}

	public function build() {
		if ( is_network_admin() ) {
			return new Plugin\Setup\Network(
				new SiteOption( $this->version_key ),
				$this->version,
				new InstallCollection( $this->network_installers ),
				new UpdateCollection( $this->network_updates )
			);
		}

		return new Plugin\Setup\Site(
			new Option( $this->version_key ),
			$this->version,
			new InstallCollection( $this->site_installers ),
			new UpdateCollection( $this->site_updates )
		);
	}

}