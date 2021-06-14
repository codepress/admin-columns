<?php

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Integrations;
use AC\PluginInformation;

class IsInstalled implements Filter {

	/**
	 * @var bool
	 */
	private $enabled;

	public function __construct( $enabled = true ) {
		$this->enabled = (bool) $enabled;
	}

	public function filter( Integrations $integrations ) {
		return new Integrations( array_filter( $integrations->all(), [ $this, 'is_installed' ] ) );
	}

	private function is_installed( Integration $integration ) {
		$plugin = new PluginInformation( $integration->get_basename() );

		return $this->enabled === $plugin->is_installed();
	}

}