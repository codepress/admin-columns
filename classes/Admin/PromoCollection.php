<?php
namespace AC\Admin;

use AC\Admin\Entity\DateRange;
use AC\Admin\Promo\BlackFriday;
use AC\ArrayIterator;
use DateTime;

class PromoCollection extends ArrayIterator {

	public function __construct() {
		parent::__construct( [
			new BlackFriday( new DateRange( new DateTime( '2019-11-28' ), new DateTime( '2019-12-03' ) ) ),
			new BlackFriday( new DateRange( new DateTime( '2020-11-26' ), new DateTime( '2020-11-31' ) ) ),
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