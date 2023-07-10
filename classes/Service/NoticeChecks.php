<?php

namespace AC\Service;

use AC\Asset\Location\Absolute;
use AC\Check;
use AC\IntegrationRepository;
use AC\Registerable;

class NoticeChecks implements Registerable {

	/**
	 * @var Absolute
	 */
	private $location;

	public function __construct( Absolute $location ) {
		$this->location = $location;
	}

	public function register(): void
    {
		foreach ( $this->get_checks() as $check ) {
			$check->register();
		}
	}

	/**
	 * @return Registerable[]
	 */
	private function get_checks() {
		$checks = [
			new Check\Review( $this->location )
		];

		$integrations = new IntegrationRepository();

        // TODO test
		foreach ( $integrations->find_all() as $integration ) {
			$checks[] = new Check\AddonAvailable( $integration );
		}

		return $checks;
	}

}