<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\View;

class DisplayDate extends Settings\Column {

	const NAME = 'display_date';

	/**
	 * @var string
	 */
	private $display_date;

	protected function define_options() {
		return [
			self::NAME => 'on',
		];
	}

	public function create_view() {
		$toggle = $this->create_element( 'radio', self::NAME );
		$toggle
			->set_options( [
				'on'  => __( 'Yes' ),
				'off' => __( 'No' ),
			] );

		$view = new View();
		$view->set( 'label', __( 'Display Date', 'codepress-admin-columns' ) )
		     ->set( 'setting', $toggle );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_display_date() {
		return $this->display_date;
	}

	/**
	 * @param boolean $label
	 */
	public function set_display_date( $value ) {
		$this->display_date = $value;
	}

}