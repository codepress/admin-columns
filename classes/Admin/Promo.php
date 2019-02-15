<?php

namespace AC\Admin;

abstract class Promo {

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
		if ( null === $this->url ) {

			$campaign = str_replace( get_parent_class( $this ) . '_', '', get_class( $this ) );

			$this->set_url( ac_get_site_utm_url( 'pricing-purchase', 'promo', null, $campaign ) );
		}

		return $this->url;
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
		return $this->get_active_date_range() ? true : false;
	}

	/**
	 * Active date range
	 * @return array|false
	 */
	private function get_active_date_range() {
		$today = date( 'Y-m-d' );

		foreach ( $this->date_ranges as $date_range ) {
			if ( $today >= $date_range['start'] && $today <= $date_range['end'] ) {
				return $date_range;
			}
		}

		return false;
	}

	/**
	 * @return bool|string
	 */
	public function end_date() {
		$date_range = $this->get_active_date_range();

		return $date_range ? date_i18n( get_option( 'date_format' ), strtotime( $date_range['end'] ) ) : false;
	}

	/**
	 * Render HTML
	 */
	public function display() { ?>
		<h3>
			<?php echo esc_html( $this->get_title() ); ?>
		</h3>
		<a target="_blank" href="<?php echo esc_url( $this->get_url() ); ?>" class="acp-button">
			<?php echo esc_html( sprintf( __( 'Get %s Off!', 'codepress-admin-columns' ), $this->get_discount() . '%' ) ); ?>
		</a>
		<p class="nomargin">
			<?php echo esc_html( sprintf( __( "Discount is valid until %s", 'codepress-admin-columns' ), $this->end_date() ) ); ?>
		</p>
		<?php
	}

}