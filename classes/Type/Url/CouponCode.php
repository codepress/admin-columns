<?php

namespace AC\Type\Url;

use AC\Type\QueryAware;
use AC\Type\QueryAwareTrait;

class CouponCode implements QueryAware {

	use QueryAwareTrait;

	const ARG_COUPON = 'coupon_code';

	public function __construct( $url, $coupon_code ) {
		$this->url = $url->get_url();

		$this->add_one( self::ARG_COUPON, $coupon_code );
	}

	public function add_coupon_code( $coupon_code ) {
		$this->add_one( self::ARG_COUPON, $coupon_code );

		return $this;
	}

}