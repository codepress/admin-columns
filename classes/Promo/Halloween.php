<?php

namespace AC\Promo;

use AC\Promo;
use AC\Type\DateRange;
use AC\Type\Url\CouponCode;

class Halloween extends Promo {

	/**
	 * @var string
	 */
	private $coupon_code;

	public function __construct( DateRange $date_range, $coupon_code = null ) {
		parent::__construct(
			'halloween',
			sprintf( '%s - %s', __( 'Halloween Sale', 'codepress-admin-columns' ), sprintf( __( 'Get %s Off', 'codepress-admin-columns' ), '20%' ) ),
			20,
			$date_range
		);

		$this->coupon_code = $coupon_code;
	}

	public function get_url() {
		return $this->coupon_code
			? new CouponCode( parent::get_url(), $this->coupon_code )
			: parent::get_url();
	}

}