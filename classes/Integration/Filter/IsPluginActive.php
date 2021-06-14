<?php

namespace AC\Integration\Filter;

use AC\Integration\Filter;
use AC\Integrations;

class IsPluginActive implements Filter {

	/**
	 * @var bool
	 */
	private $enabled;

	public function __construct( $enabled = true ) {
		$this->enabled = (bool) $enabled;
	}

	public function filter( Integrations $_integrations ) {
		$integrations = new Integrations();

		foreach ( $_integrations->all() as $integration ) {

			$add = $this->enabled
				? $integration->is_plugin_active()
				: ! $integration->is_plugin_active();

			if ( $add ) {
				$integrations->add( $integration );
			}
		}

		return $integrations;
	}

}