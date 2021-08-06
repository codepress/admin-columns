<?php

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Integrations;
use AC\PluginInformation;

class IsActive implements Filter {

	/**
	 * @var bool
	 */
	private $is_multisite;

	/**
	 * @var bool
	 */
	private $is_network_admin;

	public function __construct( $is_multisite, $is_network_admin ) {
		$this->is_multisite = (bool) $is_multisite;
		$this->is_network_admin = (bool) $is_network_admin;
	}

	public function filter( Integrations $integrations ) {
		return new Integrations( array_filter( $integrations->all(), [ $this, 'is_active' ] ) );
	}

	public function is_active( Integration $integration ) {
		$plugin = new PluginInformation( $integration->get_basename() );

		$is_active = $plugin->is_network_active()
		             || ( ! $this->is_multisite && $plugin->is_active() )
		             || ( $this->is_multisite && ! $this->is_network_admin && $plugin->is_active() );

		// The installed check is required, because it is possible for a plugin to be active without being installed.
		// For example, when a plugin is removed from the file system without visiting the plugin's page.
		return $plugin->is_installed() && $is_active;
	}

}