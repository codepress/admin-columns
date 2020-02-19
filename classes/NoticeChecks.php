<?php

namespace AC;

use AC\Admin\PromoCollection;

class NoticeChecks implements Registrable {

	public function register() {
		foreach ( $this->get_checks() as $check ) {
			$check->register();
		}
	}

	/**
	 * @return Registrable[]
	 */
	private function get_checks() {
		$checks = [
			new Check\Review(),
		];

		if ( ! ac_is_pro_active() ) {
			foreach ( new PromoCollection() as $promo ) {
				$checks[] = new Check\Promotion( $promo );
			}
		}

		foreach ( new Integrations() as $integration ) {
			$checks[] = new Check\AddonAvailable( $integration );
		}

		return $checks;
	}

}