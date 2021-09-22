<?php

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Integrations;
use AC\PluginInformation;

class IsInstalled implements Filter {

	public function filter( Integrations $integrations ) {
		return new Integrations( array_filter( $integrations->all(), [ $this, 'is_installed' ] ) );
	}

	private function is_installed( Integration $integration ) {
		$plugin = new PluginInformation( $integration->get_basename() );

		return $plugin->is_installed();
	}

}