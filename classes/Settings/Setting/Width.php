<?php

class AC_Settings_Setting_Width extends AC_Settings_SettingAbstract {

	/**
	 * @var integer
	 */
	private $width;

	/**
	 * @var string
	 */
	private $width_unit = 'px';

	protected function set_properties() {
		$this->properties = array( 'width', 'width_unit' );
	}

	public function render() {
		$this->set_label( __( 'Width', 'codepress-admin-columns' ) );

		// todo: change layout to default input and change auto to Auto
		$this->add_element( 'width' )
		     ->set_attribute( 'placeholder', __( 'auto', 'codepress-admin-columns' ) )
		     ->set_value( $this->get_width() );

		$this->create_element( 'width_unit', 'radio' )
		     ->add_class( 'unit' )
		     ->set_options( array(
			     'px' => 'px',
			     '%'  => '%',
		     ) )
		     ->set_value( $this->get_width_unit() );

		$view = new AC_Settings_View_Setting_Width();

		foreach ( $this->elements as $name => $element ) {
			$view->set( $name, $element );
		}

		$this->view->nest( $view, 'setting' );

		return parent::render();
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