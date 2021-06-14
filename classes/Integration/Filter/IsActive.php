<?php

namespace AC\Integration\Filter;

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

	/**
	 * @var bool
	 */
	private $enabled;

	public function __construct( $is_multisite, $is_network_admin, $enabled = true ) {
		$this->is_multisite = (bool) $is_multisite;
		$this->is_network_admin = (bool) $is_network_admin;
		$this->enabled = (bool) $enabled;
	}

	public function filter( Integrations $_integrations ) {
		$integrations = new Integrations();

		foreach ( $_integrations->all() as $integration ) {

			$plugin = new PluginInformation( $integration->get_basename() );

			$is_active = $plugin->is_network_active()
			             || ( ! $this->is_multisite && $plugin->is_active() )
			             || ( $this->is_multisite && ! $this->is_network_admin && $plugin->is_active() );

			$add = $this->enabled
				? $is_active
				: ! $is_active;

			if ( $add ) {
				$integrations->add( $integration );
			}
		}

		return $integrations;
	}

}