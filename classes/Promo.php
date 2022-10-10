<?php

namespace AC;

use AC;
use AC\Type\DateRange;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

abstract class Promo {

	/**
	 * @var string
	 */
	private $slug;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var int
	 */
	private $discount;

	/**
	 * @var DateRange
	 */
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
	 * @return AC\Type\Url
	 */
	public function get_url() {
		return ( new UtmTags( new Site( Site::PAGE_PRICING ), 'promo', null, $this->slug ) );
	}

	/**
	 * @return Type\DateRange
	 */
	public function get_date_range() {
		return $this->date_range;
	}

	/**
	 * @return bool True when promo is active
	 */
	public function is_active() {
		return $this->date_range->in_range() && current_user_can( AC\Capabilities::MANAGE );
	}

}