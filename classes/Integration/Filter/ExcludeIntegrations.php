<?php

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Integrations;

class ExcludeIntegrations implements Filter {

	/**
	 * @var Integrations
	 */
	private $integrations;

	public function __construct( Integrations $integrations ) {
		$this->integrations = $integrations;
	}

	public function filter( Integrations $integrations ) {
		return new Integrations( array_filter( $integrations->all(), [ $this, 'exclude_integration' ] ) );
	}

	/**
	 * @param Integration $integration
	 *
	 * @return bool
	 */
	private function exclude_integration( Integration $integration ) {
		$t = array_filter( $this->integrations->all(), function ( $i ) use ( $integration ) {
			return $i->get_slug() === $integration->get_slug();
		} );

		return count( $t ) === 0;
	}

}