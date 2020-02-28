<?php

namespace AC\Promo;

use AC\Entity\DateRange;
use AC\Promo;

class BlackFriday extends Promo {

	public function __construct( DateRange $date_range ) {
		parent::__construct(
			'black-friday',
			__( '30% Off from Black Friday until Cyber Monday', 'codepress-admin-columns' ),
			30,
			$date_range
		);
	}

}