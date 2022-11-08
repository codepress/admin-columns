<?php

namespace AC;

use AC\Asset\Location\Absolute;

class NoticeChecks implements Registerable {

	/**
	 * @var Absolute
	 */
	private $location;

	/**
	 * @var bool
	 */
	private $is_acp_active;

	public function __construct( Absolute $location, bool $is_acp_active ) {
		$this->location = $location;
		$this->is_acp_active = $is_acp_active;
	}

	public function register() {
		foreach ( $this->get_checks() as $check ) {
			$check->register();
		}
	}

	/**
	 * @return Registerable[]
	 */
	private function get_checks() {
		$checks = [];

		// TODO
		if ( ! $this->is_acp_active ) {
			$checks[] = new Check\Review( $this->location, $this->is_acp_active );
		}

		$integrations = new IntegrationRepository();

		foreach ( $integrations->find_all() as $integration ) {
			$checks[] = new Check\AddonAvailable( $integration, $this->is_acp_active );
		}

		return $checks;
	}

}