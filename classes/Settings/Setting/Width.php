<?php

class AC_Settings_Setting_Width extends AC_Settings_SettingAbstract {

	/**
	 * @var integer
	 */
	private $width;

	/**
	 * @var string
	 */
	private $width_unit = '%';

	public function set_id() {
		$this->id = 'width';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'width', 'width_unit' );
	}

	private function get_valid_width_units() {
		return array( '%' => '%', 'px' => 'px' );
	}

	public function view() {
		$width = $this->create_element( 'width' )
		              ->set_attribute( 'placeholder', __( 'Auto', 'codepress-admin-columns' ) );

		$unit = $this->create_element( 'width_unit', 'radio' )
		             ->add_class( 'unit' )
		             ->set_options( $this->get_valid_width_units() );

		$section = new AC_Settings_View();
		$section->set_template( 'settings-width' )
		        ->set( 'width', $width )
		        ->set( 'width_unit', $unit );

		$view = new AC_Settings_View();
		$view->set( 'label', __( 'Width', 'codepress-admin-columns' ) )
		     ->set( 'sections', array( $section ) );

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
	 * @return $this
	 */
	public function set_width( $width ) {
		$this->width = absint( $width );

		return $this;
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
	 * @return $this
	 */
	public function set_width_unit( $width_unit ) {
		if ( array_key_exists( $width_unit, $this->get_valid_width_units() ) ) {
			$this->width_unit = $width_unit;
		}

		return $this;
	}

}