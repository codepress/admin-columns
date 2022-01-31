<?php

namespace AC\Plugin;

use AC\Plugin;
use AC\Storage\Option;
use AC\Storage\SiteOption;

final class SetupBuilder {

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
	 * @param array $updates
	 *
	 * @return $this
	 */
	public function set_updates( array $updates ) {
		$this->set_network_updates( $updates );
		$this->set_site_updates( $updates );

		return $this;
	}

	/**
	 * @param array $updates
	 *
	 * @return $this
	 */
	public function set_network_updates( array $updates ) {
		$this->network_updates = $updates;

		return $this;
	}

	/**
	 * @param array $updates
	 *
	 * @return $this
	 */
	public function set_site_updates( array $updates ) {
		$this->site_updates = $updates;

		return $this;
	}

	/**
	 * @param array $installers
	 *
	 * @return $this
	 */
	public function set_installers( array $installers ) {
		$this->set_network_installers( $installers );
		$this->set_site_installers( $installers );

		return $this;
	}

	/**
	 * @param array $installers
	 *
	 * @return $this
	 */
	public function set_network_installers( array $installers ) {
		$this->network_installers = $installers;

		return $this;
	}

	/**
	 * @param array $installers
	 *
	 * @return $this
	 */
	public function set_site_installers( array $installers ) {
		$this->site_installers = $installers;

		return $this;
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