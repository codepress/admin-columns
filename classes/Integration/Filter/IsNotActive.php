<?php

namespace AC\Integration\Filter;

use AC\Integration;
use AC\Integration\Filter;
use AC\Integrations;

class IsNotActive implements Filter {

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
		return new Integrations( array_filter( $integrations->all(), [ $this, 'is_not_active' ] ) );
	}

	private function is_not_active( Integration $integration ) {
		return ! ( new IsActive( $this->is_multisite, $this->is_network_admin ) )->is_active( $integration );
	}

}