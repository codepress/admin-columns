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

	protected function set_properties() {
		$this->properties = array( 'width', 'width_unit' );
	}

	private function get_valid_width_units() {
		return array( '%' => '%', 'px' => 'px' );
	}

	public function render() {
		$view = $this->get_view();

		$label = $view->get_view( 'label' );
		$label->set( 'label', __( 'Width', 'codepress-admin-columns' ) );

		$this->add_element( 'width' )
		     ->set_attribute( 'placeholder', __( 'Auto', 'codepress-admin-columns' ) );

		$this->add_element( 'width_unit', 'radio' )
		     ->add_class( 'unit' )
		     ->set_options( $this->get_valid_width_units() );

		$setting = new AC_Settings_View_Setting_Width();

		foreach ( $this->elements as $name => $element ) {
			$setting->set( $name, $element );
		}

		$view->set_view( $setting, 'setting' );

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
		$this->width = $width;

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
		$this->width_unit = $width_unit;

		return $this;
	}

}