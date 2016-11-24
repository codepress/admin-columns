<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Admin_PromoAbstract {

	/**
	 * @var array
	 */
	private $date_ranges;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var int
	 */
	private $discount;

	/**
	 * @var string
	 */
	private $url;

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function set_title( $title ) {
		$this->title = $title;
	}

	/**
	 * @return int
	 */
	public function get_discount() {
		return $this->discount;
	}

	/**
	 * @param int $discount
	 */
	public function set_discount( $discount ) {
		$this->discount = $discount;
	}

	/**
	 * @return int
	 */
	public function get_url() {
		return $this->url ? $this->url : ac_get_site_url() . '/pricing-purchase/';
	}

	/**
	 * @param int $url
	 */
	public function set_url( $url ) {
		$this->url = $url;
	}

	/**
	 * @param string $start_date
	 * @param string $end_date
	 */
	public function add_date_range( $start_date, $end_date ) {
		if ( ! $start_date || ! $end_date ) {
			return;
		}

		$this->date_ranges[] = array(
			'start' => $start_date,
			'end'   => $end_date,
		);
	}

	/**
	 * @return bool True when promo is active
	 */
	public function is_active() {

		$today = date( 'Y-m-d' );

		foreach ( $this->date_ranges as $date_range ) {
			if ( $today >= $date_range['start'] && $today <= $date_range['end'] ) {
				return true;
			}
		}

		return false;
	}

}