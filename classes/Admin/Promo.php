<?php

namespace AC\Admin;

use AC\Admin\Entity\DateRange;

abstract class Promo {

	/** @var string */
	private $slug;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var int
	 */
	private $discount;

	/** @var DateRange */
	private $date_range;

	public function __construct( $slug, $title, $discount, DateRange $date_range ) {
		$this->slug = sanitize_key( $slug );
		$this->title = $title;
		$this->discount = $discount;
		$this->date_range = $date_range;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @return int
	 */
	public function get_discount() {
		return $this->discount;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return ac_get_site_utm_url( 'pricing-purchase', 'promo', null, $this->slug );
	}

	/**
	 * @return Entity\DateRange
	 */
	public function get_date_range() {
		return $this->date_range;
	}

	/**
	 * @return bool True when promo is active
	 */
	public function is_active() {
		return $this->date_range->in_range();
	}

}