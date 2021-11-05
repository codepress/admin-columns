<?php

namespace AC\Promo;

use AC\Promo;
use AC\Type\DateRange;
use AC\Type\Url\CouponCode;

class BlackFriday extends Promo {

	/**
	 * @var string
	 */
	private $coupon_code;

	public function __construct( DateRange $date_range, $coupon_code = null ) {
		parent::__construct(
			'black-friday',
			sprintf( __( '%s Off from Black Friday to Cyber Monday', 'codepress-admin-columns' ), '25%' ),
			25,
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