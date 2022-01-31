<?php

namespace AC\Plugin;

use AC\Plugin;
use AC\Plugin\Setup\Definition;

class SetupFactory {

	/**
	 * @var string
	 */
	protected $version_key;

	/**
	 * @var Plugin\Version
	 */
	protected $version;

	/**
	 * @var Definition\Site
	 */
	protected $site_definition;

	/**
	 * @var Definition\Network
	 */
	protected $network_definition;

	public function __construct(
		$version_key,
		Plugin\Version $version,
		Definition\Site $site_definition = null,
		Definition\Network $network_definition = null
	) {
		if ( null === $site_definition ) {
			$site_definition = new Definition\Site( $version_key );
		}

		if ( null === $network_definition ) {
			$network_definition = new Definition\Network( $version_key );
		}

		$this->version_key = $version_key;
		$this->version = $version;
		$this->site_definition = $site_definition;
		$this->network_definition = $network_definition;
	}

	public function create() {
		if ( is_network_admin() ) {
			return new Plugin\Setup\Network(
				$this->network_definition,
				$this->version
			);
		}

		return new Plugin\Setup\Site(
			$this->site_definition,
			$this->version
		);
	}

}