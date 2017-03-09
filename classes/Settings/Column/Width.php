<?php

class AC_Settings_Column_Width extends AC_Settings_Column
	implements AC_Settings_HeaderInterface {

	/**
	 * @var integer
	 */
	private $width;

	/**
	 * @var string
	 */
	private $width_unit;

	protected function define_options() {
		return array(
			'width',
			'width_unit' => '%',
		);
	}

	private function get_valid_width_units() {
		return array(
			'%'  => '%',
			'px' => 'px',
		);
	}

	private function is_valid_width_unit( $width_unit ) {
		return array_key_exists( $width_unit, $this->get_valid_width_units() );
	}

	public function create_view() {
		$width = $this->create_element( 'text' )
		              ->set_attribute( 'placeholder', __( 'Auto', 'codepress-admin-columns' ) );

		$unit = $this->create_element( 'radio', 'width_unit' )
		             ->set_options( $this->get_valid_width_units() );

		$section = new AC_View( array(
			'width' => $width,
			'unit'  => $unit,
		) );
		$section->set_template( 'settings/setting-width' );

		$view = new AC_View( array(
			'label'    => __( 'Width', 'codepress-admin-columns' ),
			'sections' => array( $section ),
		) );

		return $view;
	}

	public function create_header_view() {
		$content = false;

		if ( $width = $this->get_width() ) {
			$content = $width . $this->get_width_unit();
		}

		$view = new AC_View( array(
			'title'   => __( 'width', 'codepress-admin-columns' ),
			'content' => $content,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_width() {
		return $this->width;
	}

	/**
	 * @param int $width
	 *
	 * @return bool
	 */
	public function set_width( $width ) {
		$width = absint( $width );

		if ( $width <= 0 ) {
			return false;
		}

		$this->width = $width;

		return true;
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