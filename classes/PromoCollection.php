<?php

namespace AC;

use AC\Promo\BlackFriday;
use AC\Promo\Halloween;
use AC\Type\DateRange;
use DateTime;

class PromoCollection extends ArrayIterator {

	public function __construct() {
		parent::__construct( [
			new Halloween( new DateRange( new DateTime( '2022-10-28' ), new DateTime( '2022-11-01' ) ), 'Halloween22' ),
			new BlackFriday( new DateRange( new DateTime( '2022-11-25' ), new DateTime( '2021-11-30' ) ), 'BlackFriday22' ),
		] );
	}

	/**
	 * Returns the first active promotion it finds
	 * @return Promo|null
	 */
	public function find_active() {
		/** @var Promo $promo */
		foreach ( $this->array as $promo ) {
			if ( $promo->is_active() ) {
				return $promo;
			}
		}

		return null;
	}

}