<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class Width extends Settings\Column
	implements Settings\Header {

	/**
	 * @var integer
	 */
	private $width;

	/**
	 * @var string
	 */
	private $width_unit;

	protected function define_options() {
		return [
			'width',
			'width_unit' => '%',
		];
	}

	private function get_valid_width_units() {
		return [
			'%'  => '%',
			'px' => 'px',
		];
	}

	private function is_valid_width_unit( $width_unit ) {
		return array_key_exists( $width_unit, $this->get_valid_width_units() );
	}

	public function create_view() {
		$width = $this->create_element( 'text' )
		              ->set_attribute( 'placeholder', __( 'Auto', 'codepress-admin-columns' ) )
		              ->set_attribute( 'data-width-input', '' );

		$unit = $this->create_element( 'radio', 'width_unit' )
		             ->set_attribute( 'data-unit-input', '' )
		             ->set_options( $this->get_valid_width_units() );

		$section = new View( [
			'width' => $width,
			'unit'  => $unit,
		] );
		$section->set_template( 'settings/setting-width' );

		$view = new View( [
			'label'    => __( 'Width', 'codepress-admin-columns' ),
			'sections' => [ $section ],
		] );

		return $view;
	}

	public function create_header_view() {
		return new View( [
			'title'   => __( 'width', 'codepress-admin-columns' ),
			'content' => $this->width && $this->width_unit ? $this->width . $this->width_unit : '',
		] );
	}

	/**
	 * @return int
	 */
	public function get_width() {
		return $this->width;
	}

	/**
	 * @param $value
	 *
	 * @return bool
	 */
	public function set_width( $value ) {

		// Backwards compatible for AC 2.9
		if ( '' === $value ) {
			$this->width = $value;

			return true;
		}

		$value = absint( $value );

		if ( $value > 0 ) {
			$this->width = $value;

			return true;
		}

		return false;
	}

	/**
	 * @return string
	 */
	public function get_width_unit() {
		return $this->width_unit;
	}

	/**
	 * @param string $width_unit
	 *
	 * @return bool
	 */
	public function set_width_unit( $width_unit ) {
		if ( ! $this->is_valid_width_unit( $width_unit ) ) {
			return false;
		}

		$this->width_unit = $width_unit;

		return true;
	}

}