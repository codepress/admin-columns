<?php

namespace AC;

use AC\Asset\Location\Absolute;

class NoticeChecks implements Registrable {

	/**
	 * @var Absolute
	 */
	private $location;

	public function __construct( Absolute $location ) {
		$this->location = $location;
	}

	public function register() {
		foreach ( $this->get_checks() as $check ) {
			$check->register();
		}
	}

	/**
	 * @return Registrable[]
	 */
	private function get_checks() {
		$checks = [];

		if ( ! ac_is_pro_active() ) {
			$checks[] = new Check\Review( $this->location );

			foreach ( new PromoCollection() as $promo ) {
				$checks[] = new Check\Promotion( $promo );
			}
		}

		$integrations = new IntegrationRepository();

		foreach ( $integrations->find_all() as $integration ) {
			$checks[] = new Check\AddonAvailable( $integration );
		}

		return $checks;
	}

}