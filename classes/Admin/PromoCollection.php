<?php
namespace AC\Admin;

use AC\Admin\Entity\DateRange;
use AC\Admin\Promo\BlackFriday;
use DateTime;

class PromoCollection {

	/** @var Promo[] */
	private $items;

	public function __construct() {
		$this->add( new BlackFriday( new DateRange( new DateTime( '2019-11-28' ), new DateTime( '2019-12-03' ) ) ) );
		$this->add( new BlackFriday( new DateRange( new DateTime( '2020-11-26' ), new DateTime( '2020-11-31' ) ) ) );
	}

	public function add( Promo $promo ) {
		$this->items[] = $promo;
	}

	/**
	 * @return Promo[]
	 */
	public function all() {
		return $this->items;
	}

	/**
	 * Returns the first active promotion it finds
	 * @return Promo|null
	 */
	public function find_active() {
		foreach ( $this->items as $item ) {
			if ( $item->is_active() ) {
				return $item;
			}
		}

		return null;
	}

}